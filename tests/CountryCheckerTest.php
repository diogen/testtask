<?php

namespace Tests;

use App\CountryChecker;
use PHPUnit\Framework\TestCase;

class CountryCheckerTest extends TestCase
{
    public function testIsEUReturnsTrueForEUCountries(): void
    {
        $checker = new CountryChecker();
        $this->assertTrue($checker->isEU('DE'));
        $this->assertTrue($checker->isEU('NL'));
        $this->assertTrue($checker->isEU('FR'));
    }

    public function testIsEUReturnsFalseForNonEUCountries(): void
    {
        $checker = new CountryChecker();
        $this->assertFalse($checker->isEU('BR'));
        $this->assertFalse($checker->isEU('US'));
        $this->assertFalse($checker->isEU('CN'));
    }

    public function testIsEUHandlesInvalidCountryOk(): void
    {
        $checker = new CountryChecker();
        $this->assertFalse($checker->isEU('asd'));
        $this->assertFalse($checker->isEU(''));
        $this->assertFalse($checker->isEU('IO'));
    }
}