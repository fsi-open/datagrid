<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class ColumnAbstractTypeExtension implements ColumnTypeExtensionInterface
{
    public function bindData(ColumnInterface $column, $data, $object, $index): void
    {
    }

    public function buildCellView(ColumnInterface $column, CellViewInterface $view): void
    {
    }

    public function buildHeaderView(ColumnInterface $column, HeaderViewInterface $view): void
    {
    }

    public function initOptions(OptionsResolver $optionsResolver): void
    {
    }

    public function filterValue(ColumnInterface $column, $value)
    {
        return $value;
    }
}
