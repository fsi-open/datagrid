<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid;

use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataGrid\DataGridExtensionInterface;
use FSi\Component\DataGrid\Exception\DataGridColumnException;
use FSi\Component\DataGrid\Exception\UnexpectedTypeException;
use FSi\Component\DataGrid\Data\IndexingStrategyInterface;
use FSi\Component\DataGrid\DataMapper\DataMapperInterface;

class DataGridFactory implements DataGridFactoryInterface
{
    /**
     * Already registred data grids.
     * @var array
     */
    protected $dataGrids = array();

    protected $columnTypes = array();

    /**
     * Instance of Data Mapper. This object allows you mapping data from collection
     * into selected column.
     *
     * @var DataMapperInterface
     */
    protected $dataMapper;

    /**
     * The DataGridExtensionInterface instances
     *
     * @var array
     */
    protected $extensions = array();

    protected $strategy;

    public function __construct(array $extensions, DataMapperInterface $dataMapper, IndexingStrategyInterface $strategy)
    {
        foreach ($extensions as $extension) {
            if (!($extension instanceof DataGridExtensionInterface)) {
                throw new UnexpectedTypeException($extension, 'FSi\Component\DataGrid\DataGridExtensionInterface');
            }
        }

        $this->dataMapper = $dataMapper;
        $this->strategy = $strategy;
        $this->extensions = $extensions;
    }

    /**
     * Create data grid with unique name.
     * @throws DataGridColumnException
     * @param string $name
     */
    public function createDataGrid($name = 'grid')
    {
        if (array_key_exists($name, $this->dataGrids))
            throw new DataGridColumnException(sprintf('Data grid name "%s" is not uniqe, it was used before to create form', $name));

        $this->dataGrids[$name] = true;

        return new DataGrid($name, $this, $this->dataMapper, $this->strategy);
    }

    /**
     * {@inheritDoc}
     */
    public function hasColumnType($type)
    {
        if (isset($this->columnTypes[$type])) {
            return true;
        }

        try {
            $this->loadColumnType($type);
        } catch (UnexpectedTypeException $e) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function getColumnType($type)
    {
        if (isset($this->columnTypes[$type])) {
            return clone $this->columnTypes[$type];
        }

        $this->loadColumnType($type);

        return clone $this->columnTypes[$type];
    }

    /**
     * {@inheritDoc}
     */
    public function getExtensions()
    {
        return $this->extensions;
    }

    public function getDataMapper()
    {
        return $this->dataMapper;
    }

    public function getIndexingStrategy()
    {
        return $this->strategy;
    }

    private function loadColumnType($type)
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

        if (!isset($typeInstance))
            throw new UnexpectedTypeException(sprintf('There is no column with type "%s" registred in factory.', $type));

        foreach ($this->extensions as $extension) {
            if ($extension->hasColumnTypeExtensions($type)) {
                $columnExtensions = $extension->getColumnTypeExtensions($type);
                foreach ($columnExtensions as $columnExtension) {
                    $typeInstance->addExtension($columnExtension);
                }
            }
        }

        $this->columnTypes[$type] = $typeInstance;
    }

}