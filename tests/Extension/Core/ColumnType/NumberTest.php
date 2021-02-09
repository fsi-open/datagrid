<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Tests\Extension\Core\ColumnType;

use FSi\Component\DataGrid\Extension\Core\ColumnType\Number as NumberColumnType;
use FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension\DefaultColumnOptionsExtension;
use PHPUnit\Framework\TestCase;

class NumberTest extends TestCase
{
    /**
     * @var NumberColumnType
     */
    private $column;

    protected function setUp(): void
    {
        $column = new NumberColumnType();
        $column->setName('number');
        $column->initOptions();

        $extension = new DefaultColumnOptionsExtension();
        $extension->initOptions($column);

        $this->column = $column;
    }

    public function testPrecision(): void
    {
        $value = [
            'number' => 10.123,
        ];

        $this->column->setOption('precision', 2);
        $this->column->setOption('round_mode', NumberColumnType::ROUND_HALF_UP);

        self::assertSame(
            $this->column->filterValue($value),
            [
                'number' => 10.12,
            ]
        );
    }

    public function testRoundMode(): void
    {
        $this->column->setOption('round_mode', NumberColumnType::ROUND_HALF_UP);
        self::assertSame(
            $this->column->filterValue([
                'number' => 10.123,
            ]),
            [
                'number' => 10.12,
            ]
        );

        self::assertSame(
            $this->column->filterValue([
                'number' => 10.126,
            ]),
            [
                'number' => 10.13,
            ]
        );
    }

    public function testNumberFormat(): void
    {
        self::assertEquals(
            [
                'number' => 12345678.1,
            ],
            $this->column->filterValue([
                'number' => 12345678.1,
            ])
        );

        $this->column->setOption('format', true);

        self::assertEquals(
            [
                'number' => '12,345,678.10',
            ],
            $this->column->filterValue([
                'number' => 12345678.1,
            ])
        );

        $this->column->setOption('format_decimals', 0);

        self::assertEquals(
            [
                'number' => '12,345,678',
            ],
            $this->column->filterValue([
                'number' => 12345678.1,
            ])
        );

        $this->column->setOption('format_decimals', 2);

        self::assertEquals(
            [
                'number' => '12,345,678.10',
            ],
            $this->column->filterValue([
                'number' => 12345678.1,
            ])
        );

        $this->column->setOption('format_dec_point', ',');
        $this->column->setOption('format_thousands_sep', ' ');

        self::assertEquals(
            [
                'number' => '12 345 678,10',
            ],
            $this->column->filterValue([
                'number' => 12345678.1,
            ])
        );

        self::assertEquals(
            [
                'number' => '1 000,00',
            ],
            $this->column->filterValue([
                'number' => 1000,
            ])
        );

        $this->column->setOption('format_decimals', 0);

        self::assertEquals(
            [
                'number' => '1 000',
            ],
            $this->column->filterValue([
                'number' => 1000,
            ])
        );

        $this->column->setOption('format', false);
        self::assertEquals(
            [
                'number' => '1000',
            ],
            $this->column->filterValue([
                'number' => 1000,
            ])
        );
    }
}
