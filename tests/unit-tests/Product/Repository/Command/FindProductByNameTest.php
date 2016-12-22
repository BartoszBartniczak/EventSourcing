<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Product\Repository\Command;


use Shop\Product\Repository\Repository;
use Shop\User\User;

class FindProductByNameTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \Shop\Product\Repository\Command\FindProductByName::__construct
     * @covers \Shop\Product\Repository\Command\FindProductByName::getUser
     * @covers \Shop\Product\Repository\Command\FindProductByName::getProductName
     * @covers \Shop\Product\Repository\Command\FindProductByName::getProductRepository
     */
    public function testGetters()
    {

        $user = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $user User */

        $repository = $this->getMockBuilder(Repository::class)
            ->getMockForAbstractClass();
        /* @var $repository Repository */

        $findProductByName = new FindProductByName($user, 'ProductName', $repository);
        $this->assertSame($user, $findProductByName->getUser());
        $this->assertSame('ProductName', $findProductByName->getProductName());
        $this->assertSame($repository, $findProductByName->getProductRepository());
    }

}
