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

interface ColumnTypeExtensionInterface
{
    /**
     * @param DataGridInterface $dataGrid
     * @return mixed
     */
    public function setDataGrid(DataGridInterface $dataGrid);

    /**
     * @param ColumnTypeInterface $column
     * @param mixed $data
     * @param mixed $object
     * @param string $index
     */
    public function bindData(ColumnTypeInterface $column, $data, $object, $index);

    /**
     * @param ColumnTypeInterface $column
     * @param CellViewInterface $view
     */
    public function buildCellView(ColumnTypeInterface $column, CellViewInterface $view);

    /**
     * @param ColumnTypeInterface $column
     * @param HeaderViewInterface $view
     */
    public function buildHeaderView(ColumnTypeInterface $column, HeaderViewInterface $view);

    /**
     * @param ColumnTypeInterface $column
     * @param mixed $value
     * @return mixed
     */
    public function filterValue(ColumnTypeInterface $column, $value);

    /**
     * Return required options for all column types options registered in extension.
     *
     * return array(
     *     'trim',
     *     'empty_value'
     * );
     *
     * @return array
     */
    public function getRequiredOptions(ColumnTypeInterface $column);

    /**
     * Return available options for all column types options registered in extension.
     *
     * return array(
     *     'trim',
     *     'empty_value'
     * );
     *
     * @return array
     */
    public function getAvailableOptions(ColumnTypeInterface $column);

    /**
     * Return default values for all column types options registered in extension.
     * Example return:
     *
     * return array(
     *     'trim' => true,
     * );
     *
     * @return array
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
     *
     * @return array
     */
    public function getExtendedColumnTypes();
}
