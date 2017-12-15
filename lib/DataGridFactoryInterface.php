<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid;

use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\DataMapper\DataMapperInterface;

interface DataGridFactoryInterface
{
    public function hasColumnType(string $type): bool;

    public function getColumnType(string $type): ColumnTypeInterface;

    /**
     * @return DataGridExtensionInterface[]
     */
    public function getExtensions(): array;

    public function createDataGrid(string $name = 'grid'): DataGridInterface;

    public function getDataMapper(): DataMapperInterface;
}
