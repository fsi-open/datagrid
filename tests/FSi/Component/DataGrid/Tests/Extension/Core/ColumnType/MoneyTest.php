<?php
/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Tests\Extension\Core\ColumnType;

use FSi\Component\DataGrid\Extension\Core\ColumnType\Money;

class MoneyTest extends \PHPUnit_Framework_TestCase
{
    public function testCurrencyOption()
    {
        $value = array(
            'value' => 10,
        );

        $column = new Money();
        $column->setOption('currency', 'PLN');

        $this->assertSame(
            $column->filterValue($value),
            array(
                'value' => '10.00 PLN',
            )
        );

        $column = new Money();
        $this->setExpectedException('FSi\Component\DataGrid\Exception\DataGridColumnException');
        $column->filterValue($value);
    }

    public function testCurrencySeparatorOption()
    {
        $value = array(
            'value' => 10,
        );

        $column = new Money();
        $column->setOption('currency', 'PLN');
        $column->setOption('value_currency_separator', '$ ');

        $this->assertSame(
            $column->filterValue($value),
            array(
                'value' => '10.00$ PLN',
            )
        );
    }

    public function testCurrencyDecPointOption()
    {
        $value = array(
            'value' => 10,
        );

        $column = new Money();
        $column->setOption('currency', 'PLN');
        $column->setOption('dec_point', '-');

        $this->assertSame(
            $column->filterValue($value),
            array(
                'value' => '10-00 PLN',
            )
        );
    }

    public function testCurrencyDecimalsOption()
    {
        $value = array(
            'value' => 10,
        );

        $column = new Money();
        $column->setOption('currency', 'PLN');
        $column->setOption('decimals', 0);

        $this->assertSame(
            $column->filterValue($value),
            array(
                'value' => '10 PLN',
            )
        );

        $column = new Money();
        $column->setOption('currency', 'PLN');
        $column->setOption('decimals', 5);

        $this->assertSame(
            $column->filterValue($value),
            array(
                'value' => '10.00000 PLN',
            )
        );
    }

    public function testCurrencyPrecisionOption()
    {
        $value = array(
            'value' => 10.326
        );

        $column = new Money();
        $column->setOption('currency', 'PLN');
        $column->setOption('precision', 2);

        $this->assertSame(
            $column->filterValue($value),
            array(
                'value' => '10.33 PLN',
            )
        );

        $value = array(
            'value' => 10.324,
        );
        $this->assertSame(
            $column->filterValue($value),
            array(
                'value' => '10.32 PLN',
            )
        );
    }

    public function testCurrencyThousandsSepOption()
    {
        $value = array(
            'value' => 10000,
        );

        $column = new Money();
        $column->setOption('currency', 'PLN');
        $column->setOption('thousands_sep', '.');

        $this->assertSame(
            $column->filterValue($value),
            array(
                'value' => '10.000.00 PLN',
            )
        );
    }
}
