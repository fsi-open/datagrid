<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\Table;

use FSi\Component\Table\TableView;
use FSi\Component\Table\TableEvent;
use FSi\Component\Table\TableEvents;
use FSi\Component\Table\Data\DataRowset;
use FSi\Component\Table\Data\IndexingStrategyInterface;
use FSi\Component\Table\Event\DataEvent;
use FSi\Component\Table\Column\ColumnTypeInterface;
use FSi\Component\Table\DataMapper\DataMapperInterface;
use FSi\Component\Table\Exception\UnexpectedTypeException;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class Table implements TableInterface
{
    protected $name;
    
    /**
     * @var FSi\Component\Table\Data\RowsetData
     */
    protected $rowset; 

    protected $dataMapper;

    protected $tableFactory;

    protected $columns = array();
    
    protected $eventDispatcher;
    
    protected $strategy;

    public function __construct($name, TableFactoryInterface $tableFactory, DataMapperInterface $dataMapper, IndexingStrategyInterface $strategy)
    {
        $this->name = $name;
        $this->tableFactory = $tableFactory;
        $this->dataMapper = $dataMapper;
        $this->strategy = $strategy;
        $this->eventDispatcher = new EventDispatcher();
        $this->registerListeners();
    }
    
    public function getName()
    {
        return $this->name;
    }

    public function getRowset()
    {
        return $this->rowset;
    }

    public function getColumn($name)
    {
        if (!$this->hasColumn($name)) {
            throw new \InvalidArgumentException(sprintf('Column "%s" does not exist in table.', $name));
        }
        
        return $this->columns[$name];
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function hasColumn($name)
    {
        return isset($this->columns[$name]);
    }

    public function addColumn($name, $type = 'text', $otpions = array())
    {
        $column = $this->tableFactory->getColumnType($type);
        $column->setName($name)
               ->setTable($this);
               
        foreach ($otpions as $key => $value) {
            $column->setOption($key, $value);
        }
        
        $this->columns[$name] = $column;

        return $this;
    }

    public function removeColumn($name)
    {
        if (!$this->hasColumn($name)) {
            throw new \InvalidArgumentException(sprintf('Column "%s" does not exist in table.', $name));
        }

        unset($this->columns[$name]);
        
        return $this;
    }
    
    public function getDataMapper()
    {
        return $this->dataMapper;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setData($data)
    {
        $event = new TableEvent($this, $data);
        $this->eventDispatcher->dispatch(TableEvents::PRE_SET_DATA, $event);
        $data = $event->getData();
        
        if (!is_array($data)) {
            if (!($data instanceof \IteratorAggregate) || !($data instanceof \Countable) || !($data instanceof \ArrayAccess))
                throw new UnexpectedTypeException($data, 'array or IteratorAggregate');
        }
        
        $this->rowset = new DataRowset($this->strategy, $this->dataMapper);
        $this->rowset->setData($data);
        
        $event = new TableEvent($this, $data);
        $this->eventDispatcher->dispatch(TableEvents::POST_SET_DATA, $event);
        
        return $this;
    }
    
    /**
     * {@inheritdoc}
     */
    public function bindData($data)
    {
        $event = new TableEvent($this, $data);
        $this->eventDispatcher->dispatch(TableEvents::PRE_BIND_DATA, $event);
        $data = $event->getData();

        if (!is_array($data)) {
            if (!($data instanceof \IteratorAggregate) || !($data instanceof \Countable) || !($data instanceof \ArrayAccess))
                throw new UnexpectedTypeException($data, 'array or IteratorAggregate');
        }

        foreach ($data as $index => $columns) {
            if (!$this->rowset->hasObjectWithIndex($index)) {
                throw new \OutOfBoundsException(sprintf('Illegal index "%d"', $index));
            }
            $objcet = $this->rowset->getObjectByIndex($index);
            
            foreach ($columns as $column => $values) {
                if (!$this->hasColumn($column)) {
                    throw new \OutOfBoundsException(sprintf('Column "%s" does not exist in table.', $column));
                }
                $column = $this->getColumn($column);
                $column->bindData($values, $objcet);
            }
        }

        $event = new TableEvent($this, $data);
        $this->eventDispatcher->dispatch(TableEvents::POST_BIND_DATA, $event);
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

    public function createView()
    {
        $view = new TableView($this->name, $this->rowset);

        foreach ($this->columns as $name => $column) {
            $view->addColumn($column);
        }

        $extensions = $this->tableFactory->getExtensions();

        foreach ($extensions as $extension) {
            $extension->buildView($view, $this);
        }

        return $view;
    }

    /**
     * Register all listeners from table extensions.
     */
    private function registerListeners()
    {
        $extensions = $this->tableFactory->getExtensions();

        foreach ($extensions as $extension) {
            $extension->registerListeners($this);
        }
    }
}