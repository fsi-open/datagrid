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

use FSi\Component\DataGrid\Data\DataRowsetInterface;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\Column\HeaderViewInterface;

class DataGridView implements DataGridViewInterface
{
    /**
     * Original column objects passed from datagrid.
     * This array should be used only to call methods like createCellView or
     * createHeaderView
     *
     * @var array
     */
    protected $columns = array();

    /**
     * @var array
     */
    protected $columnsHeaders = array();

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
     * Constructs DataGridView, should be called only from DataGrid::createView method.
     *
     * @param string $name
     * @param array $columns
     * @param Data\DataRowsetInterface $rowset
     */
    public function __construct($name, array $columns = array(), DataRowsetInterface $rowset)
    {
        foreach ($columns as $column) {
            if (!($column instanceof ColumnTypeInterface)) {
                throw new \InvalidArgumentException('Column must implement FSi\Component\DataGrid\Column\ColumnTypeInterface');
            }

            $this->columns[$column->getName()] = $column;
            $this->columnsHeaders[$column->getName()] = $column->createHeaderView();
        }

        $this->name = $name;
        $this->rowset = $rowset;
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
     * Check if column is registered in view.
     *
     * @param string $name
     * @return boolean
     */
    public function hasColumn($name)
    {
        return array_key_exists($name, $this->columnsHeaders);
    }

    /**
     * Removes column from view.
     *
     * @param string $name
     */
    public function removeColumn($name)
    {
        if (isset($this->columnsHeaders[$name])) {
            unset($this->columnsHeaders[$name]);
            return true;
        }

        return false;
    }

    /**
     * Get column.
     *
     * @throw InvalidArgumentException
     * @param string $name
     */
    public function getColumn($name)
    {
        if ($this->hasColumn($name)) {
            return $this->columnsHeaders[$name];
        }

        throw new \InvalidArgumentException(sprintf('Column "%s" does not exist in data grid.', $name));
    }

    /**
     * Return all columns registered in view.
     *
     * @return array
     */
    public function getColumns()
    {
        return $this->columnsHeaders;
    }

    /**
     * Remove all columns from view.
     */
    public function clearColumns()
    {
        $this->columnsHeaders = array();
        return $this;
    }

    /**
     * Add new column to view.
     */
    public function addColumn(HeaderViewInterface $column)
    {
        if (!array_key_exists($column->getName(), $this->columns)) {
            throw new \InvalidArgumentException(sprintf('Column with name "%s" was never registred in datagrid ""', $column->getName(), $this->getName()));
        }

        $this->columnsHeaders[$column->getName()] = $column;
        return $this;
    }

    /**
     * Set new column list set to view.
     */
    public function setColumns(array $columns)
    {
        $this->columnsHeaders = array();

        foreach ($columns as $column) {
            if (!($column instanceof HeaderViewInterface)) {
                throw new \InvalidArgumentException('Column must implement FSi\Component\DataGrid\Column\HeaderViewInterface');
            }
            if (!array_key_exists($column->getName(), $this->columns)) {
                throw new \InvalidArgumentException(sprintf('Column with name "%s" was never registred in datagrid ""', $column->getName(), $this->getName()));
            }

            $this->columnsHeaders[$column->getName()] = $column;
        }

        return $this;
    }

    /**
     * Returns the number of elements in the collection.
     *
     * Implements Countable::count()
     *
     * @return int
     */
    public function count()
    {
        return $this->rowset->count();
    }

    /**
     * Return the current element.
     * Similar to the current() function for arrays in PHP
     * Required by interface Iterator.
     *
     * @return DataGridRowView current element from the rowset
     */
    public function current()
    {
        $index = $this->rowset->key();
        return new DataGridRowView($this->getOriginColumns(), $this->rowset->current(), $index);
    }

    /**
     * Return the identifying key of the current element.
     * Similar to the key() function for arrays in PHP.
     * Required by interface Iterator.
     *
     * @return int
     */
    public function key()
    {
        return $this->rowset->key();
    }

    /**
     * Move forward to next element.
     * Similar to the next() function for arrays in PHP.
     * Required by interface Iterator.
     *
     * @return void
     */
    public function next()
    {
        $this->rowset->next();
    }

    /**
     * Rewind the Iterator to the first element.
     * Similar to the reset() function for arrays in PHP.
     * Required by interface Iterator.
     *
     * @return DataGridView
     */
    public function rewind()
    {
        $this->rowset->rewind();
    }

    /**
     * Check if there is a current element after calls to rewind() or next().
     * Used to check if we've iterated to the end of the collection.
     * Required by interface Iterator.
     *
     * @return bool False if there's nothing more to iterate over
     */
    public function valid()
    {
        return $this->rowset->valid();
    }

    /**
     * Check if an offset exists
     * Required by the ArrayAccess implementation
     *
     * @param string $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->rowset[$offset]);
    }

    /**
     * Get the row for the given offset
     * Required by the ArrayAccess implementation
     *
     * @param int $offset
     * @return DataGridRowView
     */
    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset)) {
            return new DataGridRowView($this->getOriginColumns(), $this->rowset[$offset]);
        }

        throw new \InvalidArgumentException(sprintf('Row "%s" does not exist in rowset.', $offset));
    }

    /**
     * Does nothing
     * Required by the ArrayAccess implementation
     *
     * @param string $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
    }

    /**
     * Does nothing
     * Required by the ArrayAccess implementation
     *
     * @param string $offset
     */
    public function offsetUnset($offset)
    {
    }

    /**
     * Return the origin columns in order of columns headers.
     */
    private function getOriginColumns()
    {
        $columns = array();
        foreach ($this->columnsHeaders as $name => $header) {
            $columns[$name] = $this->columns[$name];
        }

        return $columns;
    }
}