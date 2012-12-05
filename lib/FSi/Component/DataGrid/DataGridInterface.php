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

interface DataGridInterface
{
    /**
     * Get DataGrid name. Name must be unique inside of factory.
     * @return string
     */
    public function getName();

    /**
     * Return data mapper.
     *
     * @return DataMapper
     */
    public function getDataMapper();

    /**
     * Return Indexing Strategy
     *
     * @return IndexingStrategy
     */
    public function getIndexingStrategy();

    /**
     * Add new column to DataGrid. Remeber that column type must be registred in
     * DataGridFactory that was used to create current DataGrid.
     *
     * @param string|ColumnTypeInterface $name
     * @param string $type
     * @param array $options
     * @return DataGridInterface
     */
    public function addColumn($name, $type = 'text', $options = array());

    /**
     * Remove column from DataGrdid.
     *
     * @param string $name
     * @throws InvalidArgumentException when column with $name not exists in grid.
     */
    public function removeColumn($name);

    /**
     * Remove all columns from DataGrid
     */
    public function clearColumns();

    /**
     * Return column with $name
     *
     * @throws InvalidArgumentException when column with $name not exists in grid.
     * @return ColumnTypeInterface
     */
    public function getColumn($name);

    /**
     * Return all registred columns in grid.
     *
     * @return array
     */
    public function getColumns();

    /**
     * Checks if column was added to grid.
     *
     * @param string $name
     * @return boolean
     */
    public function hasColumn($name);

    /**
     * Create DataGridView object that should be used to render data grid.
     */
    public function createView();

    /**
     * Set data collection. This method is different from bind data and
     * should not be used to update date.
     * Data should be passed as array or object that implements
     * \ArrayAccess, \Countable and \IteratorAggregate interfaces.
     * @param array $data
     */
    public function setData($data);

    /**
     * This method should be used only to update already set data.
     * @param mixed $data
     */
    public function bindData($data);
}
