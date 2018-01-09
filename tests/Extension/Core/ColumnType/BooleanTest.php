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
use FSi\Component\DataGrid\Extension\Core\ColumnType\Boolean;
use FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension\DefaultColumnOptionsExtension;
use FSi\Component\DataGrid\Tests\Fixtures\SimpleDataGridExtension;
use PHPUnit\Framework\TestCase;

class BooleanTest extends TestCase
{
    /**
     * @var DataGridFactoryInterface
     */
    private $dataGridFactory;

    public function setUp()
    {
        $this->dataGridFactory = new DataGridFactory(
            [new SimpleDataGridExtension(new DefaultColumnOptionsExtension(), new Boolean())]
        );
    }

    public function testValues()
    {
        $column = $this->dataGridFactory->createColumn($this->getDataGridMock(), Boolean::class, 'available', [
            'true_value' => 'true',
            'false_value'=> 'false',
        ]);

        $trueCellView = $this->dataGridFactory->createCellView($column, (object) ['available' => true]);
        $falseCellView = $this->dataGridFactory->createCellView($column, (object) ['available' => false]);

        $this->assertSame('true', $trueCellView->getValue());
        $this->assertSame('false', $falseCellView->getValue());
    }

    public function testAllTrueValues()
    {
        $column = $this->dataGridFactory->createColumn($this->getDataGridMock(), Boolean::class, 'available', [
            'true_value' => 'true',
            'field_mapping' => ['available', 'active'],
        ]);

        $cellView = $this->dataGridFactory->createCellView($column, (object) [
            'available' => true,
            'active' => true
        ]);

        $this->assertSame('true', $cellView->getValue());
    }

    public function testMixedValues()
    {
        $column = $this->dataGridFactory->createColumn($this->getDataGridMock(), Boolean::class, 'available', [
            'true_value' => 'true',
            'false_value'=> 'false',
            'field_mapping' => ['available', 'active', 'createdAt'],
        ]);

        $cellView = $this->dataGridFactory->createCellView($column, (object) [
            'available' => true,
            'active' => 1,
            'createdAt' => new \DateTime(),
        ]);

        $this->assertSame('true', $cellView->getValue());
    }

    public function testAllFalseValues()
    {
        $column = $this->dataGridFactory->createColumn($this->getDataGridMock(), Boolean::class, 'available', [
            'true_value' => 'true',
            'false_value' => 'false',
            'field_mapping' => ['available', 'active'],
        ]);

        $cellView = $this->dataGridFactory->createCellView($column, (object) [
            'available' => false,
            'active' => false,
        ]);

        $this->assertSame('false', $cellView->getValue());
    }

    public function testMixedValuesAndFalse()
    {
        $column = $this->dataGridFactory->createColumn($this->getDataGridMock(), Boolean::class, 'available', [
            'true_value' => 'true',
            'false_value' => 'false',
            'field_mapping' => ['available', 'active', 'createdAt', 'disabled'],
        ]);

        $cellView = $this->dataGridFactory->createCellView($column, (object) [
            'available' => true,
            'active' => 1,
            'createdAt' => new \DateTime(),
            'disabled' => false,
        ]);

        $this->assertSame('false', $cellView->getValue());
    }

    public function testMixedValuesAndNull()
    {
        $column = $this->dataGridFactory->createColumn($this->getDataGridMock(), Boolean::class, 'available', [
            'true_value' => 'true',
            'false_value'=> 'false',
            'field_mapping' => ['available', 'active', 'createdAt', 'disabled'],
        ]);

        $cellView = $this->dataGridFactory->createCellView($column, (object) [
            'available' => true,
            'active' => 1,
            'createdAt' => new \DateTime(),
            'disabled' => null,
        ]);

        $this->assertSame('true', $cellView->getValue());
    }

    public function testAllNulls()
    {
        $column = $this->dataGridFactory->createColumn($this->getDataGridMock(), Boolean::class, 'available', [
            'true_value' => 'true',
            'false_value'=> 'false',
            'field_mapping' => ['available', 'active'],
        ]);

        $cellView = $this->dataGridFactory->createCellView($column, (object) [
            'available' => null,
            'active' => null,
        ]);

        $this->assertSame('', $cellView->getValue());
    }

    private function getDataGridMock(): DataGridInterface
    {
        return $this->createMock(DataGridInterface::class);
    }
}
