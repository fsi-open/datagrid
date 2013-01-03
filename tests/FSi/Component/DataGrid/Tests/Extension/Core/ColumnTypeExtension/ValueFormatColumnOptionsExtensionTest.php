<?php
/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Tests\Extension\Core\ColumntypeExtension;

use FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension\ValueFormatColumnOptionsExtension;

class ValueFormatColumnOptionsExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testBuildCellView()
    {
        $extension = new ValueFormatColumnOptionsExtension();

        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');
        $view = $this->getMock('FSi\Component\DataGrid\Column\CellViewInterface');

        $column->expects($this->at(0))
            ->method('getOption')
            ->with('glue')
            ->will($this->returnValue('-'));

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue(array('foo', 'bar')));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('foo-bar');

        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithoutFormatAndGlue()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->getMock('FSi\Component\DataGrid\Column\CellViewInterface');
        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue(array('foo')));

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
            ->with('foo');

        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithFormatAndGlue()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->getMock('FSi\Component\DataGrid\Column\CellViewInterface');
        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue(array('foo', 'bar')));

        $column->expects($this->at(0))
            ->method('getOption')
            ->with('glue')
            ->will($this->returnValue('<br/>'));

        $column->expects($this->at(1))
            ->method('getOption')
            ->with('format')
            ->will($this->returnValue('<b>%s</b>'));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('<b>foo</b><br/><b>bar</b>');

        $extension->buildCellView($column, $view);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testBuildCellViewWithoutFormatAndGlueWithValueArray()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->getMock('FSi\Component\DataGrid\Column\CellViewInterface');
        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue(array('foo', 'bar')));

        $column->expects($this->at(0))
            ->method('getOption')
            ->with('glue')
            ->will($this->returnValue(null));

        $column->expects($this->at(1))
            ->method('getOption')
            ->with('format')
            ->will($this->returnValue(null));

        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithValidFormat()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->getMock('FSi\Component\DataGrid\Column\CellViewInterface');
        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue(array('foo')));

        $column->expects($this->at(0))
            ->method('getOption')
            ->with('glue')
            ->will($this->returnValue(null));

        $column->expects($this->at(1))
            ->method('getOption')
            ->with('format')
            ->will($this->returnValue('<b>%s</b>'));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('<b>foo</b>');

        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithValidFormatAndValueArray()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->getMock('FSi\Component\DataGrid\Column\CellViewInterface');
        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue(array('foo', 'bar')));

        $column->expects($this->at(0))
            ->method('getOption')
            ->with('glue')
            ->will($this->returnValue(null));

        $column->expects($this->at(1))
            ->method('getOption')
            ->with('format')
            ->will($this->returnValue('<b>%s</b><br/><b>%s</b>'));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('<b>foo</b><br/><b>bar</b>');

        $extension->buildCellView($column, $view);
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testBuildCellViewWithFormatWithTooManyPlaceholders()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->getMock('FSi\Component\DataGrid\Column\CellViewInterface');
        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue(array('foo')));

        $column->expects($this->at(0))
            ->method('getOption')
            ->with('glue')
            ->will($this->returnValue(null));

        $column->expects($this->at(1))
            ->method('getOption')
            ->with('format')
            ->will($this->returnValue('%s%s'));

        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithFormatWithLessPlaceholders()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->getMock('FSi\Component\DataGrid\Column\CellViewInterface');
        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue(array('foo', 'bar')));

        $column->expects($this->at(0))
            ->method('getOption')
            ->with('glue')
            ->will($this->returnValue(null));

        $column->expects($this->at(1))
            ->method('getOption')
            ->with('format')
            ->will($this->returnValue('<b>%s</b>'));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('<b>foo</b>');

        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithEmtpyFormat()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->getMock('FSi\Component\DataGrid\Column\CellViewInterface');
        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue(array('foo')));

        $column->expects($this->at(0))
            ->method('getOption')
            ->with('glue')
            ->will($this->returnValue(null));

        $column->expects($this->at(1))
            ->method('getOption')
            ->with('format')
            ->will($this->returnValue(''));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('');

        $extension->buildCellView($column, $view);
    }

}
