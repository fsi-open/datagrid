<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Column;

class CellView implements CellViewInterface
{
    /**
     * @var string
     */
    private $dataGridName;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $type;

    /**
     * @var int|string
     */
    private $index;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var array
     */
    private $attributes = [];

    /**
     * @param ColumnInterface $column
     * @param int|string $index
     * @param mixed $value
     */
    public function __construct(ColumnInterface $column, $index, $value)
    {
        $this->dataGridName = $column->getDataGrid()->getName();
        $this->name = $column->getName();
        $this->type = $column->getType()->getId();
        $this->index = $index;
        $this->value = $value;
    }

    public function getDataGridName(): string
    {
        return $this->dataGridName;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getIndex()
    {
        return $this->index;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setAttribute(string $name, $value): void
    {
        $this->attributes[$name] = $value;
    }

    public function getAttribute(string $name)
    {
        if (array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }

        return null;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function hasAttribute(string $name): bool
    {
        return array_key_exists($name, $this->attributes);
    }
}
