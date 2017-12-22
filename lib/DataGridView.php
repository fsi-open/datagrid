<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid;

use FSi\Component\DataGrid\Column\ColumnInterface;
use FSi\Component\DataGrid\Data\DataRowsetInterface;
use FSi\Component\DataGrid\Column\HeaderViewInterface;
use RuntimeException;

class DataGridView implements DataGridViewInterface
{
    /**
     * @var ColumnInterface[]
     */
    private $columns = [];

    /**
     * @var HeaderViewInterface[]
     */
    private $columnsHeaders = [];

    /**
     * @var string
     */
    private $name;

    /**
     * @var DataRowsetInterface
     */
    private $rowset;

    /**
     * @param string $name
     * @param ColumnInterface[] $columns
     * @param DataRowsetInterface $rowset
     */
    public function __construct(string $name, array $columns = [], DataRowsetInterface $rowset)
    {
        foreach ($columns as $column) {
            if (!$column instanceof ColumnInterface) {
                throw new \InvalidArgumentException(sprintf(
                    'Column must implement "%s", but got "%s"',
                    ColumnInterface::class,
                    is_object($column) ? get_class($column) : gettype($column)
                ));
            }

            $this->columns[$column->getName()] = $column;
            $this->columnsHeaders[$column->getName()] = $column->getDataGrid()->getFactory()->createHeaderView($column);
        }

        $this->name = $name;
        $this->rowset = $rowset;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getHeaders(): array
    {
        return $this->columnsHeaders;
    }

    public function count(): int
    {
        return $this->rowset->count();
    }

    public function current(): DataGridRowViewInterface
    {
        $index = $this->rowset->key();

        return new DataGridRowView($this->columns, $index, $this->rowset->current());
    }

    public function key()
    {
        return $this->rowset->key();
    }

    public function next(): void
    {
        $this->rowset->next();
    }

    public function rewind(): void
    {
        $this->rowset->rewind();
    }

    public function valid(): bool
    {
        return $this->rowset->valid();
    }

    public function offsetExists($offset): bool
    {
        return isset($this->rowset[$offset]);
    }

    public function offsetGet($offset): DataGridRowViewInterface
    {
        if ($this->offsetExists($offset)) {
            return new DataGridRowView($this->columns, $offset, $this->rowset[$offset]);
        }

        throw new \InvalidArgumentException(sprintf('Row "%s" does not exist in rowset.', $offset));
    }

    public function offsetSet($offset, $value): void
    {
        throw new RuntimeException('Method not implemented');
    }

    public function offsetUnset($offset): void
    {
        throw new RuntimeException('Method not implemented');
    }
}
