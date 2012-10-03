<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension;

use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\Column\ColumnViewInterface;
use FSi\Component\DataGrid\Column\ColumnAbstractTypeExtension;
use FSi\Component\DataGrid\Exception\DataGridColumnException;

class DefaultColumnOptionsExtension extends ColumnAbstractTypeExtension 
{
    public function getExtendedColumnTypes()
    {
        return array(
            'text',
            'datetime',
            'number',
            'action',
            'grid',
            'money',
            'gedmo.tree'
        );
    }

    public function getDefaultOptionsValues(ColumnTypeInterface $column)
    {
        return array(
            'label' => $column->getName(),
            'mapping_fields' => array($column->getName()),
            'glue' => ' '
        );
    }

    public function getRequiredOptions(ColumnTypeInterface $column)
    {
        return array('mapping_fields', 'glue');
    }

    public function getAvailableOptions(ColumnTypeInterface $column)
    {
        return array('label', 'mapping_fields', 'glue');
    }
}