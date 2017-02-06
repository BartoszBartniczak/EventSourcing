<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Event\Serializer;


trait ExtractClassNameTrait
{
    /**
     * @param $data
     * @return string
     */
    protected function tryToExtractClassName($data): string
    {
        $data = json_decode($data, true);
        if (!isset($data['name'])) {
            throw new InvalidArgumentException('Cannot extract class name of the event');
        }
        return $data['name'];
    }
}