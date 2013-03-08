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

    /**
     * {@inheritDoc}
     */
    public function buildHeaderView(ColumnTypeInterface $column, HeaderViewInterface $view)
    {
        $view->setLabel($column->getOption('label'));
        if (!is_null($order = $column->getOption('order'))) {
            $view->setAttribute('order', $order);
        }
    }

    /**
     * {@inheritDoc}
     */
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
            'action',
        );
    }

    /**
     * {@inheritDoc}
     */
    public function initOptions(ColumnTypeInterface $column)
    {
        $column->getOptionsResolver()->setDefaults(array(
            'label' => $column->getName(),
            'order' => null,
            'mapping_fields' => array($column->getName()),
        ));

        $column->getOptionsResolver()->setAllowedTypes(array(
            'label' => 'string',
            'mapping_fields' => 'array',
            'order' => array(
                'integer',
                'null'
            ),
        ));
    }
}
