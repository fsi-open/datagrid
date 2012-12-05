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

use FSi\Component\DataGrid\DataGridRowView;

class DataGridRowViewTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateDataGridRowView()
    {
        $source = 'SOURCE';

        $columnView = $this->getMock('FSi\Component\DataGrid\Column\ColumnViewInterface');

        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');
        $column->expects($this->atLeastOnce())
                ->method('createView')
                ->with($source, 0)
                ->will($this->returnValue($columnView));

        $columns = array(
            'foo' =>$column
        );

        $gridRow = new DataGridRowView($columns, $source, 0);
        $this->assertSame($gridRow->current(), $columnView);
    }
}