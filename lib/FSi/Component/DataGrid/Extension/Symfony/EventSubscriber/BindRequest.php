<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Extension\Symfony\EventSubscriber;

use FSi\Component\DataGrid\DataGridEvent;
use FSi\Component\DataGrid\DataGridEvents;
use FSi\Component\DataGrid\Exception\DataGridException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BindRequest implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(DataGridEvents::PRE_BIND_DATA => array('preBindData', 128));
    }

    public function preBindData(DataGridEvent $event)
    {
        $dataGrid    = $event->getDataGrid();
        $request  = $event->getData();

        if (!($request instanceof Request)) {
            return;
        }

        $name = $dataGrid->getName();
        $default = array();

        switch ($request->getMethod()) {
            case 'POST':
            case 'PUT':
            case 'DELETE':
            case 'PATCH':
                $data = $request->request->get($name, $default);
                break;
            case 'GET':
                $data = '' === $name
                    ? $request->query->all()
                    : $request->query->get($name, $default);
                break;
            default:
                throw new DataGridException(sprintf(
                    'The request method "%s" is not supported',
                    $request->getMethod()
                ));
                break;
        }

        $event->setData($data);
    }
}