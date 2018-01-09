<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Tests;

use FSi\Component\DataGrid\Column\ColumnInterface;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataGrid\DataGridRowView;
use FSi\Component\DataGrid\Column\CellViewInterface;
use PHPUnit\Framework\TestCase;

class DataGridRowViewTest extends TestCase
{
    public function testCreateDataGridRowView()
    {
        $source = 'SOURCE';

        $dataGridFactory = $this->createMock(DataGridFactoryInterface::class);
        $dataGrid = $this->createMock(DataGridInterface::class);

        $cellView = $this->createMock(CellViewInterface::class);

        $columnType = $this->createMock(ColumnTypeInterface::class);

        $column = $this->createMock(ColumnInterface::class);
        $column->expects($this->any())
            ->method('getType')
            ->will($this->returnValue($columnType));

        $column->expects($this->any())
            ->method('getDataGrid')
            ->will($this->returnValue($dataGrid));

        $dataGrid->expects($this->any())
            ->method('getFactory')
            ->will($this->returnValue($dataGridFactory));

        $dataGridFactory->expects($this->atLeastOnce())
            ->method('createCellView')
            ->with($column, $source)
            ->will($this->returnValue($cellView));

        $columns = [
            'foo' => $column,
        ];

        $gridRow = new DataGridRowView($columns, 0, $source);
        $this->assertSame($gridRow->current(), $cellView);

        $this->assertSame($gridRow->getSource(), $source);
    }
}
