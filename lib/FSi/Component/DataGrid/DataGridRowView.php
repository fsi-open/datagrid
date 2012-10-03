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

use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\Exception\UnexpectedTypeException;

class DataGridRowView implements DataGridRowViewInterface
{
    
    /**
     * Cells views. 
     * @var unknown_type
     */
    protected $columnViews = array();
    
    /**
     * Columns objects used to create each cell view
     * @var array
     */
    protected $columns = array();

    /**
     * The source object for which view is created
     * @var mixed
     */
    protected $source;

    /**
     * @var integer
     */
    protected $count = 0;

    /**
     * @var integer
     */
    protected $position;
    
    protected $index;
    
    public function __construct(array $columns, $source, $index)
    {    
        $this->count = count($columns);
        $this->source = $source;
        $this->index = $index;
        $this->columns = $columns;
        foreach ($this->columns as $name => $column) {
            if (!($column instanceof ColumnTypeInterface)) {
                throw new UnexpectedTypeException($column, 'FSi\Component\DataGrid\Column\ColumnTypeInterface');
            }

            $this->columnViews[$name] = $column->createView($this->source, $index);
            if (!isset($this->position))
                $this->position = $name;
        }
    }

    public function getIndex()
    {
        return $this->index;
    }

    public function seek($position)
    {
        if (!isset($this->columnViews[$position])) {
            throw new \OutOfBoundsException(sprintf('Illegal index "%d%"', $position));
        }

        $this->position = $position;
        return $this;
    }

    /**
     * Returns the number of columns in the row.
     *
     * Implements Countable::count()
     *
     * @return int
     */
    public function count()
    {
        return $this->count;
    }

    /**
     * Return the current column view.
     * Similar to the current() function for arrays in PHP
     * Required by interface Iterator.
     *
     * @return DataGridColumnView current element from the rowset
     */
    public function current()
    {
        return $this->columnViews[$this->position];
    }

    /**
     * Return the identifying key of the current column.
     * Similar to the key() function for arrays in PHP.
     * Required by interface Iterator.
     *
     * @return string
     */
    public function key()
    {
        $key = key($this->columnViews);
        return $key;
    }

    /**
     * Move forward to next column.
     * Similar to the next() function for arrays in PHP.
     * Required by interface Iterator.
     *
     * @return void
     */
    public function next()
    {
        next($this->columnViews);
        $key = key($this->columnViews);
        $this->position = $key;
        return $this;
    }

    /**
     * Rewind the Iterator to the first element.
     * Similar to the reset() function for arrays in PHP.
     * Required by interface Iterator.
     *
     * @return DataGridRowView
     */
    public function rewind()
    {
        reset($this->columnViews);
        $key = key($this->columnViews);
        $this->position = $key;
        return $this;
    }

    /**
     * Checks if current position is valid
     * Required by the SeekableIterator implementation
     */
    public function  valid()
    {
        if (current($this->columnViews) === false) {
            return false;
        }

        return true;
    }
    
    /**
     * Required by the ArrayAccess implementation
     * @param string $offset
     */
    public function offsetExists($offset)
    {
        return isset($this->columnViews[$offset]);
    }
    
    /**
     * Required by the ArrayAccess implementation
     * @param string $offset
     * @return mixed false|ColumnTypeInterface
     */
    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset))
            return $this->columnViews[$offset];
            
        throw new \InvalidArgumentException(sprintf('Column "%s" does not exist in row.', $offset));
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
}