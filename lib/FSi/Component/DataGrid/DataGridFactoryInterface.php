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

interface DataGridFactoryInterface
{
    /**
     * Check if column is registered in factory. Column types can be registered
     * only by extensions.
     *
     * @param boolean $type
     */
    public function hasColumnType($type);

    /**
     * @throws UnexpectedTypeException if column is not registered in factory.
     * @param unknown_type $type
     */
    public function getColumnType($type);

    /**
     * Return all registered in factory DataGrid extensions as array.
     *
     * @return array
     */
    public function getExtensions();

    /**
     * Create data grid with unique name.
     *
     * @param string $name
     * @throws DataGridColumnException
     */
    public function createDataGrid($name = 'grid');

    /**
     * @return DataMapper\DataMapperInterface
     */
    public function getDataMapper();

    /**
     * @return IndexingStrategyInterface
     */
    public function getIndexingStrategy();
}
