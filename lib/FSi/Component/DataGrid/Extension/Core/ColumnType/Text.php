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

class Text extends ColumnAbstractType
{
    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        return 'text';
    }

    /**
     * {@inheritDoc}
     */
    public function filterValue($value)
    {
        $trim = (boolean)$this->getOption('trim');
        if (isset($trim) && $trim == true) {
            foreach ($value as &$val) {
                if (empty($val)) {
                    continue;
                }

                $val = trim($val);
            }
        }

        return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function initOptions()
    {
        $this->getOptionsResolver()->setDefaults(array(
            'trim' => false
        ));

        $this->getOptionsResolver()->setAllowedTypes(array(
            'trim' => 'bool'
        ));
    }

    /**
     * {@inheritDoc}

    public function getAvailableOptions()
    {
        return array('trim');
    }
     */
}
