<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataGrid\Column\CellView;
use FSi\Component\DataGrid\Column\HeaderView;
use FSi\Component\DataGrid\Column\HeaderViewInterface;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\Column\ColumnTypeExtensionInterface;
use FSi\Component\DataGrid\Exception\DataGridColumnException;
use FSi\Component\DataGrid\DataMapper\DataMapperInterface;
use FSi\Component\DataGrid\Exception\UnexpectedTypeException;
use FSi\Component\DataGrid\Exception\UnknownOptionException;

abstract class ColumnAbstractType implements ColumnTypeInterface
{
    /**
     * @var array
     */
    protected $extensions = array();

    /**
     * @var array
     */
    protected $options;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var DataMapper
     */
    protected $dataMapper;

    /**
     * @var DataGrid
     */
    protected $dataGrid;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        if (!isset($this->name)) {
            throw new DataGridColumnException('Use setName method to define column name in data grid');
        }

        return $this->name;
    }

    /**
     * Set column registered name.
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = (string)$name;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setDataGrid(DataGridInterface $dataGrid)
    {
        $this->dataGrid = $dataGrid;

        foreach ($this->extensions as $extension) {
            $extension->setDataGrid($this->dataGrid);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDataGrid()
    {
        return $this->dataGrid;
    }

    /**
     * {@inheritdoc}
     */
    public function setDataMapper(DataMapperInterface $dataMapper)
    {
        $this->dataMapper = $dataMapper;
    }

    /**
     * {@inheritdoc}
     */
    public function getDataMapper()
    {
        if (!isset($this->dataMapper)) {
            $this->setDataMapper($this->dataGrid->getDataMapper());
        }
        return $this->dataMapper;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue($object)
    {
        $values = array();
        if (!$this->hasOption('mapping_fields') || !count($this->getOption('mapping_fields'))) {
            throw new DataGridColumnException(
                sprintf('"mapping_fields" option is missing in column "%s"', $this->getName())
            );
        }

        foreach ($this->getOption('mapping_fields') as $field) {
            $values[$field] = $this->getDataMapper()->getData($field, $object);
        }

        return $values;
    }

    /**
     * {@inheritdoc}
     */
    public function createCellView($object, $index)
    {
        $view = new CellView($this->getName(), $this->getId());
        $view->setSource($object);
        $view->setAttribute('row', $index);
        $dataMapper = $this->getDataMapper();

        if (!($dataMapper instanceof DataMapperInterface)) {
            throw new UnexpectedTypeException($dataMapper, 'FSi\Component\DataGrid\DataMapper\DataMapperInterface');
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

        return $view;
    }

    /**
     * {@inheritdoc}
     */
    public function buildCellView(CellViewInterface $view)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function createHeaderView()
    {
        $view = new HeaderView($this->getName(), $this->getId());

        foreach ($this->getExtensions() as $extension) {
            $extension->buildHeaderView($this, $view);
        }

        $this->buildHeaderView($view);

        return $view;
    }

    /**
     * {@inheritdoc}
     */
    public function buildHeaderView(HeaderViewInterface $view)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function setOption($name, $value)
    {
        $this->options = $this->getOptionsResolver()->resolve(array_merge(
            is_array($this->options)
                ? $this->options
                : array(),
            array($name => $value)
        ));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions($options)
    {
        $this->options = $this->getOptionsResolver()->resolve($options);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getOption($name)
    {
        if (!isset($this->options)) {
            $this->options = array();
        }

        if (!array_key_exists($name, $this->options)) {
            throw new UnknownOptionException(sprintf('Option "%s" is not available in column type "%s".', $name, $this->getId()));
        }

        return $this->options[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function hasOption($name)
    {
        return array_key_exists($name, $this->options);
    }

    /**
     * {@inheritdoc}
     */
    public function bindData($data, $object, $index)
    {
        foreach ($this->extensions as $extension) {
            $extension->bindData($this, $data, $object, $index);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setExtensions(array $extensions)
    {
        foreach ($extensions as $extension) {
            if (!($extension instanceof ColumnTypeExtensionInterface)) {
                throw new UnexpectedTypeException($extension, 'FSi\Component\DataGrid\Column\ColumnTypeExtensionInterface');
            }
        }

        $this->extensions = $extensions;
    }

    /**
     * {@inheritdoc}
     */
    public function addExtension(ColumnTypeExtensionInterface $extension)
    {
        if (!($extension instanceof ColumnTypeExtensionInterface)) {
            throw new UnexpectedTypeException('Column extension must implement FSi\Component\DataGrid\Column\ColumnTypeExtensionInterface');
        }

        $this->extensions[] = $extension;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensions()
    {
        return $this->extensions;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptionsResolver()
    {
        if (null === $this->optionsResolver) {
            $this->optionsResolver = new OptionsResolver();
        }

        return $this->optionsResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function initOptions()
    {
    }
}
