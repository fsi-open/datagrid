<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Extension\Core;

use FSi\Component\DataGrid\DataGridAbstractExtension;
use FSi\Component\DataGrid\Extension\Core\ColumnType;
use FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension;
use FSi\Component\DataGrid\Extension\Core\EventSubscriber;

class CoreExtension extends DataGridAbstractExtension
{
    protected function loadColumnTypes(): array
    {
        return [
            new ColumnType\Text(),
            new ColumnType\Number(),
            new ColumnType\Collection(),
            new ColumnType\DateTime(),
            new ColumnType\Action(),
            new ColumnType\Money(),
            new ColumnType\Action(),
        ];
    }

    protected function loadColumnTypesExtensions(): array
    {
        return [
            new ColumnTypeExtension\DefaultColumnOptionsExtension(),
            new ColumnTypeExtension\ValueFormatColumnOptionsExtension(),
        ];
    }

    protected function loadSubscribers(): array
    {
        return [
            new EventSubscriber\ColumnOrder(),
        ];
    }
}
