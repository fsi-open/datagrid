<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Extension\Core\ColumnType;

use FSi\Component\DataGrid\Column\ColumnAbstractType;

class Number extends ColumnAbstractType
{
    const ROUND_HALF_UP = PHP_ROUND_HALF_UP;
    const ROUND_HALF_DOWN = PHP_ROUND_HALF_DOWN;
    const ROUND_HALF_EVEN = PHP_ROUND_HALF_EVEN;
    const ROUND_HALF_ODD = PHP_ROUND_HALF_ODD;

    public function getId()
    {
        return 'number';
    }

    public function filterValue($value)
    {
        $precision = (int)$this->getOption('precision');
        $roundmode = $this->getOption('round_mode');

        if (isset($roundmode)) {
            foreach ($value as &$val) {
                $val = round($val, $precision, $roundmode);
            }
        }

        return $value;
    }

    public function getDefaultOptionsValues()
    {
        return array(
            'precision' => 2
        );
    }

    public function getAvailableOptions()
    {
        return array('round_mode', 'precision');
    }


}