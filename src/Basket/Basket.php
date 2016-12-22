<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Basket;


use Shop\Basket\Event\BasketHasBeenClosed;
use Shop\Basket\Event\BasketHasBeenCreated;
use Shop\Basket\Event\ProductHasBeenAddedToTheBasket;
use Shop\Basket\Event\ProductHasBeenRemovedFromTheBasket;
use Shop\Basket\Event\QuantityOfTheProductHasBeenChanged;
use Shop\Basket\Position\Position;
use Shop\Basket\Position\PositionArray;
use Shop\EventAggregate\EventAggregate;
use Shop\Product\Id as ProductId;
use Shop\Product\Product;

class Basket extends EventAggregate
{

    /**
     * @var Id
     */
    private $id;

    /**
     * @var array
     */
    private $positions;

    /**
     * @var string
     */
    private $ownerEmail;

    /**
     * @var bool
     */
    private $open;

    /**
     * Basket constructor.
     * @param Id $id
     * @param string $ownerEmail
     */
    public function __construct(Id $id, string $ownerEmail)
    {
        parent::__construct();
        $this->id = $id;
        $this->positions = new PositionArray();
        $this->ownerEmail = $ownerEmail;
        $this->open = true;
    }

    /**
     * @return PositionArray
     */
    public function getPositions(): PositionArray
    {
        return $this->positions;
    }

    /**
     * @param BasketHasBeenCreated $basketHasBeenCreated
     */
    public function handleBasketHasBeenCreated(BasketHasBeenCreated $basketHasBeenCreated)
    {
        $this->id = $basketHasBeenCreated->getBasket()->getId();
        $this->ownerEmail = $basketHasBeenCreated->getBasket()->getOwnerEmail();
    }

    /**
     * @return Id
     */
    public function getId(): Id
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getOwnerEmail(): string
    {
        return $this->ownerEmail;
    }

    /**
     * @param ProductHasBeenAddedToTheBasket $event
     */
    public function handleProductHasBeenAddedToTheBasket(ProductHasBeenAddedToTheBasket $event)
    {
        $this->add($event->getProduct(), $event->getQuantity());
    }

    /**
     * @param Product $product
     * @param float $quantity
     */
    private function add(Product $product, float $quantity)
    {
        try {
            $basketPosition = $this->findPositionByProductId($product->getId());
            $basketPosition->addToQuantity($quantity);
        } catch (CannotFindPositionException $invalidArgumentException) {
            $this->createNewItem($product, $quantity);
        }
    }

    /**
     * @param ProductId $productId
     * @return Position
     * @throws CannotFindPositionException
     */
    private function findPositionByProductId(ProductId $productId): Position
    {
        if (isset($this->positions[$productId->toNative()])) {
            return $this->positions[$productId->toNative()];
        } else {
            throw new CannotFindPositionException(sprintf("Cannot find position with product id: '%s'", $productId->toNative()));
        }
    }

    /**
     * @param Product $product
     * @param float $quantity
     */
    private function createNewItem(Product $product, float $quantity)
    {
        $this->positions[$product->getId()->toNative()] = new Position($product, $quantity);
    }

    /**
     * @return bool
     */
    public function isOpen(): bool
    {
        return $this->open;
    }

    /**
     * @param QuantityOfTheProductHasBeenChanged $event
     */
    public function handleQuantityOfTheProductHasBeenChanged(QuantityOfTheProductHasBeenChanged $event)
    {
        $this->changeQuantity($event->getProductId(), $event->getQuantity());
    }

    /**
     * @param ProductId $productId
     * @param float $quantity
     * @throws CannotFindProductException
     */
    private function changeQuantity(ProductId $productId, float $quantity)
    {
        try {
            $basketPosition = $this->findPositionByProductId($productId);
            $basketPosition->changeQuantity($quantity);
        } catch (CannotFindPositionException $cannotFindPositionException) {
            throw new CannotFindProductException('Cannot find product with id: \'%s\'.', null, $cannotFindPositionException);
        }

    }

    /**
     * @param ProductHasBeenRemovedFromTheBasket $event
     */
    public function handleProductHasBeenRemovedFromTheBasket(ProductHasBeenRemovedFromTheBasket $event)
    {
        $this->remove($event->getProductId());
    }

    /**
     * @param ProductId $productId
     * @throws CannotFindProductException
     */
    private function remove(ProductId $productId)
    {
        try {
            $this->findPositionByProductId($productId);
            $this->positions->offsetUnset($productId->toNative());
        } catch (CannotFindPositionException $cannotFindPositionException) {
            throw new CannotFindProductException('Cannot find product with id: \'%s\'.', null, $cannotFindPositionException);
        }

    }

    public function handleBasketHasBeenClosed(BasketHasBeenClosed $basketHasBeenClosed)
    {
        $this->close();
    }

    /**
     * @return void
     */
    private function close()
    {
        $this->open = false;
    }

}