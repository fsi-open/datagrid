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

use FSi\Component\DataGrid\DataGridViewInterface;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataGrid\DataGridAbstractExtension;
use FSi\Component\DataGrid\Extension\Core\ColumnType;
use FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension;

class CoreExtension extends DataGridAbstractExtension
{
    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
    protected function loadColumnTypesExtensions()
    {
        return array(
            new ColumnTypeExtension\DefaultColumnOptionsExtension(),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(DataGridViewInterface $view, DataGridInterface $dataGridFactory)
    {
        if (count($view->columns)) {
            uasort($view->columns, function($a, $b) {
                $ordera = $a->hasOption('order') ? (float) $a->getOption('order') : 0;
                $orderb = $b->hasOption('order') ? (float) $b->getOption('order') : 0;

                if ($ordera == $orderb) {
                    return true;
                }

                return ($ordera < $orderb) ? -1 : 1;
            });
        }
    }
}
