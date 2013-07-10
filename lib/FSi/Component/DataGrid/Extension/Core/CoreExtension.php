<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Extension\Core;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use FSi\Component\DataGrid\DataGridViewInterface;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataGrid\DataGridAbstractExtension;
use FSi\Component\DataGrid\Extension\Core\ColumnType;
use FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension;
use FSi\Component\DataGrid\Extension\Core\EventSubscriber;

class CoreExtension extends DataGridAbstractExtension
{
    /**
     * {@inheritDoc}
     */
    protected function loadColumnTypes()
    {
        return array(
            new ColumnType\Text(),
            new ColumnType\Number(),
            new ColumnType\DateTime(),
            new ColumnType\Action(),
            new ColumnType\Money(),
            new ColumnType\Action()
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function loadColumnTypesExtensions()
    {
        return array(
            new ColumnTypeExtension\DefaultColumnOptionsExtension(),
            new ColumnTypeExtension\ValueFormatColumnOptionsExtension(),
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function loadSubscribers()
    {
        return array(
            new EventSubscriber\ColumnOrder(),
        );
    }
}
