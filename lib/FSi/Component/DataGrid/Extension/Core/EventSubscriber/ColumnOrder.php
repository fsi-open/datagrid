<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Extension\Core\EventSubscriber;

use FSi\Component\DataGrid\DataGridEventInterface;
use FSi\Component\DataGrid\DataGridEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ColumnOrder implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(DataGridEvents::POST_BUILD_VIEW => array('postBuildView', 128));
    }

    public function postBuildView(DataGridEventInterface $event)
    {
        $dataGrid = $event->getDataGrid();
        $view = $event->getData();

        if (count($view->getColumns())) {
            $columns = $view->getColumns();
            $sort = false;

            /**
             * Check if any of selected column have order attribute different than 0.
             * Sorting columns without order attribute may give strange output columns order.
             */
            foreach ($columns as $column) {
                if ($column->hasAttribute('order')) {
                    if ((float) $column->getAttribute('order') != 0) {
                        $sort = true;
                        break;
                    }
                }
            }

            if ($sort) {
                uasort($columns, function($a, $b) {
                    $orderA = $a->hasAttribute('order') ? (float) $a->getAttribute('order') : 0;
                    $orderB = $b->hasAttribute('order') ? (float) $b->getAttribute('order') : 0;

                    if ($orderA == $orderB) {
                        return 1;
                    }

                    return ($orderA < $orderB) ? -1 : 1;
                });

                $view->setColumns($columns);
            }
        }

        $event->setData($view);
    }
}