<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Tests\Extension\Core\ColumnType;

use FSi\Component\DataGrid\DataGridFactory;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataGrid\Extension\Core\ColumnType\Number;
use FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension\DefaultColumnOptionsExtension;
use FSi\Component\DataGrid\Tests\Fixtures\SimpleDataGridExtension;
use PHPUnit\Framework\TestCase;

class NumberTest extends TestCase
{
    /**
     * @var DataGridFactoryInterface
     */
    private $dataGridFactory;

    public function setUp()
    {
        $this->dataGridFactory = new DataGridFactory(
            [new SimpleDataGridExtension(new DefaultColumnOptionsExtension(), new Number())]
        );
    }

    public function testPrecision()
    {
        $this->assertCellValue(
            ['precision' => 2, 'round_mode' => Number::ROUND_HALF_UP],
            (object) ['number' => 10.123],
            ['number' => 10.12]
        );
    }

    public function testRoundMode()
    {
        $this->assertCellValue(
            ['round_mode' => Number::ROUND_HALF_UP],
            (object) ['number' => 10.126],
            ['number' => 10.13]
        );
    }

    public function testNumberFormat()
    {
        $this->assertCellValue(
            [],
            (object) ['number' => 12345678.1],
            ['number' => 12345678.1]
        );

        $this->assertCellValue(
            ['format' => true],
            (object) ['number' => 12345678.1],
            ['number' => '12,345,678.10']
        );

        $this->assertCellValue(
            ['format' => true, 'format_decimals' => 0],
            (object) ['number' => 12345678.1],
            ['number' => '12,345,678']
        );

        $this->assertCellValue(
            ['format' => true, 'format_decimals' => 2],
            (object) ['number' => 12345678.1],
            ['number' => '12,345,678.10']
        );

        $this->assertCellValue(
            ['format' => true, 'format_decimals' => 2, 'format_dec_point' => ',', 'format_thousands_sep' => ' '],
            (object) ['number' => 12345678.1],
            ['number' => '12 345 678,10']
        );

        $this->assertCellValue(
            ['format' => true, 'format_decimals' => 2, 'format_dec_point' => ',', 'format_thousands_sep' => ' '],
            (object) ['number' => 1000],
            ['number' => '1 000,00']
        );

        $this->assertCellValue(
            ['format' => true, 'format_decimals' => 0, 'format_dec_point' => ',', 'format_thousands_sep' => ' '],
            (object) ['number' => 1000],
            ['number' => '1 000']
        );

        $this->assertCellValue(
            ['format' => false, 'format_decimals' => 2, 'format_dec_point' => ',', 'format_thousands_sep' => ' '],
            (object) ['number' => 1000],
            ['number' => 1000]
        );
    }

    private function assertCellValue(array $options, $value, array $expectedValue): void
    {
        $column = $this->dataGridFactory->createColumn($this->getDataGridMock(), Number::class, 'number', $options);
        $cellView = $this->dataGridFactory->createCellView($column, 0, $value);

        $this->assertSame($expectedValue, $cellView->getValue());
    }

    private function getDataGridMock(): DataGridInterface
    {
        return $this->createMock(DataGridInterface::class);
    }
}
