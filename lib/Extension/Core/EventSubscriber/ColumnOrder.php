<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Extension\Core\EventSubscriber;

use FSi\Component\DataGrid\DataGridEventInterface;
use FSi\Component\DataGrid\DataGridEvents;
use FSi\Component\DataGrid\DataGridViewInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ColumnOrder implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [DataGridEvents::POST_BUILD_VIEW => ['postBuildView', 128]];
    }

    /**
     * {@inheritdoc}
     */
    public function postBuildView(DataGridEventInterface $event)
    {
        /** @var DataGridViewInterface $view */
        $view = $event->getData();
        $columns = $view->getColumns();

        if (count($columns)) {
            $positive = [];
            $negative = [];
            $neutral = [];

            $indexedColumns = [];
            foreach ($columns as $column) {
                if ($column->hasAttribute('display_order')) {
                    if (($order = $column->getAttribute('display_order')) >= 0) {
                        $positive[$column->getName()] = $order;
                    } else {
                        $negative[$column->getName()] = $order;
                    }
                    $indexedColumns[$column->getName()] = $column;
                } else {
                    $neutral[] = $column;
                }
            }

            asort($positive);
            asort($negative);

            $columns = [];
            foreach ($negative as $name => $order) {
                $columns[] = $indexedColumns[$name];
            }

            $columns = array_merge($columns, $neutral);
            foreach ($positive as $name => $order) {
                $columns[] = $indexedColumns[$name];
            }

            $view->setColumns($columns);
        }
    }
}
