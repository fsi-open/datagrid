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
use FSi\Component\DataGrid\Column\CellViewInterface;
use FSi\Component\DataGrid\Column\HeaderViewInterface;
use FSi\Component\DataGrid\Column\ColumnAbstractTypeExtension;

class DefaultColumnOptionsExtension extends ColumnAbstractTypeExtension
{

    public function buildHeaderView(ColumnTypeInterface $column, HeaderViewInterface $view)
    {
        $view->setLabel($column->getOption('label'));
        $view->setAttribute('order', $column->getOption('order'));
    }

    public function getExtendedColumnTypes()
    {
        return array(
            'text',
            'boolean',
            'datetime',
            'number',
            'money',
            'gedmo.tree',
            'entity',
            'action'
        );
    }

    public function getDefaultOptionsValues(ColumnTypeInterface $column)
    {
        return array(
            'label' => $column->getName(),
            'mapping_fields' => array($column->getName()),
            'order' => 0
        );
    }

    public function getRequiredOptions(ColumnTypeInterface $column)
    {
        return array('mapping_fields');
    }

    public function getAvailableOptions(ColumnTypeInterface $column)
    {
        return array('label', 'mapping_fields', 'order');
    }
}