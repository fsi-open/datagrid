<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Column;

interface ColumnTypeExtensionInterface
{
    public function bindData(ColumnTypeInterface $column, $data, $object, $index): void;

    public function buildCellView(ColumnTypeInterface $column, CellViewInterface $view): void;

    public function buildHeaderView(ColumnTypeInterface $column, HeaderViewInterface $view): void;

    public function filterValue(ColumnTypeInterface $column, $value);

    public function initOptions(ColumnTypeInterface $column);

    /**
     * @return string[]
     */
    public function getExtendedColumnTypes(): array;
}
