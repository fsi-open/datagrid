<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\Table;

use FSi\Component\Table\TableInterface;
use FSi\Component\Table\TableViewInterface;

interface TableExtensionInterface 
{
    /**
     * Register listeners by extension
     * @param unknown_type $table
     */
    public function registerListeners(TableInterface $table);

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
     * Return extensions for column type provided by this table extension. 
     * 
     * @param string $type
     */
    public function getColumnTypeExtensions($type);

    /**
     * Build table view by adding new types. 
     * 
     * @param TableViewInterface $view
     */
    public function buildView(TableViewInterface $view, TableInterface $tableFactory);
    
}