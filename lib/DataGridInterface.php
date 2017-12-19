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
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

interface DataGridInterface
{
    public function getName(): string;

    public function getDataMapper(): DataMapperInterface;

    public function addColumn($name, string $type = 'text', array $options = []): DataGridInterface;

    public function removeColumn(string $name): DataGridInterface;

    public function clearColumns(): DataGridInterface;

    public function getColumn(string $name): ColumnTypeInterface;

    /**
     * @return ColumnTypeInterface[]
     */
    public function getColumns(): array;

    public function hasColumn(string $name): bool;

    public function hasColumnType(string $type): bool;

    public function createView(): DataGridViewInterface;

    public function setData(iterable $data): void;

    public function bindData($data): void;

    public function addEventListener(string $eventName, callable $listener, int $priority = 0): DataGridInterface;

    public function addEventSubscriber(EventSubscriberInterface $subscriber): DataGridInterface;
}
