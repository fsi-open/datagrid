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

use FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension\DefaultColumnOptionsExtension;

class DefaultColumnOptionsExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testBuildViewForAction()
    {
        $extension = new DefaultColumnOptionsExtension();

        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');
        $view   = $this->getMock('FSi\Component\DataGrid\Column\ColumnViewInterface');

        $column->expects($this->at(0))
            ->method('getId')
            ->will($this->returnValue('action'));

        $view->expects($this->never())
            ->method('getValue');

        $extension->buildView($column, $view);
    }

    public function testBuildView()
    {
        $extension = new DefaultColumnOptionsExtension();

        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');
        $view   = $this->getMock('FSi\Component\DataGrid\Column\ColumnViewInterface');

        $column->expects($this->at(0))
            ->method('getId')
            ->will($this->returnValue('text'));

        $column->expects($this->at(1))
            ->method('getOption')
            ->with('glue')
            ->will($this->returnValue('-'));

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue(array('foo', 'bar')));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('foo-bar');

        $extension->buildView($column, $view);

    }
}