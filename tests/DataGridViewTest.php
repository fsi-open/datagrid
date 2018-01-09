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
use FSi\Component\DataGrid\DataGridView;
use FSi\Component\DataGrid\Data\DataRowsetInterface;
use FSi\Component\DataGrid\Column\HeaderViewInterface;
use PHPUnit\Framework\TestCase;

class DataGridViewTest extends TestCase
{
    public function testBuildingView()
    {
        $dataGridFactory = $this->createMock(DataGridFactoryInterface::class);
        $dataGrid = $this->createMock(DataGridInterface::class);
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

        $column->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('foo'));

        $dataGridFactory->expects($this->any())
            ->method('createHeaderView')
            ->will($this->returnCallback(function() {
                $headerView = $this->createMock(HeaderViewInterface::class);
                $headerView->expects($this->any())
                    ->method('getName')
                    ->will($this->returnValue('ColumnHeaderView'));

                $headerView->expects($this->any())
                    ->method('getType')
                    ->will($this->returnValue('foo-type'));

                return $headerView;
            }));

        $columnHeader = $this->createMock(HeaderViewInterface::class);
        $columnHeader->expects($this->any())
                ->method('getName')
                ->will($this->returnValue('foo'));

        $columnHeader->expects($this->any())
            ->method('getType')
            ->will($this->returnValue('foo-type'));

        $rowset = $this->createMock(DataRowsetInterface::class);
        $gridView = new DataGridView('test-grid-view', [$column], $rowset);

        $this->assertSame('test-grid-view', $gridView->getName());
        $this->assertTrue(isset($gridView->getHeaders()['foo']));
        $this->assertCount(1, $gridView->getHeaders());
        $this->assertSame($gridView->getHeaders()['foo']->getName(), 'ColumnHeaderView');
    }
}
