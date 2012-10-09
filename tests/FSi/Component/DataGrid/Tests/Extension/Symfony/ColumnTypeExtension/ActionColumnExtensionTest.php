<?php
/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Tests\Extension\Symfony\ColumnTypeExtension;

use FSi\Component\DataGrid\Extension\Symfony\ColumnTypeExtension\ActionColumnExtension;

class ActionColumnExtensionTest extends \PHPUnit_Framework_TestCase
{
    private $router;

    protected function setUp()
    {
        if (!class_exists('Symfony\Component\Routing\Router')) {
            $this->markTestSkipped('Symfony Column Extension require Symfony\Component\Routing\Router class.');
        }
        $this->router = $this->getMock('Symfony\Component\Routing\RouterInterface');
    }

    public function testFilterValueWrongActionsOptionType()
    {
        $extension = new ActionColumnExtension($this->router);

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
        $extension = new ActionColumnExtension($this->router);

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
        $extension = new ActionColumnExtension($this->router);

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
        $this->router->expects($this->once())
             ->method('generate')
             ->with('foo', array(), false)
             ->will($this->returnValue('/test/bar'));

        $extension = new ActionColumnExtension($this->router);

        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $column->expects($this->any())
               ->method('getOption')
               ->with('actions')
               ->will($this->returnValue(array(
                   'edit' => array(
                       'route_name' => 'foo',
                       'anchor' => 'test',
                       'absolute' => false
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
        $this->router->expects($this->once())
             ->method('generate')
             ->with('foo', array('foo' => 'bar'), true)
             ->will($this->returnValue('https://fsi.pl/test/bar'));

        $extension = new ActionColumnExtension($this->router);

        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $column->expects($this->at(0))
               ->method('getOption')
               ->with('actions')
               ->will($this->returnValue(array(
                   'edit' => array(
                       'route_name' => 'foo',
                       'parameters' => array('foo' => 'foo'),
                       'anchor' => 'test',
                       'absolute' => true
                   )
               )));

        $column->expects($this->at(1))
               ->method('getOption')
               ->with('mapping_fields')
               ->will($this->returnValue(array(
                    'foo'
                   )
               ));

        $column->expects($this->at(3))
               ->method('getOption')
               ->with('actions')
               ->will($this->returnValue(array(
                   'edit' => array(
                       'route_name' => 'foo',
                       'parameters' => array('foo' => 'foo'),
                       'anchor' => 'test',
                       'absolute' => true
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