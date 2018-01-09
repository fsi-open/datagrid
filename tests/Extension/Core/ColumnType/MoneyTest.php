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
use FSi\Component\DataGrid\Extension\Core\ColumnType\Money;
use FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension\DefaultColumnOptionsExtension;
use FSi\Component\DataGrid\Tests\Fixtures\SimpleDataGridExtension;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    /**
     * @var DataGridFactoryInterface
     */
    private $dataGridFactory;

    public function setUp()
    {
        $this->dataGridFactory = new DataGridFactory(
            [new SimpleDataGridExtension(new DefaultColumnOptionsExtension(), new Money())]
        );
    }

    public function testCurrencyOption()
    {
        $this->assertCellValue(['currency' => 'EUR'], (object) ['price' => 10], ['price' => '10.00 EUR']);
    }

    public function testCurrencySeparatorOption()
    {
        $this->assertCellValue(['value_currency_separator' => '$'], (object) ['price' => 10], ['price' => '10.00$PLN']);
    }

    public function testDecPointOption()
    {
        $this->assertCellValue(['dec_point' => '-'], (object) ['price' => 10], ['price' => '10-00 PLN']);
    }

    public function testDecimalsOption()
    {
        $this->assertCellValue(['decimals' => 0], (object) ['price' => 10], ['price' => '10 PLN']);

        $this->assertCellValue(['decimals' => 5], (object) ['price' => 10], ['price' => '10.00000 PLN']);
    }

    public function testPrecisionOption()
    {
        $this->assertCellValue(['precision' => 2], (object) ['price' => 10.326], ['price' => '10.33 PLN']);

        $this->assertCellValue(['precision' => 2], (object) ['price' => 10.324], ['price' => '10.32 PLN']);
    }

    public function testThousandsSepOption()
    {
        $this->assertCellValue(['thousands_sep' => '.'], (object) ['price' => 10000], ['price' => '10.000.00 PLN']);
    }

    private function assertCellValue(array $options, $value, array $expectedValue): void
    {
        $options = array_merge([
            'currency' => 'PLN',
        ], $options);

        $column = $this->dataGridFactory->createColumn($this->getDataGridMock(), Money::class, 'price', $options);
        $cellView = $this->dataGridFactory->createCellView($column, $value);

        $this->assertSame($expectedValue, $cellView->getValue());
    }

    private function getDataGridMock(): DataGridInterface
    {
        return $this->createMock(DataGridInterface::class);
    }
}
