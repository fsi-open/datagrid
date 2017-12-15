<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid;

use FSi\Component\DataGrid\Column\ColumnTypeExtensionInterface;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;

interface DataGridExtensionInterface
{
    public function registerSubscribers(DataGridInterface $dataGrid): void;

    public function hasColumnType(string $type): bool;

    public function getColumnType(string $type): ColumnTypeInterface;

    public function hasColumnTypeExtensions(string $type): bool;

    /**
     * @param string $type
     * @return ColumnTypeExtensionInterface[]
     */
    public function getColumnTypeExtensions(string $type): array;
}
