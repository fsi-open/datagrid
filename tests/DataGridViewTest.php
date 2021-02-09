<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Tests;

use FSi\Component\DataGrid\DataGridView;
use FSi\Component\DataGrid\Data\DataRowsetInterface;
use FSi\Component\DataGrid\Column\HeaderViewInterface;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use PHPUnit\Framework\TestCase;

class DataGridViewTest extends TestCase
{
    /**
     * @var DataRowsetInterface
     */
    private $rowset;

    /**
     * @var DataGridView
     */
    private $gridView;

    public function testAddHasGetRemoveColumn(): void
    {
        $self = $this;

        $column = $this->createMock(ColumnTypeInterface::class);
        $column
            ->method('createHeaderView')
            ->willReturnCallback(
                function () use ($self) {
                    $headerView = $self->createMock(HeaderViewInterface::class);
                    $headerView->method('getName')->willReturn('ColumnHeaderView');
                    $headerView->method('getType')->willReturn('foo-type');

                    return $headerView;
                }
            );

        $column->method('getName')->willReturn('foo');

        $columnHeader = $this->createMock(HeaderViewInterface::class);
        $columnHeader->method('getName')->willReturn('foo');
        $columnHeader->method('getType')->willReturn('foo-type');

        $this->rowset = $this->createMock(DataRowsetInterface::class);
        $this->gridView = new DataGridView('test-grid-view', [$column], $this->rowset);

        self::assertSame('test-grid-view', $this->gridView->getName());

        self::assertTrue($this->gridView->hasColumn('foo'));
        self::assertTrue($this->gridView->hasColumnType('foo-type'));
        self::assertCount(1, $this->gridView->getColumns());
        self::assertSame($this->gridView->getColumn('foo')->getName(), 'ColumnHeaderView');
        $this->gridView->removeColumn('foo');
        self::assertFalse($this->gridView->hasColumn('foo'));

        $this->gridView->addColumn($columnHeader);
        self::assertTrue($this->gridView->hasColumn('foo'));

        $this->gridView->clearColumns();
        self::assertFalse($this->gridView->hasColumn('foo'));
    }
}
