<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid;

use FSi\Component\DataGrid\Column\CellView;
use FSi\Component\DataGrid\Column\CellViewInterface;
use FSi\Component\DataGrid\Column\Column;
use FSi\Component\DataGrid\Column\ColumnInterface;
use FSi\Component\DataGrid\Column\ColumnTypeExtensionInterface;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\Column\HeaderView;
use FSi\Component\DataGrid\Column\HeaderViewInterface;
use FSi\Component\DataGrid\Exception\DataGridColumnException;
use FSi\Component\DataGrid\Exception\UnexpectedTypeException;
use InvalidArgumentException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DataGridFactory implements DataGridFactoryInterface
{
    /**
     * @var DataGridInterface[]
     */
    protected $dataGrids = [];

    /**
     * @var ColumnTypeInterface[]
     */
    protected $columnTypes = [];

    /**
     * @var DataGridExtensionInterface[]
     */
    protected $extensions = [];

    /**
     * @param DataGridExtensionInterface[] $extensions
     * @throws InvalidArgumentException
     */
    public function __construct(array $extensions)
    {
        foreach ($extensions as $extension) {
            if (!$extension instanceof DataGridExtensionInterface) {
                throw new InvalidArgumentException(sprintf(
                    'Each extension must implement "%s"',
                    DataGridExtensionInterface::class
                ));
            }
        }

        $this->extensions = $extensions;
    }

    public function createDataGrid(string $name): DataGridInterface
    {
        if (array_key_exists($name, $this->dataGrids)) {
            throw new DataGridColumnException(sprintf(
                'Datagrid name "%s" is not uniqe, it was used before to create datagrid',
                $name
            ));
        }

        $this->dataGrids[$name] = new DataGrid($name, $this);

        foreach ($this->extensions as $extension) {
            $extension->registerSubscribers($this->dataGrids[$name]);
        }

        return $this->dataGrids[$name];
    }

    public function hasColumnType(string $type): bool
    {
        if (array_key_exists($type, $this->columnTypes)) {
            return true;
        }

        try {
            $this->loadColumnType($type);
        } catch (UnexpectedTypeException $e) {
            return false;
        }

        return true;
    }

    public function getColumnType(string $type): ColumnTypeInterface
    {
        if ($this->hasColumnType($type)) {
            return clone $this->columnTypes[$type];
        }

        $this->loadColumnType($type);

        return clone $this->columnTypes[$type];
    }

    public function createColumn(
        DataGridInterface $dataGrid,
        string $type,
        string $name,
        array $options
    ): ColumnInterface {
        $optionsResolver = new OptionsResolver();
        $optionsResolver->setRequired('name');
        $optionsResolver->setAllowedTypes('name', 'string');

        $columnType = $this->getColumnType($type);
        $columnType->initOptions($optionsResolver);
        foreach ($this->getColumnTypeExtensions($columnType) as $extension) {
            $extension->initOptions($optionsResolver);
        }

        return new Column(
            $dataGrid,
            $columnType,
            $name,
            $optionsResolver->resolve(array_merge(['name' => $name], $options))
        );
    }

    public function createCellView(ColumnInterface $column, $source): CellViewInterface
    {
        $columnType = $column->getType();
        $value = $columnType->filterValue($column, $columnType->getValue($column, $source));
        foreach ($this->getColumnTypeExtensions($columnType) as $extension) {
            $value = $extension->filterValue($column, $value);
        }

        $cellView = new CellView($column, $value);
        $columnType->buildCellView($column, $cellView);
        foreach ($this->getColumnTypeExtensions($columnType) as $extension) {
            $extension->buildCellView($column, $cellView);
        }

        return $cellView;
    }

    public function createHeaderView(ColumnInterface $column): HeaderViewInterface
    {
        $view = new HeaderView($column);

        $columnType = $column->getType();
        $columnType->buildHeaderView($column, $view);
        foreach ($this->getColumnTypeExtensions($columnType) as $extension) {
            $extension->buildHeaderView($column, $view);
        }

        return $view;
    }

    /**
     * @param ColumnTypeInterface $columnType
     * @return ColumnTypeExtensionInterface[]
     */
    public function getColumnTypeExtensions(ColumnTypeInterface $columnType): array
    {
        $extensions = [];
        foreach ($this->extensions as $extension) {
            if ($extension->hasColumnTypeExtensions($columnType)) {
                $extensions[] = $extension->getColumnTypeExtensions($columnType);
            }
        }

        if (empty($extensions)) {
            return [];
        }

        return array_merge(...$extensions);
    }

    /**
     * @param string $type
     * @throws UnexpectedTypeException
     */
    private function loadColumnType(string $type): void
    {
        if (isset($this->columnTypes[$type])) {
            return;
        }

        $typeInstance = null;
        foreach ($this->extensions as $extension) {
            if ($extension->hasColumnType($type)) {
                $typeInstance = $extension->getColumnType($type);
                break;
            }
        }

        if (null === $typeInstance) {
            throw new UnexpectedTypeException(sprintf(
                'There is no column with type "%s" registered in factory.',
                $type
            ));
        }

        $this->columnTypes[$type] = $typeInstance;
    }
}
