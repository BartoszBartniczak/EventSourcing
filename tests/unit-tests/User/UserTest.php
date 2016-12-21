<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\User;


use Shop\ArrayObject\ArrayObject;
use Shop\EventAggregate\EventAggregate;
use Shop\User\Event\ActivationTokenHasBeenGenerated;
use Shop\User\Event\UnsuccessfulAttemptOfActivatingUserAccount;
use Shop\User\Event\UserAccountHasBeenActivated;
use Shop\User\Event\UserHasBeenLoggedIn;
use Shop\User\Event\UserHasBeenLoggedOut;
use Shop\User\Event\UserHasBeenRegistered;

class UserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \Shop\User\User::__construct
     * @covers \Shop\User\User::getEmail
     * @covers \Shop\User\User::getPasswordHash
     * @covers \Shop\User\User::getPasswordSalt
     * @covers \Shop\User\User::isActive
     * @covers \Shop\User\User::getLoginDates
     * @covers \Shop\User\User::getUnsuccessfulAttemptsOfActivatingUserAccount
     */
    public function testConstructor()
    {
        $user = new User('user@user.com', 'password', 'salt');
        $this->assertInstanceOf(EventAggregate::class, $user);

        $this->assertEquals('user@user.com', $user->getEmail());
        $this->assertEquals('password', $user->getPasswordHash());
        $this->assertEquals('salt', $user->getPasswordSalt());

        $this->assertFalse($user->isActive());
        $this->assertInstanceOf(ArrayObject::class, $user->getLoginDates());
        $this->assertEquals(0, $user->getLoginDates()->count());
        $this->assertEquals(0, $user->getUnsuccessfulAttemptsOfActivatingUserAccount());
    }

    /**
     * @covers \Shop\User\User::handleUserHasBeenRegistered
     */
    public function testHandleUserHasBeenRegistered()
    {
        $userHasBeenRegisteredEvent = $this->getMockBuilder(UserHasBeenRegistered::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getUserEmail',
                'getPasswordHash',
                'getPasswordSalt'
            ])
            ->getMock();

        $userHasBeenRegisteredEvent->method('getUserEmail')
            ->willReturn('user@user.com');

        $userHasBeenRegisteredEvent->method('getPasswordHash')
            ->willReturn('password');

        $userHasBeenRegisteredEvent->method('getPasswordSalt')
            ->willReturn('salt');
        /* @var $userHasBeenRegisteredEvent UserHasBeenRegistered */

        $user = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'findHandleMethod'
            ])
            ->getMock();
        $user->method('findHandleMethod')
            ->willReturn('handleUserHasBeenRegistered');

        /* @var $user \Shop\User\User */
        $user->apply($userHasBeenRegisteredEvent);

        $this->assertEquals('user@user.com', $user->getEmail());
        $this->assertEquals('password', $user->getPasswordHash());
        $this->assertEquals('salt', $user->getPasswordSalt());

    }

    /**
     * @covers \Shop\User\User::handleActivationTokenHasBeenGenerated
     * @covers \Shop\User\User::getActivationToken
     * @covers \Shop\User\User::changeActivationToken
     */
    public function testHandleActivationTokenHasBeenGenerated()
    {

        $activationTokenHasBeenGenerated = $this->getMockBuilder(ActivationTokenHasBeenGenerated::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getActivationToken'
            ])
            ->getMock();
        $activationTokenHasBeenGenerated->method('getActivationToken')
            ->willReturn('newToken');
        /* @var $activationTokenHasBeenGenerated ActivationTokenHasBeenGenerated * */

        $user = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'findHandleMethod'
            ])
            ->getMock();
        $user->method('findHandleMethod')
            ->willReturn('handleActivationTokenHasBeenGenerated');
        /* @var $user \Shop\User\User */

        $user->apply($activationTokenHasBeenGenerated);
        $this->assertEquals('newToken', $user->getActivationToken());
    }

    /**
     * @covers \Shop\User\User::handleUnsuccessfulAttemptOfActivatingUserAccount
     */
    public function testHandleUnsuccessfulAttemptOfActivatingUserAccount()
    {
        $unsuccessfulAttemptsOfActivatingUserAccount = $this->getMockBuilder(UnsuccessfulAttemptOfActivatingUserAccount::class)
            ->disableOriginalConstructor()
            ->getMock();

        /* @var $unsuccessfulAttemptsOfActivatingUserAccount UnsuccessfulAttemptOfActivatingUserAccount */


        $user = $this->getMockBuilder(User::class)
            ->setConstructorArgs([
                '', '', ''
            ])
            ->setMethods([
                'findHandleMethod'
            ])
            ->getMock();
        $user->method('findHandleMethod')
            ->willReturn('handleUnsuccessfulAttemptOfActivatingUserAccount');
        /* @var $user \Shop\User\User */

        $this->assertEquals(0, $user->getUnsuccessfulAttemptsOfActivatingUserAccount());
        $user->apply($unsuccessfulAttemptsOfActivatingUserAccount);
        $this->assertEquals(1, $user->getUnsuccessfulAttemptsOfActivatingUserAccount());
        $user->apply($unsuccessfulAttemptsOfActivatingUserAccount);
        $this->assertEquals(2, $user->getUnsuccessfulAttemptsOfActivatingUserAccount());
    }

    /**
     * @covers \Shop\User\User::handleUserAccountHasBeenActivated
     * @covers \Shop\User\User::activate
     */
    public function testHandleUserAccountHasBeenActivated()
    {

        $userAccountHasBeenActivated = $this->getMockBuilder(UserAccountHasBeenActivated::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $userAccountHasBeenActivated UserAccountHasBeenActivated */

        $user = $this->getMockBuilder(User::class)
            ->setConstructorArgs([
                '', '', ''
            ])
            ->setMethods([
                'findHandleMethod'
            ])
            ->getMock();
        $user->method('findHandleMethod')
            ->willReturn('handleUserAccountHasBeenActivated');
        /* @var $user \Shop\User\User */

        $user->apply($userAccountHasBeenActivated);
        $this->assertTrue($user->isActive());
    }

    /**
     * @covers \Shop\User\User::handleUserHasBeenLoggedIn
     */
    public function testHandleUserHasBeenLoggedIn()
    {
        $dateTime1 = new \DateTime();
        $dateTime2 = new \DateTime();

        $userHasBeenLoggedIn = $this->getMockBuilder(UserHasBeenLoggedIn::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getDateTime'
            ])
            ->getMock();
        $userHasBeenLoggedIn->expects($this->atLeast(2))
            ->method('getDateTime')
            ->willReturnOnConsecutiveCalls(
                $dateTime1,
                $dateTime2
            );
        /* @var $userHasBeenLoggedIn UserHasBeenLoggedIn */

        $user = $this->getMockBuilder(User::class)
            ->setConstructorArgs([
                '', '', ''
            ])
            ->setMethods([
                'findHandleMethod'
            ])
            ->getMock();
        $user->method('findHandleMethod')
            ->willReturn('handleUserHasBeenLoggedIn');
        /* @var $user \Shop\User\User */

        $user->apply($userHasBeenLoggedIn);
        $user->apply($userHasBeenLoggedIn);
        $this->assertEquals(2, $user->getLoginDates()->count());
        $this->assertSame($dateTime1, $user->getLoginDates()->offsetGet(0));
        $this->assertSame($dateTime2, $user->getLoginDates()->offsetGet(1));
    }

    /**
     * @covers \Shop\User\User::handleUserHasBeenLoggedOut
     */
    public function testHandleUserHasBeenLoggedOut()
    {
        $userHasBeenLoggedOut = $this->getMockBuilder(UserHasBeenLoggedOut::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $userHasBeenLoggedOut UserHasBeenLoggedIn */

        $user = $this->getMockBuilder(User::class)
            ->setConstructorArgs([
                '', '', ''
            ])
            ->setMethods([
                'findHandleMethod'
            ])
            ->getMock();
        $user->method('findHandleMethod')
            ->willReturn('handleUserHasBeenLoggedOut');
        /* @var $user \Shop\User\User */

        $user->apply($userHasBeenLoggedOut);
    }

}
