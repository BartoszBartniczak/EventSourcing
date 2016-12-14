<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Logger;


use Psr\Log\LoggerInterface;

class DumpLogger implements LoggerInterface
{
    public function emergency($message, array $context = array())
    {
        throw new \BadMethodCallException();
    }

    public function alert($message, array $context = array())
    {
        throw new \BadMethodCallException();
    }

    public function critical($message, array $context = array())
    {
        throw new \BadMethodCallException();
    }

    public function error($message, array $context = array())
    {
        throw new \BadMethodCallException();
    }

    public function warning($message, array $context = array())
    {
        throw new \BadMethodCallException();
    }

    public function notice($message, array $context = array())
    {
        throw new \BadMethodCallException();
    }

    public function info($message, array $context = array())
    {
        dump($message, $context);
    }

    public function debug($message, array $context = array())
    {
        throw new \BadMethodCallException();
    }

    public function log($level, $message, array $context = array())
    {
        throw new \BadMethodCallException();
    }


}