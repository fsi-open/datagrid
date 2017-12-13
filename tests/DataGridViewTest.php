<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Tests;

use FSi\Component\DataGrid\DataGridView;
use FSi\Component\DataGrid\Data\DataRowsetInterface;
use FSi\Component\DataGrid\Column\HeaderViewInterface;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;

class DataGridViewTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DataRowsetInterface
     */
    private $rowset;

    /**
     * @var DataGridView
     */
    private $gridView;

    public function testAddHasGetRemoveColumn()
    {
        $self = $this;

        $column = $this->createMock(ColumnTypeInterface::class);
        $column->expects($this->any())
            ->method('createHeaderView')
            ->will($this->returnCallback(function() use ($self) {
                $headerView = $self->createMock(HeaderViewInterface::class);
                $headerView->expects($self->any())
                    ->method('getName')
                    ->will($self->returnValue('ColumnHeaderView'));

                $headerView->expects($self->any())
                    ->method('getType')
                    ->will($self->returnValue('foo-type'));

                return $headerView;
            }));

        $column->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('foo'));

        $columnHeader = $this->createMock(HeaderViewInterface::class);
        $columnHeader->expects($this->any())
                ->method('getName')
                ->will($this->returnValue('foo'));

        $columnHeader->expects($this->any())
            ->method('getType')
            ->will($this->returnValue('foo-type'));

        $columnHeader->expects($this->any())
            ->method('setDataGridView');

        $this->rowset = $this->createMock(DataRowsetInterface::class);
        $this->gridView = new DataGridView('test-grid-view', [$column], $this->rowset);

        $this->assertSame('test-grid-view', $this->gridView->getName());

        $this->assertTrue($this->gridView->hasColumn('foo'));
        $this->assertTrue($this->gridView->hasColumnType('foo-type'));
        $this->assertCount(1, $this->gridView->getColumns());
        $this->assertSame($this->gridView->getColumn('foo')->getName(), 'ColumnHeaderView');
        $this->gridView->removeColumn('foo');
        $this->assertFalse($this->gridView->hasColumn('foo'));

        $this->gridView->addColumn($columnHeader);
        $this->assertTrue($this->gridView->hasColumn('foo'));

        $this->gridView->clearColumns();
        $this->assertFalse($this->gridView->hasColumn('foo'));
    }
}
