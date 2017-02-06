<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Event\Serializer;


use BartoszBartniczak\EventSourcing\Event\Event;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer as SymfonySerializer;

class SymfonyJsonSerializerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Event\Serializer\SymfonyJsonSerializer::__construct
     */
    public function testConstructor()
    {
        $symfonySerializer = $this->getMockBuilder(SymfonySerializer::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();
        /* @var $symfonySerializer SymfonySerializer */

        $symfonyJsonSerializer = new SymfonyJsonSerializer($symfonySerializer);
        $this->assertInstanceOf(Serializer::class, $symfonyJsonSerializer);
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Event\Serializer\SymfonyJsonSerializer::serialize
     */
    public function testSerialize()
    {

        $event = $this->getMockBuilder(Event::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        /* @var $event Event */

        $symfonySerializer = new SymfonySerializer([new class() implements NormalizerInterface
        {
            /**
             * @inheritDoc
             */
            public function normalize($object, $format = null, array $context = array())
            {
                return ['result'];
            }

            /**
             * @inheritDoc
             */
            public function supportsNormalization($data, $format = null)
            {
                return true;
            }

        }], [new JsonEncoder()]);

        $symfonyJsonSerializer = new SymfonyJsonSerializer($symfonySerializer);
        $result = $symfonyJsonSerializer->serialize($event);
        $this->assertSame('["result"]', $result);
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Event\Serializer\SymfonyJsonSerializer::deserialize
     */
    public function testDeserialize()
    {
        $event = $this->getMockBuilder(Event::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        /* @var $event Event */

        $symfonySerializer = new SymfonySerializer([new class($event) implements DenormalizerInterface
        {

            /**
             * @var Event
             */
            protected $event;

            /**
             *  constructor.
             */
            public function __construct(Event $event)
            {
                $this->event = $event;
            }


            /**
             * @inheritDoc
             */
            public function denormalize($data, $class, $format = null, array $context = array())
            {
                return $this->event;
            }

            /**
             * @inheritDoc
             */
            public function supportsDenormalization($data, $type, $format = null)
            {
                return true;
            }

        }], [new JsonEncoder()]);

        $symfonyJsonSerializer = new SymfonyJsonSerializer($symfonySerializer);
        $result = $symfonyJsonSerializer->deserialize('{"name":"Event"}');
        $this->assertSame($event, $result);
    }

}
