<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
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

    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        return 'number';
    }

    /**
     * {@inheritDoc}
     */
    public function filterValue($value)
    {
        $precision = (int)$this->getOption('precision');
        $roundmode = $this->getOption('round_mode');

        if (isset($roundmode)) {
            foreach ($value as &$val) {
                if (empty($val)) {
                    continue;
                }

                $val = round($val, $precision, $roundmode);
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
            'round_mode' => null,
            'precision' => 2
        ));

        $this->getOptionsResolver()->setAllowedTypes(array(
            'precision' => 'integer',
        ));

        $this->getOptionsResolver()->setAllowedValues(array(
            'round_mode' => array(
                null,
                self::ROUND_HALF_UP,
                self::ROUND_HALF_DOWN,
                self::ROUND_HALF_EVEN,
                self::ROUND_HALF_ODD,
            )
        ));
    }
}
