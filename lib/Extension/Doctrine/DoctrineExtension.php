<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Extension\Doctrine;

use FSi\Component\DataGrid\DataGridAbstractExtension;
use FSi\Component\DataGrid\Extension\Doctrine\ColumnType;

class DoctrineExtension extends DataGridAbstractExtension
{
    protected function loadColumnTypes(): array
    {
        return [
            new ColumnType\Entity(),
        ];
    }

    protected function loadColumnTypesExtensions(): array
    {
        return [
            new ColumnTypeExtension\ValueFormatColumnOptionsExtension(),
        ];
    }
}
