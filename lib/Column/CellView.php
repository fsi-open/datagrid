<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Column;

use FSi\Component\DataGrid\DataGridViewInterface;

class CellView implements CellViewInterface
{
    /**
     * The original object from which the value of the cell was retrieved.
     *
     * @var mixed
     */
    protected $source;

    /**
     * Cell value. In most cases this should be a simple string.
     *
     * @var mixed
     */
    protected $value;

    /**
     * Cell attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Cell name.
     *
     * @var string
     */
    protected $name;

    /**
     * Cell type.
     *
     * @var string
     */
    protected $type;

    /**
     * @var DataGridViewInterface
     */
    protected $datagrid;

    public function __construct(string $name, string $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setValue($value): void
    {
        $this->value = $value;
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

    public function setSource($source): void
    {
        $this->source = $source;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function setDataGridView(DataGridViewInterface $dataGrid): void
    {
        $this->datagrid = $dataGrid;
    }

    public function getDataGridView(): DataGridViewInterface
    {
        return $this->datagrid;
    }
}
