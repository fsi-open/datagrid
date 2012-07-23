<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\Table\Column;

use FSi\Component\Table\TableInterface;
use FSi\Component\Table\Column\ColumnView;
use FSi\Component\Table\Column\ColumnTypeInterface;
use FSi\Component\Table\Column\ColumnTypeExtensionInterface;
use FSi\Component\Table\Exception\TableColumnException;
use FSi\Component\Table\DataMapper\DataMapperInterface;
use FSi\Component\Table\Exception\UnexpectedTypeException;
use FSi\Component\Table\Exception\UnknownOptionException;

abstract class ColumnAbstractType implements ColumnTypeInterface 
{
    protected $extensions = array();

    protected $options;

    protected $name;

    protected $dataMapper;

    protected $table;

    public function getName()
    {
        if (!isset($this->name))
            throw new TableColumnException('Use setName method to define column name in table');

        return $this->name;
    }

    public function setName($name)
    {
        $this->name = (string)$name;
        return $this;
    }

    public function setTable(TableInterface $table)
    {
        $this->table = $table;

        foreach ($this->extensions as $extension) {
            $extension->setTable($this->table);
        }
    }

    public function getDataMapper()
    {
        return $this->table->getDataMapper();
    }

    public function setOption($name, $value)
    {
        if (!isset($this->options)) {
            $this->loadAvailableOptions();
        }

       if (!array_key_exists($name, $this->options)) {
            throw new UnknownOptionException(sprintf('Option "%s" is not available in column type "%s".', $name, $this->getId()));
       }

        $this->options[$name] = $value;
        return $this;
    }

    public function getOption($name)
    {
        if (!isset($this->options)) {
            $this->loadAvailableOptions();
        }
        if (!array_key_exists($name, $this->options)) {
            throw new UnknownOptionException(sprintf('Option "%s" is not available in column type "%s".', $name, $this->getId()));
        }
 
        return $this->options[$name];
    }

    public function hasOption($name)
    {
        if (!isset($this->options)) {
            $this->loadAvailableOptions();
        }

        return array_key_exists($name, $this->options);
    }

    public function bindData($data, $object)
    {
        foreach ($this->extensions as $extension) {
        	$values = $extension->bindData($this, $data, $object);
        }
    }

    public function createView($object, $index)
    {
        $this->validateOptions();

        $view = new ColumnView();
        $view->setSource($object);
        $view->setAttribute('row', $index);
        $dataMapper = $this->getDataMapper();

        if (!($dataMapper instanceof DataMapperInterface)) {
            throw new UnexpectedTypeException($dataMapper, 'FSi\Component\Table\DataMapper\DataMapperInterface');
        }
        $values = array();
        foreach ($this->options['mapping_fields'] as $field) {
            $values[] = $dataMapper->getData($field, $object);
        }

        foreach ($this->extensions as $extension) {
            $values = $extension->filterValue($values);
        }

        $value = $this->filterValue($values);

        $view->setValue($value);

        $this->buildView($view);

        foreach ($this->extensions as $extension) {
            $extension->buildView($this, $view);
        }

        return $view;
    }

    public function buildView(ColumnViewInterface $view)
    {}

    public function setExtensions(array $extensions)
    {
        foreach ($extensions as $extension) {
            if (!($extension instanceof ColumnTypeExtensionInterface)) {
                throw new UnexpectedTypeException($extension, 'FSi\Component\Table\Column\ColumnTypeExtensionInterface');
            }
        }

        $this->extensions = $extensions;
    }

    public function getExtensions()
    {
        return $this->extensions;
    }

    public function addExtension(ColumnTypeExtensionInterface $extension)
    {
        if (!($extension instanceof ColumnTypeExtensionInterface)) {
            throw new UnexpectedTypeException($extension, 'FSi\Component\Table\Column\ColumnTypeExtensionInterface');
        }

        $this->extensions[] = $extension;
        return $this;
    }

    /**
     * Method returns array of required by column type options names.
     * Required means not null option value. 
     *  
     */
    protected function getRequiredOptions()
    {
        return array();
    }

    /**
     * Method returns array of available options for column type.
     */
    protected function getAvailableOptions()
    {
        return array();
    }

    /**
     * Method return default options values for column type. 
     * Method should return array where key is option name and value 
     * is option value. 
     * Option must available in column to set default value.
     */
    protected function getDefaultOptionsValues()
    {
        return array();
    }

    private function loadAvailableOptions()
    {
        // Load options from column type
        $options = $this->getAvailableOptions();
        // Load options from column type extensions
        foreach ($this->extensions as $extension) {
            $options = array_unique(array_merge($options, $extension->getAvailableOptions($this)));
        }

        if (!is_array($options)) {
            throw new UnexpectedTypeException($options, 'array');
        }

        $this->options = array();

        // Set options values to null
        foreach ($options as $option) {
            $this->options[strtolower($option)] = null;
        }

        // Load options default values from colum type
        $defaultValues = $this->getDefaultOptionsValues();
        // Load options default values from colum type extensions
        foreach ($this->extensions as $extension) {
            $defaultValues = (array_merge($defaultValues, $extension->getDefaultOptionsValues($this))); 
        }

        if (!is_array($defaultValues)) {
            throw new UnexpectedTypeException($defaultValues, 'array');
        }

        // Set column default options values;
        foreach ($defaultValues as $option => $value) {
            $this->setOption($option, $value);
        }
    }

    /**
     * Check if required options values exists.
     * 
     */
    private function validateOptions()
    {
        $required = $this->getRequiredOptions();
        foreach ($this->extensions as $extension) {
            $required = array_unique(array_merge($required, $extension->getRequiredOptions($this)));
        }

        foreach ($required as $option) {
            if (!$this->hasOption($option)) {
                throw new TableColumnException(sprintf('Option "%s" is required in column "%s".', $option, $this->getId()));
            }

            $value = $this->getOption($option);
            if ($value === null) {
                throw new TableColumnException(sprintf('Option "%s" is required in column "%s" and cant be null.', $option, $this->getId()));
            }
        }
    }
}
