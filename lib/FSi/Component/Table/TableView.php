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

use FSi\Component\Table\TableRowView;
use FSi\Component\Table\Data\DataRowsetInterface;
use FSi\Component\Table\Column\ColumnTypeInterface;
use FSi\Component\Table\Exception\UnexpectedTypeException;

class TableView extends TableAbstractView
{
    /**
     * Unique table name.
     * @var string
     */
    protected $name;
    
    /**
     * @var DataRowsetInterface
     */
    protected $rowset;

    /**
     * @var array
     */
    protected $columns = array();

    public function __construct($name, DataRowsetInterface $rowset)
    {
        $this->name = $name;
        $this->position = 0;
        $this->rowset = $rowset;
        $this->count = $rowset->count();
    }

    public function getName()
    {
        return $this->name;
    }
    
    public function hasColumn($name)
    {
        return array_key_exists($name, $this->columns);    
    }

    public function removeColumn($name)
    {
        if (isset($this->columns[$name])) {
            unset($this->columns[$name]);
            return true;
        }
        return false;
    }

    public function getColumn($name)
    {
        if ($this->hasColumn($name))
            return $this->columns[$name];
            
        throw new \InvalidArgumentException(sprintf('Column "%s" does not exist in table.', $offset));
    }
    
    public function addColumn(ColumnTypeInterface $column)
    {
        $this->columns[$column->getName()] = $column;
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function clearColumns()
    {
        $this->columns = array();
        return $this;
    }
}