<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid;

use FSi\Component\DataGrid\Column\ColumnInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

interface DataGridInterface
{
    public function getFactory(): DataGridFactoryInterface;

    public function getName(): string;

    public function addColumn(string $name, string $type, array $options = []): DataGridInterface;

    public function addColumnInstance(ColumnInterface $column): DataGridInterface;

    public function removeColumn(string $name): DataGridInterface;

    public function clearColumns(): DataGridInterface;

    public function getColumn(string $name): ColumnInterface;

    /**
     * @return ColumnInterface[]
     */
    public function getColumns(): array;

    public function hasColumn(string $name): bool;

    public function hasColumnType(string $type): bool;

    public function createView(): DataGridViewInterface;

    public function setData(iterable $data): void;
}
