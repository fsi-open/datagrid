<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Extension\Core\ColumnType;

use FSi\Component\DataGrid\Column\ColumnAbstractType;
use FSi\Component\DataGrid\Column\ColumnInterface;

class Batch extends ColumnAbstractType
{
    public function getId(): string
    {
        return 'batch';
    }

    public function getValue(ColumnInterface $column, $object)
    {
        return null;
    }
}
