<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Tests;

use FSi\Component\DataGrid\DataGridRowView;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\Column\CellViewInterface;
use FSi\Component\DataGrid\DataGridViewInterface;
use PHPUnit\Framework\TestCase;

class DataGridRowViewTest extends TestCase
{
    public function testCreateDataGridRowView()
    {
        $source = 'SOURCE';

        $dataGridView = $this->createMock(DataGridViewInterface::class);

        $cellView = $this->createMock(CellViewInterface::class);

        $column = $this->createMock(ColumnTypeInterface::class);
        $column->expects($this->atLeastOnce())
            ->method('createCellView')
            ->with($source, 0)
            ->will($this->returnValue($cellView));

        $columns = [
            'foo' =>$column
        ];

        $gridRow = new DataGridRowView($dataGridView, $columns, $source, 0);
        $this->assertSame($gridRow->current(), $cellView);

        $this->assertSame($gridRow->getSource(), $source);
    }
}
