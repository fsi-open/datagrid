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
use FSi\Component\Table\Column\ColumnTypeExtensionInterface;

abstract class ColumnAbstractTypeExtension implements ColumnTypeExtensionInterface 
{
	public function setTable(TableInterface $table)
	{}

	public function bindData(ColumnTypeInterface $column, $data, $object)
	{}

    public function buildView(ColumnTypeInterface $column, ColumnViewInterface $view)
    {}

    public function getRequiredOptions(ColumnTypeInterface $column)
    {
    	return array();
    }

    public function getAvailableOptions(ColumnTypeInterface $column)
    {
    	return array();
    }

    public function getDefaultOptionsValues(ColumnTypeInterface $column)
    {
    	return array();
    }

    public function filterValue($value)
    {
        return $value;
    }
}