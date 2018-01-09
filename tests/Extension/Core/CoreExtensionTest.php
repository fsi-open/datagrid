<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Tests\Extension\Core;

use FSi\Component\DataGrid\Column\ColumnInterface;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataGrid\Extension\Core\ColumnType\Action;
use FSi\Component\DataGrid\Extension\Core\ColumnType\Batch;
use FSi\Component\DataGrid\Extension\Core\ColumnType\DateTime;
use FSi\Component\DataGrid\Extension\Core\ColumnType\Money;
use FSi\Component\DataGrid\Extension\Core\ColumnType\Number;
use FSi\Component\DataGrid\Extension\Core\ColumnType\Text;
use FSi\Component\DataGrid\Extension\Core\CoreExtension;
use FSi\Component\DataGrid\Extension\Core\EventSubscriber\ColumnOrder;
use FSi\Component\DataGrid\DataGridEventInterface;
use FSi\Component\DataGrid\Extension\Doctrine\ColumnType\Entity;
use PHPUnit\Framework\TestCase;

class CoreExtensionTest extends TestCase
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
        $this->assertTrue($extension->hasColumnTypeExtensions(new Text()));
        $this->assertTrue($extension->hasColumnTypeExtensions(new Number()));
        $this->assertTrue($extension->hasColumnTypeExtensions(new DateTime()));
        $this->assertTrue($extension->hasColumnTypeExtensions(new Action()));
        $this->assertTrue($extension->hasColumnTypeExtensions(new Money()));
        $this->assertTrue($extension->hasColumnTypeExtensions(new Batch()));
        $this->assertTrue($extension->hasColumnTypeExtensions(new Entity()));
    }

    public function testColumnOrder()
    {
        $subscriber = new ColumnOrder();

        $cases = [
            [
                'columns' => [
                    'negative2' => -2,
                    'neutral1' => null,
                    'negative1' => -1,
                    'neutral2' => null,
                    'positive1' => 1,
                    'neutral3' => null,
                    'positive2' => 2,
                ],
                'sorted' => [
                    'negative2',
                    'negative1',
                    'neutral1',
                    'neutral2',
                    'neutral3',
                    'positive1',
                    'positive2',
                ]
            ],
            [
                'columns' => [
                    'neutral1' => null,
                    'neutral2' => null,
                    'neutral3' => null,
                    'neutral4' => null,
                ],
                'sorted' => [
                    'neutral1',
                    'neutral2',
                    'neutral3',
                    'neutral4',
                ]
            ]
        ];

        foreach ($cases as $case) {
            $columns = [];

            foreach ($case['columns'] as $name => $order) {
                $column = $this->createMock(ColumnInterface::class);

                $column
                    ->expects($this->any())
                    ->method('getName')
                    ->will($this->returnValue($name));

                $column
                    ->expects($this->atLeastOnce())
                    ->method('hasOption')
                    ->will($this->returnCallback(function ($attribute) use ($order) {
                        return ('display_order' === $attribute) && (null !== $order);
                    }));

                $column
                    ->expects($this->any())
                    ->method('getOption')
                    ->will($this->returnCallback(function ($attribute) use ($order) {
                        if ('display_order' === $attribute) {
                            return $order;
                        }

                        return null;
                    }));

                $columns[$name] = $column;
            }

            $dataGrid = $this->createMock(DataGridInterface::class);

            $dataGrid
                ->expects($this->at(0))
                ->method('getColumns')
                ->will($this->returnValue($columns));

            $dataGrid
                ->expects($this->at(1))
                ->method('clearColumns');

            $sortedColumns = array_map(
                function (string $columnName) use ($columns): array {
                    return [$columns[$columnName]];
                },
                $case['sorted']
            );
            $dataGrid
                ->expects($this->exactly(count($case['sorted'])))
                ->method('addColumnInstance')
                ->withConsecutive(...$sortedColumns)
                ->will($this->returnSelf());

            $event = $this->createMock(DataGridEventInterface::class);
            $event
                ->expects($this->once())
                ->method('getDataGrid')
                ->will($this->returnValue($dataGrid));

            $subscriber->preBuildView($event);
        }
    }
}
