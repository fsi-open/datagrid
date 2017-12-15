<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Extension\Core\ColumnType;

use FSi\Component\DataGrid\Column\ColumnAbstractType;
use FSi\Component\DataGrid\Column\CellViewInterface;
use FSi\Component\DataGrid\Column\HeaderViewInterface;

class Batch extends ColumnAbstractType
{
    public function getId(): string
    {
        return 'batch';
    }

    public function filterValue($value)
    {
        return $this->getIndex();
    }

    public function getValue($object)
    {
        return null;
    }

    public function buildCellView(CellViewInterface $view): void
    {
        $view->setAttribute('datagrid_name', $this->getDataGrid()->getName());
    }

    public function buildHeaderView(HeaderViewInterface $view): void
    {
        $view->setAttribute('datagrid_name', $this->getDataGrid()->getName());
    }
}
