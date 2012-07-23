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

use FSi\Component\Table\TableInterface;
use FSi\Component\Table\TableExtensionInterface;
use FSi\Component\Table\TableViewInterface;
use FSi\Component\Table\Column\ColumnTypeInterface;
use FSi\Component\Table\Column\ColumnTypeExtensionInterface;
use FSi\Component\Table\Exception\UnexpectedTypeException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Register extensions and create Form Builder
 * 
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 *
 */
abstract class TableAbstractExtension implements TableExtensionInterface
{
    /**
     * All column types extensions provided by table extension
     * @var array
     */
    protected $columnTypesExtensions;
    
    /**
     * All column types provided by extension
     * @var array
     */
    protected $columnTypes;

    /**
     * Returns a column type by id (all column types mush have unique id).
     *
     * @param string $id The identity of the column type
     *
     * @return ColumnTypeInterface The column type
     *
     * @throws TableException if the given column type is not a part of this extension
     */
    public function getColumnType($type)
    {
        if (!isset($this->columnTypes)) {
            $this->initColumnTypes();
        }

        if (!isset($this->columnTypes[$type])) {
            throw new TableException(sprintf('The column type "%s" can not be loaded by this extension', $type));
        }

        return $this->columnTypes[$type];
    }

    public function hasColumnType($type)
    {
        if (!isset($this->columnTypes)) {
            $this->initColumnTypes();
        }

        return isset($this->columnTypes[$type]);
    }

    public function hasColumnTypeExtensions($type)
    {
        if (!isset($this->columnTypesExtensions)) {
            $this->initColumnTypesExtensions();
        }
        
        return isset($this->columnTypesExtensions[$type]);
    }

    public function getColumnTypeExtensions($type)
    {
        if (!isset($this->columnTypesExtensions)) {
            $this->initColumnTypesExtensions();
        }

        if (!isset($this->columnTypesExtensions[$type])) {
            throw new TableException(sprintf('Extension for column type "%s" can not be loaded by this table extension', $type));
        }

        return $this->columnTypesExtensions[$type];
    }
    
    public function registerListeners(TableInterface $table)
    {
        $listeners = $this->loadListeners();
        if (!is_array($listeners))
            throw new UnexpectedTypeException($listeners, 'array');
            
        foreach ($listeners as $listener) {
            if (!($listener instanceof EventSubscriberInterface)) {
                throw new UnexpectedTypeException($columnType, 'Symfony\Component\EventDispatcher\EventSubscriberInterface');
            }
            
            $table->addEventSubscriber($listener);
        }
    }

    public function buildView(TableViewInterface $view, TableInterface $tableFactory)
    {}

    /**
     * If extension needs to provide new column types this function
     * should be owerloaded in child class and return array of TableColumnTypeInterface 
     * instances 
     * 
     * @return array
     */
    protected function loadColumnTypes()
    {
        return array();
    }

    protected function loadListeners()
    {
        return array();
    }

    /**
     * If extension needs to provide new column types this function
     * should be owerloaded in child class and return array of TableColumnTypeInterface 
     * instances 
     * 
     * @return array
     */
    protected function loadColumnTypesExtensions()
    {
        return array();
    }
    
    private function initColumnTypes()
    {
        $this->columnTypes = array();

        $columnTypes = $this->loadColumnTypes();

        foreach ($columnTypes as $columnType) {
            if (!($columnType instanceof ColumnTypeInterface)) {
                throw new UnexpectedTypeException($columnType, 'FSi\Component\Table\Column\ColumnTypeInterface');
            }

            $this->columnTypes[$columnType->getId()] = $columnType;
        }
        /*
        $columnTypesExtensions = $this->loadColumnTypesExtensions();
        foreach ($columnTypesExtensions as $extension) {
            if (!($extension instanceof ColumnTypeExtensionInterface)) {
                throw new UnexpectedTypeException($extension, 'FSi\Component\Table\Column\ColumnTypeExtensionInterface');
            }
            
            $types = $extension->getExtendedColumnTypes();
            foreach ($types as $type) {
                if ($this->hasColumnType($type)) {
                    $this->getColumnType($type)->addExtension($extension);
                }
            }
           
        } 
        */
    }
    
    private function initColumnTypesExtensions()
    {
        $columnTypesExtensions = $this->loadColumnTypesExtensions();
        foreach ($columnTypesExtensions as $extension) {
            if (!($extension instanceof ColumnTypeExtensionInterface)) {
                throw new UnexpectedTypeException($extension, 'FSi\Component\Table\Column\ColumnTypeExtensionInterface');
            }
            
            $types = $extension->getExtendedColumnTypes();
            foreach ($types as $type) {
                if (!isset($this->columnTypesExtensions)) {
                    $this->columnTypesExtensions[$type] = array();
                }
                $this->columnTypesExtensions[$type][] = $extension;
            }
        }
    }
}