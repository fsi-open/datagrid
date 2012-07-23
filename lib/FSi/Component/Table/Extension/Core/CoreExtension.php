<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\Table\Extension\Core;

use FSi\Component\Table\TableAbstractExtension;
use FSi\Component\Table\Extension\Core\ColumnType;
use FSi\Component\Table\Extension\Core\EventListener;
use FSi\Component\Table\Extension\Core\ColumnTypeExtension;

class CoreExtension extends TableAbstractExtension
{
    protected function loadColumnTypes()
    {
        return array(
            new ColumnType\Text(),
            new ColumnType\Int(),
            new ColumnType\DateTime()
        );
    }

    protected function loadListeners()
    {
        return array(
            //new EventListener\BindSymfonyRequest()
        );
    }

    protected function loadColumnTypesExtensions()
    {
        return array(
            //new ColumnTypeExtension\SymfonyFormExtension(),
            new ColumnTypeExtension\DefaultColumnOptionsExtension()
        );
    }
}
