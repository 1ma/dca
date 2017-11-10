<?php

declare(strict_types=1);

namespace UMA\Tests\DCA\Bitstamp;

use PHPUnit\Framework\TestCase;
use UMA\DCA\Bitstamp\Auth;
use UMA\DCA\Bitstamp\NonceGenerator;

class AuthTest extends TestCase
{
    public function testAuthComputesCorrectCredentials()
    {
        $auth = new Auth('123', 'abc', 'xyz');

        /** @var NonceGenerator|\PHPUnit_Framework_MockObject_MockObject $nonceGenerator */
        $nonceGenerator = $this->getMockBuilder(NonceGenerator::class)
            ->getMock();

        $nonceGenerator->expects($this->once())
            ->method('next')
            ->will($this->returnValue(1500000000000000));

        $this->replaceInstanceProperty($auth, 'nonceGenerator', $nonceGenerator);

        $expected = [
            'key' => '123',
            'signature' => 'A3011914998470DA8C1B14F47A237A1C8428F93CBC6B98A76C84561FC929F024',
            'nonce' => '1500000000000000'
        ];

        self::assertSame($expected, $auth->getCredentials());
    }

    /**
     * @param object $instance
     * @param string $propertyName
     * @param mixed  $mysteryMeat
     */
    private function replaceInstanceProperty($instance, $propertyName, $mysteryMeat)
    {
        $property = (new \ReflectionClass($instance))
            ->getProperty($propertyName);

        $property->setAccessible(true);
        $property->setValue($instance, $mysteryMeat);
    }
}
