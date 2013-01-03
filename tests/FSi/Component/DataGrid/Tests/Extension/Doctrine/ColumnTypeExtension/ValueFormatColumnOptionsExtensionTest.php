<?php
/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Tests\Extension\Doctrine\ColumntypeExtension;

use FSi\Component\DataGrid\Extension\Doctrine\ColumnTypeExtension\ValueFormatColumnOptionsExtension;

class ValueFormatColumnOptionsExtensionTest extends \PHPUnit_Framework_TestCase
{

    public function testBuildCellViewWithoutFormatAndGlue()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->getMock('FSi\Component\DataGrid\Column\CellViewInterface');
        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue(array(
                0 => array(
                    'id' => 1,
                    'name' => 'Foo'
                )
            )));

        $column->expects($this->at(0))
            ->method('getOption')
            ->with('glue')
            ->will($this->returnValue(null));

        $column->expects($this->at(1))
            ->method('getOption')
            ->with('format')
            ->will($this->returnValue(null));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('');

        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithFormatAndGlue()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->getMock('FSi\Component\DataGrid\Column\CellViewInterface');
        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $view->expects($this->at(0))
        ->method('getValue')
        ->will($this->returnValue(array(
            0 => array(
                'id' => 1,
                'name' => 'Foo'
            )
        )));

        $column->expects($this->at(0))
            ->method('getOption')
            ->with('glue')
            ->will($this->returnValue('<br/>'));

        $column->expects($this->at(1))
            ->method('getOption')
            ->with('format')
            ->will($this->returnValue('(%s)'));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('(1)<br/>(Foo)');

        $extension->buildCellView($column, $view);
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testBuildCellViewWithFormatAndGlueWithToManyPlaceholders()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->getMock('FSi\Component\DataGrid\Column\CellViewInterface');
        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $view->expects($this->at(0))
        ->method('getValue')
            ->will($this->returnValue(array(
                0 => array(
                    'id' => 1,
                    'name' => 'Foo'
                )
            )));

        $column->expects($this->at(0))
            ->method('getOption')
            ->with('glue')
            ->will($this->returnValue('<br/>'));

        $column->expects($this->at(1))
            ->method('getOption')
            ->with('format')
            ->will($this->returnValue('(%s) (%s)'));

        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithFormatGlueAndGlueMultiple()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->getMock('FSi\Component\DataGrid\Column\CellViewInterface');
        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue(array(
                0 => array(
                    'id' => 1,
                    'name' => 'Foo'
                ),
                1 => array(
                    'id' => 2,
                    'name' => 'Bar'
                )
            )));

        $column->expects($this->at(0))
            ->method('getOption')
            ->with('glue')
            ->will($this->returnValue(' '));

        $column->expects($this->at(1))
            ->method('getOption')
            ->with('format')
            ->will($this->returnValue('(%s)'));

        $column->expects($this->at(2))
            ->method('getOption')
            ->with('glue_multiple')
            ->will($this->returnValue('<br>'));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('(1) (Foo)<br>(2) (Bar)');

        $extension->buildCellView($column, $view);
    }
}
