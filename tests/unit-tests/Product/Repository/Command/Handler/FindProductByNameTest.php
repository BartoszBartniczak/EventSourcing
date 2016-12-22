<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Product\Repository\Command\Handler;

use Shop\Product\Product;
use Shop\Product\Repository\Command\FindProductByName as FindProductByNameCommand;
use Shop\Product\Repository\Repository;
use Shop\User\User;
use Shop\UUID\Generator;

class FindProductByNameTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \Shop\Product\Repository\Command\Handler\FindProductByName::handle
     */
    public function testHandleFindsProduct()
    {

        $generator = $this->getMockBuilder(Generator::class)
            ->getMock();
        /* @var $generator Generator */

        $user = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $user User */

        $productMock = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $productMock Product */

        $productRepository = $this->getMockBuilder(Repository::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'findByName'
            ])
            ->getMockForAbstractClass();
        $productRepository->method('findByName')
            ->with('productName')
            ->willReturn($productMock);
        /* @var $productRepository Repository */

        $findProductByNameCommand = new FindProductByNameCommand($user, 'productName', $productRepository);

        $findProductByName = new FindProductByName($generator);
        $productMock = $findProductByName->handle($findProductByNameCommand);
        $this->assertInstanceOf(Product::class, $productMock);
        $this->assertSame($productMock, $productMock);
        $this->assertEquals(0, $findProductByName->getAdditionalEvents()->count());
        $this->assertEquals(0, $findProductByName->getNextCommands()->count());

    }

}
