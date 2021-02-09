<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid;

use FSi\Component\DataGrid\Data\DataRowsetInterface;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\Column\HeaderViewInterface;
use InvalidArgumentException;
use RuntimeException;

class DataGridView implements DataGridViewInterface
{
    /**
     * @var ColumnTypeInterface[]
     */
    protected $columns = [];

    /**
     * @var HeaderViewInterface[]
     */
    protected $columnsHeaders = [];

    /**
     * @var string
     */
    protected $name;

    /**
     * @var DataRowsetInterface
     */
    protected $rowset;

    /**
     * @param string $name
     * @param ColumnTypeInterface[] $columns
     * @param DataRowsetInterface $rowset
     * @throws InvalidArgumentException
     */
    public function __construct(string $name, array $columns, DataRowsetInterface $rowset)
    {
        foreach ($columns as $column) {
            if (!$column instanceof ColumnTypeInterface) {
                throw new InvalidArgumentException(sprintf('Column must implement %s', ColumnTypeInterface::class));
            }

            $this->columns[$column->getName()] = $column;
            $headerView = $column->createHeaderView();
            $headerView->setDataGridView($this);
            $this->columnsHeaders[$column->getName()] = $headerView;
        }

        $this->name = $name;
        $this->rowset = $rowset;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function hasColumn(string $name): bool
    {
        return array_key_exists($name, $this->columnsHeaders);
    }

    public function hasColumnType(string $type): bool
    {
        foreach ($this->columnsHeaders as $header) {
            if ($header->getType() === $type) {
                return true;
            }
        }

        return false;
    }

    public function removeColumn(string $name): void
    {
        if (array_key_exists($name, $this->columnsHeaders)) {
            unset($this->columnsHeaders[$name]);
        }
    }

    public function getColumn(string $name): HeaderViewInterface
    {
        if ($this->hasColumn($name)) {
            return $this->columnsHeaders[$name];
        }

        throw new InvalidArgumentException(sprintf('Column "%s" does not exist in data grid.', $name));
    }

    public function getColumns(): array
    {
        return $this->columnsHeaders;
    }

    public function clearColumns(): void
    {
        $this->columnsHeaders = [];
    }

    public function addColumn(HeaderViewInterface $column): void
    {
        if (!array_key_exists($column->getName(), $this->columns)) {
            throw new InvalidArgumentException(sprintf(
                'Column with name "%s" was never registred in datagrid "%s"',
                $column->getName(),
                $this->getName()
            ));
        }

        $this->columnsHeaders[$column->getName()] = $column;
    }

    public function setColumns(array $columns): void
    {
        $this->columnsHeaders = [];

        foreach ($columns as $column) {
            if (!$column instanceof HeaderViewInterface) {
                throw new InvalidArgumentException(sprintf('Column must implement %s', HeaderViewInterface::class));
            }

            if (!array_key_exists($column->getName(), $this->columns)) {
                throw new InvalidArgumentException(sprintf(
                    'Column with name "%s" was never registred in datagrid "%s"',
                    $column->getName(),
                    $this->getName()
                ));
            }

            $this->columnsHeaders[$column->getName()] = $column;
        }
    }

    public function count(): int
    {
        return $this->rowset->count();
    }

    /**
     * @return string[]
     */
    public function getIndexes(): array
    {
        $indexes = [];
        foreach ($this->rowset as $index => $row) {
            $indexes[] = $index;
        }

        return $indexes;
    }

    public function current(): DataGridRowViewInterface
    {
        $index = $this->rowset->key();

        return new DataGridRowView($this, $this->getOriginColumns(), $this->rowset->current(), $index);
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

    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset)) {
            return new DataGridRowView($this, $this->getOriginColumns(), $this->rowset[$offset], $offset);
        }

        throw new InvalidArgumentException(sprintf('Row "%s" does not exist in rowset.', $offset));
    }

    public function offsetSet($offset, $value): void
    {
        throw new RuntimeException('Method not implemented');
    }

    public function offsetUnset($offset): void
    {
        throw new RuntimeException('Method not implemented');
    }

    /**
     * @return ColumnTypeInterface[]
     */
    protected function getOriginColumns(): array
    {
        $columns = [];
        foreach ($this->columnsHeaders as $name => $header) {
            $columns[$name] = $this->columns[$name];
        }

        return $columns;
    }
}
