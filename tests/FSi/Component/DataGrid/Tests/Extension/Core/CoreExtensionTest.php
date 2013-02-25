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

use FSi\Component\DataGrid\Extension\Core\CoreExtension;
use FSi\Component\DataGrid\Extension\Core\EventSubscriber\ColumnOrder;

class CoreExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testLoadedTypes()
    {
        $extension = new CoreExtension();
        $this->assertTrue($extension->hasColumnType('text'));
        $this->assertTrue($extension->hasColumnType('number'));
        $this->assertTrue($extension->hasColumnType('datetime'));
        $this->assertTrue($extension->hasColumnType('action'));
        $this->assertTrue($extension->hasColumnType('money'));
        $this->assertTrue($extension->hasColumnType('action'));

        $this->assertFalse($extension->hasColumnType('foo'));
    }

    public function testLoadedExtensions()
    {
        $extension = new CoreExtension();
        $this->assertTrue($extension->hasColumnTypeExtensions('text'));
        $this->assertTrue($extension->hasColumnTypeExtensions('text'));
        $this->assertTrue($extension->hasColumnTypeExtensions('number'));
        $this->assertTrue($extension->hasColumnTypeExtensions('datetime'));
        $this->assertTrue($extension->hasColumnTypeExtensions('action'));
        $this->assertTrue($extension->hasColumnTypeExtensions('money'));
        $this->assertTrue($extension->hasColumnTypeExtensions('gedmo.tree'));
        $this->assertTrue($extension->hasColumnTypeExtensions('entity'));
    }

    public function testColumnOrder()
    {
        $subscriber = new ColumnOrder();

        $cases = array(
            array(
                'columns' => array(
                    'negative2' => -2,
                    'neutral1' => null,
                    'negative1' => -1,
                    'neutral2' => null,
                    'positive2' => 1,
                    'neutral3' => null,
                    'positive1' => 2,
                ),
                'sorted' => array(
                    'positive1',
                    'positive2',
                    'neutral1',
                    'neutral2',
                    'neutral3',
                    'negative1',
                    'negative2',
                )
            )
        );

        foreach ($cases as $case) {
            $columns = array();

            foreach ($case['columns'] as $name => $order) {
                $columnHeader = $this->getMock('FSi\Component\DataGrid\Column\HeaderViewInterface');

                $columnHeader
                    ->expects($this->atLeastOnce())
                    ->method('getName')
                    ->will($this->returnValue($name));

                $columnHeader
                    ->expects($this->atLeastOnce())
                    ->method('hasAttribute')
                    ->will($this->returnCallback(function ($attribute) use ($order) {
                        if (($attribute == 'order') && isset($order)) {
                            return true;
                        } else {
                            return false;
                        }
                    }));

                $columnHeader
                    ->expects($this->any())
                    ->method('getAttribute')
                    ->will($this->returnCallback(function ($attribute) use ($order) {
                        if (($attribute == 'order') && isset($order)) {
                            return $order;
                        } else {
                            return null;
                        }
                    }));

                $columns[] = $columnHeader;
            }

            $view = $this->getMock('FSi\Component\DataGrid\DataGridViewInterface');

            $self = $this;

            $view
                ->expects($this->once())
                ->method('getColumns')
                ->will($this->returnValue($columns));

            $view
                ->expects($this->once())
                ->method('setColumns')
                ->will($this->returnCallback(function (array $columns) use ($self, $case) {
                    $sorted = array();
                    foreach ($columns as $column) {
                        $sorted[] = $column->getName();
                    }
                    $self->assertSame($case['sorted'], $sorted);
                }));

            $event = $this->getMock('FSi\Component\DataGrid\DataGridEventInterface');
            $event
                ->expects($this->once())
                ->method('getData')
                ->will($this->returnValue($view));

            $subscriber->postBuildView($event);
        }
    }
}
