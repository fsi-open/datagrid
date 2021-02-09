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
use FSi\Component\DataGrid\DataMapper\DataMapperInterface;
use FSi\Component\DataGrid\Exception\DataGridColumnException;
use FSi\Component\DataGrid\Exception\UnexpectedTypeException;
use FSi\Component\DataGrid\Exception\UnknownOptionException;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class ColumnAbstractType implements ColumnTypeInterface
{
    /**
     * @var ColumnTypeExtensionInterface[]
     */
    protected $extensions = [];

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var string
     */
    protected $name;

    /**
     * This property is used when creating column view.
     * After ColumnView is created it is set to null.
     *
     * @var null|string
     */
    protected $index;

    /**
     * @var DataMapperInterface
     */
    protected $dataMapper;

    /**
     * @var DataGridInterface
     */
    protected $dataGrid;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    public function getName(): string
    {
        if (null === $this->name) {
            throw new DataGridColumnException('Use setName method to define column name in data grid');
        }

        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setDataGrid(DataGridInterface $dataGrid): void
    {
        $this->dataGrid = $dataGrid;
    }

    public function getDataGrid(): DataGridInterface
    {
        return $this->dataGrid;
    }

    public function setDataMapper(DataMapperInterface $dataMapper): void
    {
        $this->dataMapper = $dataMapper;
    }

    public function getDataMapper(): DataMapperInterface
    {
        if (null === $this->dataMapper) {
            $this->setDataMapper($this->dataGrid->getDataMapper());
        }

        return $this->dataMapper;
    }

    public function getValue($object)
    {
        $values = [];
        if (!$this->hasOption('field_mapping') || !count($this->getOption('field_mapping'))) {
            throw new DataGridColumnException(
                sprintf('"field_mapping" option is missing in column "%s"', $this->getName())
            );
        }

        foreach ($this->getOption('field_mapping') as $field) {
            $values[$field] = $this->getDataMapper()->getData($field, $object);
        }

        return $values;
    }

    public function createCellView($object, $index): CellViewInterface
    {
        $this->setIndex($index);

        $view = new CellView($this->getName(), $this->getId());
        $view->setSource($object);
        $view->setAttribute('row', $index);
        $dataMapper = $this->getDataMapper();

        if (!$dataMapper instanceof DataMapperInterface) {
            throw new UnexpectedTypeException($dataMapper, DataMapperInterface::class);
        }

        $values = $this->getValue($object);

        foreach ($this->getExtensions() as $extension) {
            $values = $extension->filterValue($this, $values);
        }

        $value = $this->filterValue($values);
        $view->setValue($value);

        foreach ($this->getExtensions() as $extension) {
            $extension->buildCellView($this, $view);
        }

        $this->buildCellView($view);
        $this->setIndex(null);

        return $view;
    }

    public function buildCellView(CellViewInterface $view): void
    {
    }

    public function createHeaderView(): HeaderViewInterface
    {
        $view = new HeaderView($this->getName(), $this->getId());

        foreach ($this->getExtensions() as $extension) {
            $extension->buildHeaderView($this, $view);
        }

        $this->buildHeaderView($view);

        return $view;
    }

    public function buildHeaderView(HeaderViewInterface $view): void
    {
    }

    public function setOption(string $name, $value): void
    {
        $this->options = $this->getOptionsResolver()->resolve(array_merge(
            is_array($this->options)
                ? $this->options
                : [],
            [$name => $value]
        ));
    }

    public function setOptions(array $options): void
    {
        $this->options = $this->getOptionsResolver()->resolve($options);
    }

    public function getOption(string $name)
    {
        if (!array_key_exists($name, $this->options)) {
            throw new UnknownOptionException(
                sprintf('Option "%s" is not available in column type "%s".', $name, $this->getId())
            );
        }

        return $this->options[$name];
    }

    public function hasOption(string $name): bool
    {
        return array_key_exists($name, $this->options);
    }

    public function bindData($data, $object, $index): void
    {
        foreach ($this->extensions as $extension) {
            $extension->bindData($this, $data, $object, $index);
        }
    }

    public function setExtensions(array $extensions): void
    {
        foreach ($extensions as $extension) {
            if (!$extension instanceof ColumnTypeExtensionInterface) {
                throw new UnexpectedTypeException($extension, ColumnTypeExtensionInterface::class);
            }
        }

        $this->extensions = $extensions;
    }

    public function addExtension(ColumnTypeExtensionInterface $extension): void
    {
        $this->extensions[] = $extension;
    }

    public function getExtensions(): array
    {
        return $this->extensions;
    }

    public function getOptionsResolver(): OptionsResolver
    {
        if (null === $this->optionsResolver) {
            $this->optionsResolver = new OptionsResolver();
        }

        return $this->optionsResolver;
    }

    public function initOptions(): void
    {
    }

    /**
     * @param int|string|null $index
     * @return void
     */
    protected function setIndex($index): void
    {
        $this->index = $index;
    }

    /**
     * @return int|string|null
     */
    protected function getIndex()
    {
        return $this->index;
    }
}
