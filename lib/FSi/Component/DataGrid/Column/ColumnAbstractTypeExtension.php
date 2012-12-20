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

use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\Column\CellViewInterface;
use FSi\Component\DataGrid\Column\HeaderViewInterface;
use FSi\Component\DataGrid\Column\ColumnTypeExtensionInterface;

abstract class ColumnAbstractTypeExtension implements ColumnTypeExtensionInterface
{
    public function setDataGrid(DataGridInterface $dataGrid)
    {}

    public function bindData(ColumnTypeInterface $column, $data, $object, $index)
    {}

    public function buildCellView(ColumnTypeInterface $column, CellViewInterface $view)
    {}

    public function buildHeaderView(ColumnTypeInterface $column, HeaderViewInterface $view)
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

    public function filterValue(ColumnTypeInterface $column, $value)
    {
        return $value;
    }
}