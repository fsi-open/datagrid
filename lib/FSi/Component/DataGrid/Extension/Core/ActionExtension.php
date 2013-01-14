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

class ActionExtension extends DataGridAbstractExtension
{
    /**
     * {@inheritdoc}
     */
    protected function loadColumnTypesExtensions()
    {
        return array(
            new ColumnTypeExtension\ActionColumnExtension()
        );
    }

}
