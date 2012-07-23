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

use FSi\Component\Table\TableFactoryInterface;
use FSi\Component\Table\TableExtensionInterface;
use FSi\Component\Table\Exception\TableColumnException;
use FSi\Component\Table\Data\IndexingStrategyInterface;
use FSi\Component\Table\DataMapper\DataMapperInterface;

class TableFactory implements TableFactoryInterface
{
    /**
     * Already registred tables.
     * @var array
     */
    protected $tables = array();
    
    protected $columnTypes = array();

    /**
     * Instance of Data Mapper. This object allows you mapping data from collection
     * into selected column. 
     * 
     * @var DataMapperInterface
     */
    protected $dataMapper; 

    /**
     * The TableExtensionInterface instances
     * 
     * @var array
     */
    protected $extensions = array();
    
    protected $strategy;

    public function __construct(array $extensions, DataMapperInterface $dataMapper, IndexingStrategyInterface $strategy)
    {
        foreach ($extensions as $extension) {
            if (!($extension instanceof TableExtensionInterface)) {
                throw new UnexpectedTypeException($extension, 'FSi\Component\Table\TableExtensionInterface');
            }
        }

        $this->dataMapper = $dataMapper;
        $this->strategy = $strategy;
        $this->extensions = $extensions;
    }

    /**
     * Create table with unique name. 
     * @throws TableColumnException
     * @param unknown_type $name
     */
    public function createTable($name = 'table')
    {
        if (array_key_exists($name, $this->tables))
            throw new TableColumnException(sprintf('Table name "%s" is not uniqe, it was used before to create form', $name));
            
        $this->tables[$name] = true;
        
        return new Table($name, $this, $this->dataMapper, $this->strategy);        
    }

    public function hasColumnType($type)
    {
        if (isset($this->columnTypes[$type])) {
            return true;
        }

        try {
            $this->loadColumnType($type);
        } catch (FormException $e) {
            return false;
        }

        return true;
    }
    
    public function getColumnType($type)
    {
        if (isset($this->columnTypes[$type])) {
            return clone $this->columnTypes[$type];
        }

        $this->loadColumnType($type);

        return clone $this->columnTypes[$type];
    }    

    public function getDataMapper()
    {
        return $this->dataMapper;
    }
    
    public function getExtensions()
    {
        return $this->extensions;
    }
    
    private function loadColumnType($type)
    {
        if (isset($this->columnTypes[$type])) {
            return;
        }

        $typeInstance = null;
        foreach ($this->extensions as $extension) {
            if ($extension->hasColumnType($type)) {
                $typeInstance = $extension->getColumnType($type);
                break;
            }
        }

        if (!isset($typeInstance))
            throw new UnexpectedTypeException(printf('Could not load % type.', $type));

        foreach ($this->extensions as $extension) {
            if ($extension->hasColumnTypeExtensions($type)) {
                $columnExtensions = $extension->getColumnTypeExtensions($type);
                foreach ($columnExtensions as $columnExtension) {
                    $typeInstance->addExtension($columnExtension);
                }
            }
        }
        
        $this->columnTypes[$type] = $typeInstance;
    }

}