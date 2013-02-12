<?php
/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Tests\Fixtures\ColumnType;

use FSi\Component\DataGrid\Column\ColumnAbstractType;

class FooType extends ColumnAbstractType
{
    public function getId()
    {
        return 'foo';
    }

    public function filterValue($value)
    {
        return $value;
    }
}
