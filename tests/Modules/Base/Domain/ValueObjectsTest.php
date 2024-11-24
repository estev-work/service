<?php

declare(strict_types=1);

namespace Tests\Modules\Base\Domain;

use PHPUnit\Framework\TestCase;
use Project\Base\Domain\ValueObjects\DateValue;
use Project\Base\Domain\ValueObjects\Identifier;

class ValueObjectsTest extends TestCase
{

    public function testIdValue(): void
    {
        $id1 = Identifier::create();
        $id2 = Identifier::create();
        $this->assertNotSame($id1->toString(), $id2->toString());

        $sameId = Identifier::fromString($id1->toString());
        $this->assertTrue($id1->equals($sameId));

        $this->expectException(\Exception::class);
        Identifier::fromString('invalid-uuid');
    }

    public function testDateValue(): void
    {
        $date = DateValue::fromString('2023-11-22T10:00:00+00:00');
        $this->assertSame('2023-11-22T10:00:00+00:00', $date->format(DATE_ATOM));

        $sameDate = DateValue::fromString('2023-11-22T10:00:00+00:00');
        $this->assertTrue($date->equals($sameDate->getDateImmutable()));

        $laterDate = DateValue::fromString('2023-11-23T10:00:00+00:00');
        $this->assertTrue($date->isBefore($laterDate->getDateImmutable()));

        $this->expectException(\InvalidArgumentException::class);
        DateValue::fromString('invalid-date');
    }
}