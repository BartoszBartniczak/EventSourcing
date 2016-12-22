<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Product\Repository\Event;


use Shop\EventTestCase;

class ProductHasNotBeenFoundTest extends EventTestCase
{

    /**
     * @covers \Shop\Product\Repository\Event\ProductHasNotBeenFound::__construct
     * @covers \Shop\Product\Repository\Event\ProductHasNotBeenFound::getUserEmail
     * @covers \Shop\Product\Repository\Event\ProductHasNotBeenFound::getProductName
     */
    public function testGetters()
    {

        $productHasNotBeenFound = new ProductHasNotBeenFound(
            $this->generateEventId(),
            $this->generateDateTime(),
            'Batmobile',
            'user@email.com'
        );
        $this->assertInstanceOf(Event::class, $productHasNotBeenFound);
        $this->assertSameEventIdAsGenerated($productHasNotBeenFound);
        $this->assertSameDateTimeAsGenerated($productHasNotBeenFound);
        $this->assertSame('user@email.com', $productHasNotBeenFound->getUserEmail());
        $this->assertSame('Batmobile', $productHasNotBeenFound->getProductName());
    }

}
