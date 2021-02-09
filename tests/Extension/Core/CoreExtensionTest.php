<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Tests\Extension\Core;

use FSi\Component\DataGrid\Extension\Core\CoreExtension;
use FSi\Component\DataGrid\Extension\Core\EventSubscriber\ColumnOrder;
use FSi\Component\DataGrid\DataGridEventInterface;
use FSi\Component\DataGrid\DataGridViewInterface;
use FSi\Component\DataGrid\Column\HeaderViewInterface;
use PHPUnit\Framework\TestCase;

class CoreExtensionTest extends TestCase
{
    public function testLoadedTypes(): void
    {
        $extension = new CoreExtension();
        self::assertTrue($extension->hasColumnType('text'));
        self::assertTrue($extension->hasColumnType('number'));
        self::assertTrue($extension->hasColumnType('datetime'));
        self::assertTrue($extension->hasColumnType('action'));
        self::assertTrue($extension->hasColumnType('money'));
        self::assertTrue($extension->hasColumnType('action'));

        self::assertFalse($extension->hasColumnType('foo'));
    }

    public function testLoadedExtensions(): void
    {
        $extension = new CoreExtension();
        self::assertTrue($extension->hasColumnTypeExtensions('text'));
        self::assertTrue($extension->hasColumnTypeExtensions('text'));
        self::assertTrue($extension->hasColumnTypeExtensions('number'));
        self::assertTrue($extension->hasColumnTypeExtensions('datetime'));
        self::assertTrue($extension->hasColumnTypeExtensions('action'));
        self::assertTrue($extension->hasColumnTypeExtensions('money'));
        self::assertTrue($extension->hasColumnTypeExtensions('gedmo_tree'));
        self::assertTrue($extension->hasColumnTypeExtensions('entity'));
    }

    public function testColumnOrder(): void
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
                $columnHeader = $this->createMock(HeaderViewInterface::class);

                $columnHeader
                    ->expects(self::atLeastOnce())
                    ->method('getName')
                    ->willReturn($name);

                $columnHeader
                    ->expects(self::atLeastOnce())
                    ->method('hasAttribute')
                    ->willReturnCallback(
                        function ($attribute) use ($order) {
                            if (('display_order' === $attribute) && isset($order)) {
                                return true;
                            }

                            return false;
                        }
                    );

                $columnHeader
                    ->method('getAttribute')
                    ->willReturnCallback(
                        function ($attribute) use ($order) {
                            if (('display_order' === $attribute) && isset($order)) {
                                return $order;
                            }

                            return null;
                        }
                    );

                $columns[] = $columnHeader;
            }

            $view = $this->createMock(DataGridViewInterface::class);

            $view->expects(self::once())->method('getColumns')->willReturn($columns);

            $view
                ->expects(self::once())
                ->method('setColumns')
                ->willReturnCallback(
                    function (array $columns) use ($case) {
                        $sorted = [];
                        foreach ($columns as $column) {
                            $sorted[] = $column->getName();
                        }
                        self::assertSame($case['sorted'], $sorted);
                    }
                );

            $event = $this->createMock(DataGridEventInterface::class);
            $event->expects(self::once())->method('getData')->willReturn($view);

            $subscriber->postBuildView($event);
        }
    }
}
