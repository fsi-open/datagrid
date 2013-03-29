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

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

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
     * @deprecated This method is deprecated and it will removed in version 1.2
     * @return IndexingStrategy
     */
    public function getIndexingStrategy();

    /**
     * Add new column to DataGrid. Remember that column type must be registered in
     * DataGridFactory that was used to create current DataGrid.
     *
     * @param string|ColumnTypeInterface $name
     * @param string $type
     * @param array $options
     * @return DataGridInterface
     */
    public function addColumn($name, $type = 'text', $options = array());

    /**
     * Remove column from DataGrid.
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
     * Return all registered columns in grid.
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
     * Checks if column with specific type was added to grid.
     *
     * @param string $type
     * @return boolean
     */
    public function hasColumnType($type);

    /**
     * Create DataGridView object that should be used to render data grid.
     *
     * @return DataGridView
     */
    public function createView();

    /**
     * Set data collection. This method is different from bind data and
     * should not be used to update date.
     * Data should be passed as array or object that implements
     * \ArrayAccess, \Countable and \IteratorAggregate interfaces.
     *
     * @param array $data
     */
    public function setData($data);

    /**
     * This method should be used only to update already set data.
     *
     * @param mixed $data
     */
    public function bindData($data);

    public function addEventListener($eventName, $listener, $priority = 0);

    public function addEventSubscriber(EventSubscriberInterface $subscriber);
}
