<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
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
     * Sets the default options for this type.
     *
     * @param OptionsResolverInterface $column.
     */
    public function initOptions(ColumnTypeInterface $column);

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
