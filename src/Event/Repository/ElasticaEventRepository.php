<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Event\Repository;

use BartoszBartniczak\EventSourcing\Event\Event;
use BartoszBartniczak\EventSourcing\Event\EventStream;
use BartoszBartniczak\EventSourcing\Event\Serializer\Serializer;
use BartoszBartniczak\EventSourcing\EventAggregate\EventAggregate;
use BartoszBartniczak\NamingConventionConverter\Converter as NamingConvention;
use Elastica\Client;
use Elastica\Document;
use Elastica\Index;
use Elastica\Type;

class ElasticaEventRepository implements EventRepository
{

    /**
     * @var Client;
     */
    protected $elastica;

    /**
     * @var Index
     */
    protected $index;

    /**
     * @var string
     */
    protected $indexName;

    /**
     * @var NamingConvention
     */
    protected $typeNamingStrategy;

    /**
     * @var Serializer
     */
    private $serializer;


    /**
     * ElasticaEventRepository constructor.
     * @param Client $elastica
     * @param Serializer $serializer
     * @param string $indexName
     * @param NamingConvention $typeNamingConvention
     */
    public function __construct(Client $elastica, Serializer $serializer, string $indexName, NamingConvention $typeNamingConvention)
    {
        $this->elastica = $elastica;
        $this->serializer = $serializer;
        $this->indexName = $indexName;
        $this->typeNamingStrategy = $typeNamingConvention;
    }

    public function saveEventAggregate(EventAggregate $eventAggregate)
    {
        $this->saveEventStream($eventAggregate->getUncommittedEvents());
        $eventAggregate->commit();
    }

    public function saveEventStream(EventStream $stream)
    {
        $types = [];
        $documents = [];

        foreach ($stream as $event) {

            /* @var $event Event */
            if (!isset($types[$event->getEventFamilyName()])) {
                $types[$event->getEventFamilyName()] = $this->getType($event->getEventFamilyName());
            }

            $documents[$event->getEventFamilyName()][] = $this->eventToDocument($event);
        }

        foreach ($types as $key => $type) {
            /* @var $type Type */
            $type->addDocuments($documents[$key]);
        }

        $this->getIndex()->refresh();
    }

    protected function getType(string $typeName): Type
    {
        $typeName = $this->typeNamingStrategy->convert($typeName);

        $index = $this->getIndex();
        $type = $index->getType($typeName);
        return $type;
    }

    protected function getIndex(): Index
    {
        if (!$this->index instanceof Index) {
            $this->index = $this->elastica->getIndex($this->indexName);
            if (!$this->index->exists()) {
                $this->index->create();
            }
        }
        return $this->index;
    }

    protected function eventToDocument(Event $event): Document
    {
        return new Document($event->getEventId()->toNative(), $this->serializer->serialize($event));
    }

    public function saveEvent(Event $event)
    {
        $type = $this->getType($event->getEventFamilyName());
        $type->addDocument($this->eventToDocument($event));
        $this->getIndex()->refresh();
    }

    /**
     * @inheritDoc
     */
    public function find(string $eventFamily = null, array $parameters = null): EventStream
    {
        $type = $this->getType($this->typeNamingStrategy->convert($eventFamily));
        $response = $type->search($parameters);

        $eventStream = new EventStream();
        foreach ($response as $result) {
            /* @var $result \Elastica\Result */
            $json = (string)json_encode($result->getSource());
            $eventStream[] = $this->serializer->deserialize($json);
        }

        return $eventStream;
    }


}