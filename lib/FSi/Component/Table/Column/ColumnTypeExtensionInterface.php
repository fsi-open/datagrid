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
use FSi\Component\Table\Column\ColumnTypeInterface;
use FSi\Component\Table\Column\ColumnViewInterface;

interface ColumnTypeExtensionInterface 
{
    public function setTable(TableInterface $table);
    
	public function bindData(ColumnTypeInterface $column, $data, $object);

    public function buildView(ColumnTypeInterface $column, ColumnViewInterface $view);

    public function filterValue($value);

    public function getRequiredOptions(ColumnTypeInterface $column);

    public function getAvailableOptions(ColumnTypeInterface $column);

    public function getDefaultOptionsValues(ColumnTypeInterface $column);

    /**
     * Return array with extended column types. 
     * Example return: 
     * 
     * return array(
     *     'text',
     *     'date_time'
     * );
     * 
     * Extensions will be loaded into columns text and date_time.
     */
    public function getExtendedColumnTypes();
}