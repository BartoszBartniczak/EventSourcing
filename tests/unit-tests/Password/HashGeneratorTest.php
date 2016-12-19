<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Shop\Password;


use Shop\User\User;

class HashGeneratorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \Shop\Password\HashGenerator::hash
     */
    public function testHash()
    {
        $hashGenerator = new HashGenerator();
        $this->assertNotEquals($hashGenerator->hash('password'), $hashGenerator->hash('password'));
        $this->assertNotEmpty($hashGenerator->hash('password', 'd6a4d4f7-e446-4a49-b5fe-b5d9df85811a'));
        $this->assertEquals($hashGenerator->hash('password', 'd6a4d4f7-e446-4a49-b5fe-b5d9df85811a'), $hashGenerator->hash('password', 'd6a4d4f7-e446-4a49-b5fe-b5d9df85811a'));
    }

    /**
     * @covers \Shop\Password\HashGenerator::verifyUserPassword
     */
    public function testVerifyUserPassword()
    {
        $hashGenerator = new HashGenerator();
        $hash = $hashGenerator->hash('password', 'd6a4d4f7-e446-4a49-b5fe-b5d9df85811a');

        $user = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->setMethods(['getPasswordHash', 'getPasswordSalt'])
            ->getMock();
        $user->method('getPasswordHash')
            ->willReturn($hash);
        $user->method('getPasswordSalt')
            ->willReturn('d6a4d4f7-e446-4a49-b5fe-b5d9df85811a');
        /* @var $user User */
        $this->assertFalse($hashGenerator->verifyUserPassword('wrong_password', $user));
        $this->assertTrue($hashGenerator->verifyUserPassword('password', $user));
    }

    /**
     * @covers \Shop\Password\HashGenerator::needsRehash
     */
    public function testNeedsRehash()
    {
        $hashGenerator = new HashGenerator();
        $hash = $hashGenerator->hash('password', 'd6a4d4f7-e446-4a49-b5fe-b5d9df85811a', PASSWORD_BCRYPT, 10);
        $this->assertFalse($hashGenerator->needsRehash($hash));
        $this->assertTrue($hashGenerator->needsRehash(substr($hash, 1, 10), PASSWORD_BCRYPT, 10));
    }

    /**
     * @covers \Shop\Password\HashGenerator::hashInfo
     */
    public function testHashInfo()
    {
        $hashGenerator = new HashGenerator();
        $hash = '$2y$10$Ai5.PtwQDBnh2xx0fVpIcO343ytdSNDnhDKDVSx09C7jgH3Xgz2qK';

        $this->assertEquals(
            new HashInfo(1, 'bcrypt', 10), $hashGenerator->hashInfo($hash)
        );

    }

}
