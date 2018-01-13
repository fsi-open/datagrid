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
use FSi\Component\DataGrid\Data\DataRowsetInterface;
use FSi\Component\DataGrid\Data\DataRowset;
use FSi\Component\DataGrid\Exception\DataGridException;
use InvalidArgumentException;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DataGrid implements DataGridInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var DataRowsetInterface
     */
    protected $rowset;

    /**
     * @var DataGridFactoryInterface
     */
    protected $dataGridFactory;

    /**
     * @var ColumnInterface[]
     */
    protected $columns = [];

    /**
     * @var EventDispatcher
     */
    protected $eventDispatcher;

    public function __construct(
        string $name,
        DataGridFactoryInterface $dataGridFactory,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->name = $name;
        $this->dataGridFactory = $dataGridFactory;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function getFactory(): DataGridFactoryInterface
    {
        return $this->dataGridFactory;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function addColumn(string $name, string $type, array $options = []): DataGridInterface
    {
        return $this->addColumnInstance(
            $this->dataGridFactory->createColumn($this, $type, $name, $options)
        );
    }

    public function addColumnInstance(ColumnInterface $column): DataGridInterface
    {
        if ($column->getDataGrid() !== $this) {
            throw new InvalidArgumentException('Tried to add column associated with different datagrid instance');
        }

        $this->columns[$column->getName()] = $column;

        return $this;
    }

    public function getColumn(string $name): ColumnInterface
    {
        if (!$this->hasColumn($name)) {
            throw new InvalidArgumentException(sprintf(
                'Column "%s" does not exist in data grid.',
                $name
            ));
        }

        return $this->columns[$name];
    }

    /**
     * @return ColumnInterface[]
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    public function hasColumn(string $name): bool
    {
        return array_key_exists($name, $this->columns);
    }

    public function hasColumnType(string $type): bool
    {
        foreach ($this->columns as $column) {
            if ($column->getType()->getId() === $type) {
                return true;
            }
        }

        return false;
    }

    public function removeColumn(string $name): DataGridInterface
    {
        if (!$this->hasColumn($name)) {
            throw new InvalidArgumentException(sprintf(
                'Column "%s" does not exist in data grid.',
                $name
            ));
        }

        unset($this->columns[$name]);

        return $this;
    }

    public function clearColumns(): DataGridInterface
    {
        $this->columns = [];

        return $this;
    }

    public function setData(iterable $data): void
    {
        $event = new DataGridEvent($this, $data);
        $this->eventDispatcher->dispatch(DataGridEvents::PRE_SET_DATA, $event);
        $data = $event->getData();
        if (!is_iterable($data)) {
            throw new InvalidArgumentException(sprintf(
                'The data returned by the "DataGridEvents::PRE_SET_DATA" class needs to be iterable, "%s" given!',
                is_object($data) ? get_class($data) : gettype($data)
            ));
        }

        $this->rowset = new DataRowset($data);

        $event = new DataGridEvent($this, $this->rowset);
        $this->eventDispatcher->dispatch(DataGridEvents::POST_SET_DATA, $event);
    }

    public function createView(): DataGridViewInterface
    {
        $event = new DataGridEvent($this, null);
        $this->eventDispatcher->dispatch(DataGridEvents::PRE_BUILD_VIEW, $event);

        $view = new DataGridView($this->name, $this->columns, $this->getRowset());

        $event = new DataGridEvent($this, $view);
        $this->eventDispatcher->dispatch(DataGridEvents::POST_BUILD_VIEW, $event);
        $view = $event->getData();

        return $view;
    }

    private function getRowset(): DataRowsetInterface
    {
        if (null === $this->rowset) {
            throw new DataGridException(
                'Before you will be able to crete view from DataGrid you need to call method setData'
            );
        }

        return $this->rowset;
    }
}
