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

use FSi\Component\DataGrid\DataGridViewInterface;

class DataGridAbstractView implements DataGridViewInterface
{
    /**
     * @var integer
     */
    protected $position;

    /**
     * @var integer
     */
    protected $count = 0;

    /**
     * Take the Iterator to position $position
     * Required by interface SeekableIterator.
     *
     * @param int $position the position to seek to
     */
    public function seek($position)
    {
        $position = (int)$position;
        if ($position < 0 || $position >= $this->count()) {
            throw new \OutOfBoundsException(sprintf('Illegal index "%d%"', $position));
        }
        $this->position = $position;
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
        return $this->count;
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
        if ($this->valid() === false) {
            return null;
        }
        $index = $this->rowset->getRowIndex($this->position);
        return new DataGridRowView($this->columns, $this->rowset[$this->position], $index);
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
        return $this->position;
    }

    public function index()
    {
        return $this->rowset->getRowIndex($this->position);;
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
        $this->position++;
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
        $this->position = 0;
        return $this;
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
        return $this->position >= 0 && $this->position < $this->count;
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
        return isset($this->rowset[(int)$offset]);
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
            return new DataGridRowView($this->columns, $this->rowset[$offset]);
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
}
