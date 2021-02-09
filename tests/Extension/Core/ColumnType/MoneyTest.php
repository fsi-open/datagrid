<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Tests\Extension\Core\ColumnType;

use FSi\Component\DataGrid\Extension\Core\ColumnType\Money;
use FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension\DefaultColumnOptionsExtension;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    /**
     * @var Money
     */
    private $column;

    protected function setUp(): void
    {
        $column = new Money();
        $column->setName('money');
        $column->initOptions();

        $extension = new DefaultColumnOptionsExtension();
        $extension->initOptions($column);

        $this->column = $column;
    }

    public function testCurrencyOption(): void
    {
        $value = [
            'value' => 10,
        ];

        $this->column->setOption('currency', 'PLN');

        self::assertSame(
            $this->column->filterValue($value),
            [
                'value' => '10.00 PLN',
            ]
        );
    }

    public function testCurrencySeparatorOption(): void
    {
        $value = [
            'value' => 10,
        ];

        $this->column->setOption('currency', 'PLN');
        $this->column->setOption('value_currency_separator', '$ ');

        self::assertSame(
            $this->column->filterValue($value),
            [
                'value' => '10.00$ PLN',
            ]
        );
    }

    public function testCurrencyDecPointOption(): void
    {
        $value = [
            'value' => 10,
        ];

        $this->column->setOption('currency', 'PLN');
        $this->column->setOption('dec_point', '-');

        self::assertSame(
            $this->column->filterValue($value),
            [
                'value' => '10-00 PLN',
            ]
        );
    }

    public function testCurrencyDecimalsOption(): void
    {
        $value = [
            'value' => 10,
        ];

        $this->column->setOption('currency', 'PLN');
        $this->column->setOption('decimals', 0);

        self::assertSame(
            $this->column->filterValue($value),
            [
                'value' => '10 PLN',
            ]
        );

        $this->column->setOption('currency', 'PLN');
        $this->column->setOption('decimals', 5);

        self::assertSame(
            $this->column->filterValue($value),
            [
                'value' => '10.00000 PLN',
            ]
        );
    }

    public function testCurrencyPrecisionOption(): void
    {
        $value = [
            'value' => 10.326
        ];

        $this->column->setOption('currency', 'PLN');
        $this->column->setOption('precision', 2);

        self::assertSame(
            $this->column->filterValue($value),
            [
                'value' => '10.33 PLN',
            ]
        );

        $value = [
            'value' => 10.324,
        ];
        self::assertSame(
            $this->column->filterValue($value),
            [
                'value' => '10.32 PLN',
            ]
        );
    }

    public function testCurrencyThousandsSepOption(): void
    {
        $value = [
            'value' => 10000,
        ];

        $this->column->setOption('currency', 'PLN');
        $this->column->setOption('thousands_sep', '.');

        self::assertSame(
            $this->column->filterValue($value),
            [
                'value' => '10.000.00 PLN',
            ]
        );
    }
}
