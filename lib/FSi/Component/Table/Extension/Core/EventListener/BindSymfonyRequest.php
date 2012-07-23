<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\Table\Extension\Core\EventListener;

use FSi\Component\Table\TableEvent;
use FSi\Component\Table\TableEvents;
use FSi\Component\Table\Exception\TableException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BindSymfonyRequest implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(TableEvents::PRE_BIND_DATA => array('preBindData', 128));
    }

    public function preBindData(TableEvent $event)
    {
        $table    = $event->getTable();
        $request  = $event->getData();

        if (!($request instanceof Request)) {
            return;
        }

        $name = $table->getName();
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
                throw new TableException(sprintf(
                    'The request method "%s" is not supported',
                    $request->getMethod()
                ));
                break;
        }

        $event->setData($data);
    }
}