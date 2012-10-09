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

use FSi\Component\DataGrid\DataGridRowView;
use FSi\Component\DataGrid\Data\DataRowsetInterface;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\Exception\UnexpectedTypeException;

class DataGridView extends DataGridAbstractView
{
    /**
     * Unique data grid name.
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

    /**
     * Returns datagrid name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Check if column is registred in view.
     *
     * @param string $name
     * @return boolean
     */
    public function hasColumn($name)
    {
        return array_key_exists($name, $this->columns);
    }

    /**
     * Removes column from view.
     *
     * @param string $name
     */
    public function removeColumn($name)
    {
        if (isset($this->columns[$name])) {
            unset($this->columns[$name]);
            return true;
        }
        return false;
    }

    /**
     * Get column.
     *
     * @param string $name
     */
    public function getColumn($name)
    {
        if ($this->hasColumn($name))
            return $this->columns[$name];

        throw new \InvalidArgumentException(sprintf('Column "%s" does not exist in data grid.', $offset));
    }

    /**
     * Add new column to view.
     *
     * @param ColumnTypeInterface $column
     */
    public function addColumn(ColumnTypeInterface $column)
    {
        $this->columns[$column->getName()] = $column;
        return $this;
    }

    /**
     * Return all columns registred in view.
     *
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Remove all columns from view.
     */
    public function clearColumns()
    {
        $this->columns = array();
        return $this;
    }
}