<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid;

use FSi\Component\DataGrid\Column\CellViewInterface;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\Exception\UnexpectedTypeException;
use InvalidArgumentException;
use RuntimeException;

class DataGridRowView implements DataGridRowViewInterface
{
    /**
     * @var CellViewInterface[]
     */
    protected $cellViews = [];

    /**
     * @var mixed
     */
    protected $source;

    /**
     * @var int
     */
    protected $index;

    /**
     * @param DataGridViewInterface $dataGridView
     * @param ColumnTypeInterface[] $columns
     * @param mixed $source
     * @param int $index
     * @throws Exception\UnexpectedTypeException
     */
    public function __construct(DataGridViewInterface $dataGridView, array $columns, $source, $index)
    {
        $this->source = $source;
        $this->index = $index;
        foreach ($columns as $name => $column) {
            if (!$column instanceof ColumnTypeInterface) {
                throw new UnexpectedTypeException(sprintf(
                    'Column object must implement "%s"',
                    ColumnTypeInterface::class
                ));
            }

            $cellView = $column->createCellView($this->source, $index);
            $cellView->setDataGridView($dataGridView);

            $this->cellViews[$name] = $cellView;
        }
    }

    public function getIndex()
    {
        return $this->index;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function count(): int
    {
        return count($this->cellViews);
    }

    public function current(): CellViewInterface
    {
        return current($this->cellViews);
    }

    public function key(): ?string
    {
        return key($this->cellViews);
    }

    public function next(): void
    {
        next($this->cellViews);
    }

    public function rewind(): void
    {
        reset($this->cellViews);
    }

    public function valid(): bool
    {
        return $this->key() !== null;
    }

    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->cellViews);
    }

    public function offsetGet($offset): CellViewInterface
    {
        if ($this->offsetExists($offset)) {
            return $this->cellViews[$offset];
        }

        throw new InvalidArgumentException(sprintf('Column "%s" does not exist in row.', $offset));
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
