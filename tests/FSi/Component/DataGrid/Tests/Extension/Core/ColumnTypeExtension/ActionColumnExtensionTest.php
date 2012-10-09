<?php
/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Tests\Extension\Core;

use FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension\ActionColumnExtension;

class ActionColumnExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testFilterValueWrongActionsOptionType()
    {
        $extension = new ActionColumnExtension();

        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $column->expects($this->at(0))
               ->method('getOption')
               ->with('actions')
               ->will($this->returnValue('boo'));

        $this->setExpectedException('InvalidArgumentException');
        $extension->filterValue($column, array());
    }

    public function testFilterValueEmptyActionsOptionType()
    {
        $extension = new ActionColumnExtension();

        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $column->expects($this->at(0))
               ->method('getOption')
               ->with('actions')
               ->will($this->returnValue(array()));

        $this->setExpectedException('InvalidArgumentException');
        $extension->filterValue($column, array());
    }

    public function testFilterValueInvalidActionInActionsOption()
    {
        $extension = new ActionColumnExtension();

        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $column->expects($this->at(0))
               ->method('getOption')
               ->with('actions')
               ->will($this->returnValue(array(
                   'edit' => array()
               )));

       $this->setExpectedException('InvalidArgumentException');
       $extension->filterValue($column, array());
    }

    public function testFilterValueRequiredActionInActionsOption()
    {
        $extension = new ActionColumnExtension();

        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $column->expects($this->any())
               ->method('getOption')
               ->with('actions')
               ->will($this->returnValue(array(
                   'edit' => array(
                       'uri_scheme' => '/test/%s',
                       'anchor' => 'test'
                   )
               )));

       $this->assertSame(
           array(
               'edit' => array(
                   'name' => 'edit',
                   'anchor' => 'test',
                   'url' => '/test/bar'
               )
           ),
           $extension->filterValue($column, array(
               'foo' => 'bar'
           ))
       );
    }

    public function testFilterValueAvailableActionInActionsOption()
    {
        $extension = new ActionColumnExtension();

        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $column->expects($this->any())
               ->method('getOption')
               ->with('actions')
               ->will($this->returnValue(array(
                   'edit' => array(
                       'uri_scheme' => '/test/%s',
                       'anchor' => 'test',
                       'domain' => 'fsi.pl',
                       'protocole' => 'https://'
                   )
               )));

       $this->assertSame(
           array(
               'edit' => array(
                   'name' => 'edit',
                   'anchor' => 'test',
                   'url' => 'https://fsi.pl/test/bar'
               )
           ),
           $extension->filterValue($column, array(
               'foo' => 'bar'
           ))
       );
    }
}