<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Product\Repository\Event;


use Shop\Event\Event;
use Shop\UUID\UUID;

class ProductHasNotBeenFound extends Event
{
    /**
     * @var string
     */
    private $productName;

    /**
     * @var string
     */
    private $userEmail;

    /**
     * ProductHasNotBeenFoundEvent constructor.
     * @param UUID $eventId
     * @param \DateTime $dateTime
     * @param string $productName
     * @param string $userEmail
     */
    public function __construct(UUID $eventId, \DateTime $dateTime, string $productName, string $userEmail)
    {
        parent::__construct($eventId, $dateTime);
        $this->productName = $productName;
        $this->userEmail = $userEmail;

    }

    /**
     * @inheritDoc
     */
    public function getEventFamilyName(): string
    {
        return 'ProductRepository';
    }

    /**
     * @return string
     */
    public function getProductName(): string
    {
        return $this->productName;
    }

    /**
     * @return string
     */
    public function getUserEmail(): string
    {
        return $this->userEmail;
    }

}