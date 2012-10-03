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

use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataGrid\DataGridViewInterface;

interface DataGridExtensionInterface 
{
    /**
     * Register event subscribers
     * @param unknown_type $dataGrid
     */
    public function registerSubscribers(DataGridInterface $dataGrid);

    /**
     * Check if extension has column type of $type
     * @param unknown_type $type
     */
    public function hasColumnType($type);

    /**
     * Get column type
     * @param string $type
     */
    public function getColumnType($type);

    /**
     * Check if extension has any column type extension for colum of $type
     * @param string $type
     */
    public function hasColumnTypeExtensions($type);

    /**
     * Return extensions for column type provided by this data grid extension. 
     * 
     * @param string $type
     */
    public function getColumnTypeExtensions($type);

    /**
     * Build data grid view by adding new types. 
     * 
     * @param DataGridViewInterface $view
     */
    public function buildView(DataGridViewInterface $view, DataGridInterface $dataGridFactory);
    
}