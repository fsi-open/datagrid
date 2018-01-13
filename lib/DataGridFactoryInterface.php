<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid;

use FSi\Component\DataGrid\Column\CellViewInterface;
use FSi\Component\DataGrid\Column\ColumnInterface;
use FSi\Component\DataGrid\Column\ColumnTypeExtensionInterface;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\Column\HeaderViewInterface;

interface DataGridFactoryInterface
{
    public function hasColumnType(string $type): bool;

    public function getColumnType(string $type): ColumnTypeInterface;

    public function createDataGrid(string $name): DataGridInterface;

    public function createColumn(
        DataGridInterface $dataGrid,
        string $type,
        string $name,
        array $options
    ): ColumnInterface;

    /**
     * @param ColumnInterface $column
     * @param int|string $index
     * @param mixed $source
     * @return CellViewInterface
     */
    public function createCellView(ColumnInterface $column, $index, $source): CellViewInterface;

    public function createHeaderView(ColumnInterface $column): HeaderViewInterface;

    /**
     * @param ColumnTypeInterface $columnType
     * @return ColumnTypeExtensionInterface[]
     */
    public function getColumnTypeExtensions(ColumnTypeInterface $columnType): array;
}
