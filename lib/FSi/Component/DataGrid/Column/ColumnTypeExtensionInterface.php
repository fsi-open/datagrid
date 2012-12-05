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
use FSi\Component\DataGrid\Column\ColumnViewInterface;

interface ColumnTypeExtensionInterface
{
    public function setDataGrid(DataGridInterface $dataGrid);

    public function bindData(ColumnTypeInterface $column, $data, $object, $index);

    public function buildView(ColumnTypeInterface $column, ColumnViewInterface $view);

    public function filterValue(ColumnTypeInterface $column, $value);

    /**
     * Return required options for all column types options registred in extension.
     */
    public function getRequiredOptions(ColumnTypeInterface $column);

    /**
     * Return available options for all column types options registred in extension.
     */
    public function getAvailableOptions(ColumnTypeInterface $column);

    /**
     * Return default values for all column types options registred in extension.
     */
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