<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Extension\Core;

use FSi\Component\DataGrid\DataGridAbstractExtension;
use FSi\Component\DataGrid\Extension\Core\ColumnType;
use FSi\Component\DataGrid\Extension\Core\EventListener;
use FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension;

class CoreExtension extends DataGridAbstractExtension
{
    protected function loadColumnTypes()
    {
        return array(
            new ColumnType\Text(),
            new ColumnType\Number(),
            new ColumnType\DateTime(),
            new ColumnType\Action(),
            new ColumnType\Money()
        );
    }

    protected function loadColumnTypesExtensions()
    {
        return array(
            new ColumnTypeExtension\DefaultColumnOptionsExtension(),
            new ColumnTypeExtension\ActionColumnExtension()
        );
    }
}
