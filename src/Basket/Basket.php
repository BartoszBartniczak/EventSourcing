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
use Shop\EventAggregate\EventAggregate;
use Shop\Product\Product;
use Shop\UUID\UUID;

class Basket extends EventAggregate
{

    /**
     * @var Id
     */
    private $id;

    /**
     * @var array
     */
    private $products;

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
        $this->products = [];
        $this->ownerEmail = $ownerEmail;
        $this->open = true;
    }

    /**
     * @return array
     */
    public function getPositions(): array
    {
        return $this->products;
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
        $basketPosition = $this->findPositionByProductId($product->getId());
        if (!$basketPosition) {
            $this->createNewItem($product, $quantity);
        } else {
            $this->products[$product->getId()->toNative()]['quantity'] += $quantity;
        }
    }

    private function findPositionByProductId(UUID $uuid)
    {
        if (isset($this->products[$uuid->toNative()])) {
            return $this->products[$uuid->toNative()];
        } else {
            return null;
        }
    }

    /**
     * @param Product $product
     * @param float $quantity
     */
    private function createNewItem(Product $product, float $quantity)
    {
        $this->products[$product->getId()->toNative()] = [
            'product' => $product,
            'quantity' => $quantity
        ];
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
     * @param UUID $productId
     * @param float $quantity
     * @throws CannotFindProductException
     */
    private function changeQuantity(UUID $productId, float $quantity)
    {
        if (!isset($this->products[$productId->toNative()])) {
            throw new CannotFindProductException();
        }

        $this->products[$productId->toNative()]['quantity'] = $quantity;
    }

    /**
     * @param ProductHasBeenRemovedFromTheBasket $event
     */
    public function handleProductHasBeenRemovedFromTheBasket(ProductHasBeenRemovedFromTheBasket $event)
    {
        $this->remove($event->getProductUuid());
    }

    /**
     * @param UUID $productId
     * @throws CannotFindProductException
     */
    private function remove(UUID $productId)
    {
        if (!isset($this->products[$productId->toNative()])) {
            throw new CannotFindProductException();
        }

        unset($this->products[$productId->toNative()]);
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