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
     * @var array
     */
    protected $cellViews = array();

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
        foreach ($columns as $name => $column) {
            if (!($column instanceof ColumnTypeInterface)) {
                throw new UnexpectedTypeException('Column object must implements FSi\Component\DataGrid\Column\ColumnTypeInterface');
            }

            $this->cellViews[$name] = $column->createCellView($this->source, $index);
            if (!isset($this->position)) {
                $this->position = $name;
            }
        }
    }

    public function getIndex()
    {
        return $this->index;
    }

    public function seek($position)
    {
        if (!isset($this->cellViews[$position])) {
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
     * @return DataGridCellView current element from the rowset
     */
    public function current()
    {
        return $this->cellViews[$this->position];
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
        $key = key($this->cellViews);
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
        next($this->cellViews);
        $key = key($this->cellViews);
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
        reset($this->cellViews);
        $key = key($this->cellViews);
        $this->position = $key;
        return $this;
    }

    /**
     * Checks if current position is valid
     * Required by the SeekableIterator implementation
     */
    public function  valid()
    {
        if (current($this->cellViews) === false) {
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
        return isset($this->cellViews[$offset]);
    }

    /**
     * Required by the ArrayAccess implementation
     * @param string $offset
     * @return mixed false|ColumnTypeInterface
     */
    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset)) {
            return $this->cellViews[$offset];
        }

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