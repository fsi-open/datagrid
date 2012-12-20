<?php
/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Tests;

use FSi\Component\DataGrid\DataGridView;

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
        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');
        $column->expects($this->any())
            ->method('createHeaderView')
            ->will($this->returnValue('ColumnHeaderView'));

        $column->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('foo'));

        $columnHeader = $this->getMock('FSi\Component\DataGrid\Column\HeaderViewInterface');
        $columnHeader->expects($this->any())
                ->method('getName')
                ->will($this->returnValue('foo'));

        $this->rowset = $this->getMock('FSi\Component\DataGrid\Data\DataRowsetInterface');
        $this->gridView = new DataGridView('test-grid-view', array($column) , $this->rowset);

        $this->assertSame('test-grid-view', $this->gridView->getName());

        $this->assertTrue($this->gridView->hasColumn('foo'));
        $this->assertSame(1, count($this->gridView->getColumns()));
        $this->assertSame($this->gridView->getColumn('foo'), 'ColumnHeaderView');
        $this->gridView->removeColumn('foo');
        $this->assertFalse($this->gridView->hasColumn('foo'));

        $this->gridView->addColumn($columnHeader);
        $this->assertTrue($this->gridView->hasColumn('foo'));

        $this->gridView->clearColumns();
        $this->assertFalse($this->gridView->hasColumn('foo'));
    }
}