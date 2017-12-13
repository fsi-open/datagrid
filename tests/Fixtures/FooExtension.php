<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Tests\Fixtures;

use FSi\Component\DataGrid\DataGridAbstractExtension;
use FSi\Component\DataGrid\Tests\Fixtures\ColumnType;

class FooExtension extends DataGridAbstractExtension
{
    protected function loadColumnTypes(): array
    {
        return [
            new ColumnType\FooType(),
        ];
    }
}
