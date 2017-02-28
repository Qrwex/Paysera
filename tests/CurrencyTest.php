<?php

namespace Tests;

use App\Currencies\Currency;
use PHPUnit\Framework\TestCase;

/**
 * @covers App\Currencies\Currency
 */
class CurrencyTest extends TestCase
{
    /** @test */
    public function testConvert()
    {
        $this->assertEquals(Currency::convert('EUR', 'JPY', 2.5), 323.825);
        $this->assertEquals(Currency::convert('JPY', 'EUR', 997.381), 7.7);
        $this->assertEquals(Currency::convert('USD', 'EUR', 7.269955495), 6.32335);
        $this->assertEquals(Currency::convert('USD', 'EUR', 517.365), 450);
        $this->assertEquals(Currency::convert('JPY', 'EUR', 589), 4.5472091407395974);
        $this->assertEquals(Currency::convert('EUR', 'JPY', 214), 27719.420000000002);
    }
}
