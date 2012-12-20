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

            uasort($columns, function($a, $b) {
                $ordera = $a->hasAttribute('order') ? (float) $a->getAttribute('order') : 0;
                $orderb = $b->hasAttribute('order') ? (float) $b->getAttribute('order') : 0;

                if ($ordera == $orderb) {
                    return true;
                }

                return ($ordera < $orderb) ? -1 : 1;
            });

            $view->setColumns($columns);
        }

        $event->setData($view);
    }
}