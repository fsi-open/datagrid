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

class Boolean extends ColumnAbstractType
{
    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        return 'boolean';
    }

    /**
     * {@inheritDoc}
     */
    public function filterValue($value)
    {
        $value = (array) $value;

        $boolValue = true;
        foreach ($value as $val) {
            $boolValue = (boolean) ($boolValue & (boolean) $val);

            if (!$boolValue) {
                break;
            }
        }

        return $boolValue ? $this->getOption('true_value') : $this->getOption('false_value') ;
    }

    /**
     * {@inheritDoc}
     */
    public function getDefaultOptionsValues()
    {
        return array(
            'true_value' => '',
            'false_value' => '',
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getAvailableOptions()
    {
        return array('true_value', 'false_value');
    }
}
