<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Data;

use FSi\Component\DataGrid\Data\IndexingStrategyInterface;
use FSi\Component\DataGrid\DataMapper\DataMapperInterface;

class DataRowset implements DataRowsetInterface
{
    protected $indexesSeparator = ':';

    protected $strategy;

    protected $dataMapper;

    protected $source;

    protected $count;

    protected $position = 0;

    protected $data = array();

    protected $indexes = array();

    protected $indexedData = array();

    public function __construct(IndexingStrategyInterface $strategy, DataMapperInterface $dataMapper)
    {
        $this->strategy = $strategy;
        $this->dataMapper = $dataMapper;
    }

    public function setData($data)
    {
        if (!is_array($data)) {
            if (!($data instanceof \Traversable)) {
                throw new \InvalidArgumentException('array or Iterator is expected as data.');
            }
        }

        $this->source = $data;

        foreach ($data as $object) {
            $index = $this->getIndex($object);
            $this->indexes[] = $index;
            $this->data[] = $object;
            $this->indexedData[$index] = $object;
        }

        $this->count = count($this->data);

        return $this;
    }

    public function getRowIndex($row)
    {
        if ($this->offsetExists($row)) {
            return $this->indexes[$row];
        }
        return null;
    }

    public function hasObjectWithIndex($index)
    {
        return isset($this->indexedData[$index]);
    }

    public function getObjectByIndex($index)
    {
        if (!isset($this->indexedData[$index])) {
            throw new \OutOfBoundsException(sprintf('Illegal index "%d"', $index));
        }
        return $this->indexedData[$index];
    }

    /**
     * Sets indexes separator. This variable is used only when
     * object is indexed by many values. For example if we have object user
     * with indexes on fields email and username indexesSeparator will be used
     * to create one index for datagrid.
     *
     * $index = 'email' . $this->indexesSeparator . 'username';
     *
     * @param string $separator
     */
    public function setIndexesSeparator($separator)
    {
        $this->indexesSeparator = (string)$separator;
        return $this;
    }

    /**
     * Return rowsets count.
     * Required by interface Countable.
     *
     * @return DataGridView
     */
    public function count()
    {
        return $this->count;
    }

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

        return $this->data[$this->position];
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

    /**
     * Move forward to next element.
     * Similar to the next() function for arrays in PHP.
     * Required by interface Iterator.
     *
     * @return void
     */
    public function next()
    {
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
        return isset($this->data[(int)$offset]);
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
            return $this->data[$offset];
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

    protected function getIndex($object)
    {
        $identifiers = $this->strategy->getIndex($object);

        if (!is_array($identifiers)) {
            throw new \RuntimeException('Indexing strategy can\'t resolve the index from object.');
        }

        $indexes = array();
        foreach ($identifiers as $identifier) {
            $indexes[] = $this->dataMapper->getData($identifier, $object);
        }

        return implode($this->indexesSeparator, $indexes);
    }
}