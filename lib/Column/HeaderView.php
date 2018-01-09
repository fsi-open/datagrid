<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Column;

class HeaderView implements HeaderViewInterface
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
     * @var string
     */
    private $label;

    /**
     * @var array
     */
    private $attributes = [];

    public function __construct(ColumnInterface $column, ?string $label = null)
    {
        $this->dataGridName = $column->getDataGrid()->getName();
        $this->name = $column->getName();
        $this->type = $column->getType()->getId();
        $this->label = $label ?? $this->name;
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

    public function hasAttribute(string $name): bool
    {
        return array_key_exists($name, $this->attributes);
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getDataGridName(): string
    {
        return $this->dataGridName;
    }
}
