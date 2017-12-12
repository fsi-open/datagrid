<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Extension\Core\ColumnType;

use FSi\Component\DataGrid\Column\ColumnAbstractType;

class Boolean extends ColumnAbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'boolean';
    }

    /**
     * {@inheritdoc}
     */
    public function filterValue($value)
    {
        $value = (array) $value;

        $boolValue = null;
        foreach ($value as $val) {
            if ($val === null) {
                continue;
            }

            if ((bool) $val === false) {
                $boolValue = false;
                break;
            }

            $boolValue = true;
        }

        if (null === $boolValue) {
            return '';
        }

        return $boolValue ? $this->getOption('true_value') : $this->getOption('false_value') ;
    }

    /**
     * {@inheritdoc}
     */
    public function initOptions()
    {
        $this->getOptionsResolver()->setDefaults([
            'true_value' => '',
            'false_value' => ''
        ]);
    }
}
