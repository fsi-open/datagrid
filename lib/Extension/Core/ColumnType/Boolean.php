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

class Boolean extends ColumnAbstractType
{
    public function getId(): string
    {
        return 'boolean';
    }

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

        return $this->getOption($boolValue ? 'true_value' : 'false_value');
    }

    public function initOptions(): void
    {
        $this->getOptionsResolver()->setDefaults([
            'true_value' => '',
            'false_value' => ''
        ]);
    }
}
