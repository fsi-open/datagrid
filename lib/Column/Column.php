<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Column;

use FSi\Component\DataGrid\DataGridInterface;

class Column implements ColumnInterface
{
    /**
     * @var DataGridInterface
     */
    private $dataGrid;

    /**
     * @var ColumnTypeInterface
     */
    private $type;

    /**
     * @var array
     */
    private $options;

    /**
     * @var string
     */
    private $name;

    public function __construct(DataGridInterface $dataGrid, ColumnTypeInterface $type, string $name, array $options)
    {
        $this->dataGrid = $dataGrid;
        $this->type = $type;
        $this->name = $name;
        $this->options = $options;
    }

    public function getType(): ColumnTypeInterface
    {
        return $this->type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDataGrid(): DataGridInterface
    {
        return $this->dataGrid;
    }

    public function getOption(string $name)
    {
        return $this->options[$name];
    }

    public function hasOption(string $name): bool
    {
        return array_key_exists($name, $this->options);
    }
}
