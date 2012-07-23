<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\Table\Extension\Core\ColumnType;

use FSi\Component\Table\Column\ColumnAbstractType;

class Text extends ColumnAbstractType 
{
    public function getId()
    {
        return 'text';
    }
    
    public function filterValue($value)
    {
        if (is_array($value))
            $value = current($value);

        return (string)$value;
    }
}