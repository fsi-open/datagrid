<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid;

use FSi\Component\DataGrid\Data\DataRowsetInterface;
use FSi\Component\DataGrid\Data\DataRowset;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\DataMapper\DataMapperInterface;
use FSi\Component\DataGrid\Exception\UnexpectedTypeException;
use FSi\Component\DataGrid\Exception\DataGridException;
use InvalidArgumentException;
use Symfony\Component\EventDispatcher\EventDispatcher;
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
     * @var DataMapperInterface
     */
    protected $dataMapper;

    /**
     * @var DataGridFactoryInterface
     */
    protected $dataGridFactory;

    /**
     * @var ColumnTypeInterface[]
     */
    protected $columns = [];

    /**
     * @var EventDispatcher
     */
    protected $eventDispatcher;

    public function __construct(
        string $name,
        DataGridFactoryInterface $dataGridFactory,
        DataMapperInterface $dataMapper
    ) {
        $this->name = $name;
        $this->dataGridFactory = $dataGridFactory;
        $this->dataMapper = $dataMapper;
        $this->eventDispatcher = new EventDispatcher();
        $this->registerSubscribers();
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param ColumnTypeInterface|string $name
     * @param string $type
     * @param array $options
     * @return $this|DataGridInterface
     * @throws UnexpectedTypeException
     */
    public function addColumn($name, string $type = 'text', array $options = []): DataGridInterface
    {
        if ($name instanceof ColumnTypeInterface) {
            $type = $name->getId();

            if (!$this->dataGridFactory->hasColumnType($type)) {
                throw new UnexpectedTypeException(sprintf(
                    'There is no column with type "%s" registred in factory.',
                    $type
                ));
            }

            $name->setDataGrid($this);
            $this->columns[$name->getName()] = $name;

            return $this;
        }

        $column = $this->dataGridFactory->getColumnType($type);
        $column->setName($name);
        $column->setDataGrid($this);

        $column->initOptions();
        foreach ($column->getExtensions() as $extension) {
            $extension->initOptions($column);
        }
        $column->setOptions($options);

        $this->columns[$name] = $column;

        return $this;
    }

    public function getColumn(string $name): ColumnTypeInterface
    {
        if (!$this->hasColumn($name)) {
            throw new InvalidArgumentException(sprintf(
                'Column "%s" does not exist in data grid.',
                $name
            ));
        }

        return $this->columns[$name];
    }

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
            if ($column->getId() === $type) {
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

    public function getDataMapper(): DataMapperInterface
    {
        return $this->dataMapper;
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

    public function bindData($data): void
    {
        $event = new DataGridEvent($this, $data);
        $this->eventDispatcher->dispatch(DataGridEvents::PRE_BIND_DATA, $event);
        $data = $event->getData();

        foreach ($data as $index => $values) {
            if (!isset($this->rowset[$index])) {
                continue;
            }

            $object = $this->rowset[$index];

            foreach ($this->getColumns() as $column) {
                $column->bindData($values, $object, $index);
            }
        }

        $event = new DataGridEvent($this, $data);
        $this->eventDispatcher->dispatch(DataGridEvents::POST_BIND_DATA, $event);
    }

    public function addEventListener(string $eventName, callable $listener, int $priority = 0): DataGridInterface
    {
        $this->eventDispatcher->addListener($eventName, $listener, $priority);

        return $this;
    }

    public function addEventSubscriber(EventSubscriberInterface $subscriber): DataGridInterface
    {
        $this->eventDispatcher->addSubscriber($subscriber);

        return $this;
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

    private function registerSubscribers(): void
    {
        $extensions = $this->dataGridFactory->getExtensions();

        foreach ($extensions as $extension) {
            $extension->registerSubscribers($this);
        }
    }
}
