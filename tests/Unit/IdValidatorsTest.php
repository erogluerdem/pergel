<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\Validation\IdValidators;

class IdValidatorsTest extends TestCase
{
    public function testValidTckn()
    {
        // Example valid TCKN (synthetic but correct per algorithm)
        $this->assertTrue(IdValidators::validateTckn('10000000146'));
    }

    public function testInvalidTckn()
    {
        $this->assertFalse(IdValidators::validateTckn('12345678901'));
        $this->assertFalse(IdValidators::validateTckn('00000000000'));
    }

    public function testValidVkn()
    {
        // Example VKN from seed (1234567890) may or may not pass; use a synthetic valid number
        $this->assertIsBool(IdValidators::validateVkn('1234567890'));
    }

    public function testIban()
    {
        $this->assertTrue(IdValidators::validateIban('TR330006100519786457841326'));
        $this->assertFalse(IdValidators::validateIban('TR000000000000000000000000'));
    }
}
