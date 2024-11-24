<?php

declare(strict_types=1);

namespace Tests\Modules\Base\Infrastructure;

use PHPUnit\Framework\TestCase;
use Project\Base\Domain\Common\Uuid\UuidInterface;
use Project\Base\Infrastructure\Common\Uuid\BaseUuidFactory;
use Project\Base\Infrastructure\Exceptions\InfrastructureLayerException;
use Random\RandomException;
use ReflectionException;

class UuidFactoryTest extends TestCase
{
    private BaseUuidFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new BaseUuidFactory();
    }

    public function testCreateV3(): void
    {
        $namespace = $this->factory->createV4();
        $name = 'test';
        $uuid = $this->factory->createV3($namespace, $name);

        $this->assertInstanceOf(UuidInterface::class, $uuid);
        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-3[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
            $uuid->toString()
        );
        $this->assertTrue(self::isValidUuid($uuid->toString()));
    }

    public function testCreateV4(): void
    {
        $uuid = $this->factory->createV4();

        $this->assertInstanceOf(UuidInterface::class, $uuid);
        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
            $uuid->toString()
        );
        $this->assertTrue(self::isValidUuid($uuid->toString()));
    }

    public function testCreateV5(): void
    {
        $namespace = $this->factory->createV4();
        $name = 'test';
        $uuid = $this->factory->createV5($namespace, $name);

        $this->assertInstanceOf(UuidInterface::class, $uuid);
        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-5[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
            $uuid->toString()
        );
        $this->assertTrue(self::isValidUuid($uuid->toString()));
    }

    public function testCreateV7(): void
    {
        $uuid = $this->factory->createV7();

        $this->assertInstanceOf(UuidInterface::class, $uuid);
        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-7[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
            $uuid->toString()
        );
        $this->assertTrue(self::isValidUuid($uuid->toString()));
    }

    /**
     * @dataProvider uuidProvider
     */
    public function testFromString(string $uuidString): void
    {
        $uuid = $this->factory->fromString($uuidString);

        $this->assertInstanceOf(UuidInterface::class, $uuid);
        $this->assertSame($uuidString, $uuid->toString());
        $this->assertTrue(self::isValidUuid($uuid->toString()));
    }

    public function testCreateV4Failure(): void
    {
        $mockFactory = $this->getMockBuilder(BaseUuidFactory::class)
            ->onlyMethods(['createV4'])
            ->getMock();

        $mockFactory->method('createV4')->willThrowException(
            new InfrastructureLayerException('Random bytes generation failed')
        );

        $this->expectException(InfrastructureLayerException::class);
        $this->expectExceptionMessage('Random bytes generation failed');

        $mockFactory->createV4();
    }

    /**
     * @throws ReflectionException
     * @throws RandomException
     */
    public function testInvalidVersionInFormatUuid(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid UUID version: 8');

        $reflectionMethod = new \ReflectionMethod(BaseUuidFactory::class, 'formatUuid');

        $data = random_bytes(16);
        $reflectionMethod->invokeArgs(null, [$data, 8]);
    }

    public static function uuidProvider(): array
    {
        return [
            ['3b12f1df-5232-3c75-a3dd-3b10c4d1b2b6'], // V3
            ['f47ac10b-58cc-4372-a567-0e02b2c3d479'], // V4
            ['21f7f8de-8051-5b89-8680-0195ef798b6a'], // V5
            ['017f22e2-79b0-7cc2-98c4-dc0c0c07398f'], // V7
        ];
    }

    private static function isValidUuid(string $uuid): bool
    {
        $pattern = '/^[0-9a-f]{8}-[0-9a-f]{4}-[1-578][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i';
        return preg_match($pattern, $uuid) === 1;
    }
}
