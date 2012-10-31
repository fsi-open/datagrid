<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid;

use FSi\Component\DataGrid\DataGridView;
use FSi\Component\DataGrid\DataGridEvent;
use FSi\Component\DataGrid\DataGridEvents;
use FSi\Component\DataGrid\Data\DataRowset;
use FSi\Component\DataGrid\Data\IndexingStrategyInterface;
use FSi\Component\DataGrid\Event\DataEvent;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\DataMapper\DataMapperInterface;
use FSi\Component\DataGrid\Exception\UnexpectedTypeException;
use FSi\Component\DataGrid\Exception\DataGridException;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DataGrid implements DataGridInterface
{
    /**
     * Unique data grid name. With this name grid is registred in factory.
     * @var string
     */
    protected $name;

    /**
     * DataCollection used to render view.
     * @var FSi\Component\DataGrid\Data\RowsetData
     */
    protected $rowset;

    /**
     * DataMapper used by all columns to retrive data from rowset objects.
     * @var DataMapperInterface
     */
    protected $dataMapper;

    /**
     * Factory that holds all column types and column types extensions.
     * @var DataGridFactoryInterface
     */
    protected $dataGridFactory;

    /**
     * Columns cloned from $dataGridFactory and used to render rowset view.
     * @var array
     */
    protected $columns = array();

    /**
     * Symfony EventDispatcher mechanism that allow users to register
     * listeners and subsribers.
     * @var EventDispatcher
     */
    protected $eventDispatcher;

    /**
     * Indexing strategy used to index rowset oryginal data under unique indexes.
     * @var IndexingStrategyInterface
     */
    protected $strategy;

    public function __construct($name, DataGridFactoryInterface $dataGridFactory, DataMapperInterface $dataMapper, IndexingStrategyInterface $strategy)
    {
        $this->name = $name;
        $this->dataGridFactory = $dataGridFactory;
        $this->dataMapper = $dataMapper;
        $this->strategy = $strategy;
        $this->eventDispatcher = new EventDispatcher();
        $this->registerSubscribers();
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function addColumn($name, $type = 'text', $otpions = array())
    {
        if ($name instanceof ColumnTypeInterface) {
            $type = $name->getId();

            if (!$this->dataGridFactory->hasColumnType($type)) {
                throw new UnexpectedTypeException(sprintf('There is no column with type "%s" registred in factory.', $type));
            }

            $name->setDataGrid($this);
            $this->columns[$name->getName()] = $name;
            return $this;
        }

        $column = $this->dataGridFactory->getColumnType($type);
        $column->setName($name)
               ->setDataGrid($this);

        foreach ($otpions as $key => $value) {
            $column->setOption($key, $value);
        }

        $this->columns[$name] = $column;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getColumn($name)
    {
        if (!$this->hasColumn($name)) {
            throw new \InvalidArgumentException(sprintf('Column "%s" does not exist in data grid.', $name));
        }

        return $this->columns[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * {@inheritdoc}
     */
    public function hasColumn($name)
    {
        return isset($this->columns[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function removeColumn($name)
    {
        if (!$this->hasColumn($name)) {
            throw new \InvalidArgumentException(sprintf('Column "%s" does not exist in data grid.', $name));
        }

        unset($this->columns[$name]);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function clearColumns()
    {
        $this->columns = array();
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDataMapper()
    {
        return $this->dataMapper;
    }

    /**
     * {@inheritdoc}
     */
    public function getIndexingStrategy()
    {
        return $this->strategy;
    }

    /**
     * {@inheritdoc}
     */
    public function setData($data)
    {
        $event = new DataGridEvent($this, $data);
        $this->eventDispatcher->dispatch(DataGridEvents::PRE_SET_DATA, $event);
        $data = $event->getData();

        if (!is_array($data)) {
            if (!($data instanceof \Traversable)) {
                throw new \InvalidArgumentException('array or Traversable object is expected as data in setData method.');
            }
        }

        $this->rowset = new DataRowset($this->strategy, $this->dataMapper);
        $this->rowset->setData($data);

        $event = new DataGridEvent($this, $data);
        $this->eventDispatcher->dispatch(DataGridEvents::POST_SET_DATA, $event);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function bindData($data)
    {
        $event = new DataGridEvent($this, $data);
        $this->eventDispatcher->dispatch(DataGridEvents::PRE_BIND_DATA, $event);
        $data = $event->getData();

        if (!is_array($data)) {
            if (!($data instanceof \ArrayIterator)) {
                throw new \InvalidArgumentException('array or Traversable object is expected as data in bindData method.');
            }
        }

        foreach ($data as $index => $values) {
            if (!$this->rowset->hasObjectWithIndex($index)) {
                continue;
            }

            $objcet = $this->rowset->getObjectByIndex($index);

            foreach ($this->getColumns() as $column) {
                $column->bindData($values, $objcet, $index);
            }
        }

        $event = new DataGridEvent($this, $data);
        $this->eventDispatcher->dispatch(DataGridEvents::POST_BIND_DATA, $event);
    }

    /**
     * {@inheritdoc}
     */
    public function addEventListener($eventName, $listener, $priority = 0)
    {
        $this->eventDispatcher->addListener($eventName, $listener, $priority);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addEventSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->eventDispatcher->addSubscriber($subscriber);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function createView()
    {
        $view = new DataGridView($this->name, $this->getRowset());

        foreach ($this->columns as $name => $column) {
            $view->addColumn($column);
        }

        $extensions = $this->dataGridFactory->getExtensions();

        foreach ($extensions as $extension) {
            $extension->buildView($view, $this);
        }

        return $view;
    }


    /**
     * Returns data grid rowset that contains source data.
     *
     * @throws DataGridException thrown when getRowset is called before setData
     * @return DataRowset
     */
    private function getRowset()
    {
        if (!isset($this->rowset)) {
            throw new DataGridException(
            	'Before you will be able to crete view from DataGrid you need to call method setData'
            );
        }

        return $this->rowset;
    }

    /**
     * Register all event subsribers provided by extensions.
     */
    private function registerSubscribers()
    {
        $extensions = $this->dataGridFactory->getExtensions();

        foreach ($extensions as $extension) {
            $extension->registerSubscribers($this);
        }
    }
}