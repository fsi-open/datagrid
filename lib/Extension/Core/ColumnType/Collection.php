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

class Collection extends ColumnAbstractType
{
    public function getId(): string
    {
        return 'collection';
    }

    public function filterValue($value)
    {
        $value = (array) $value;
        foreach ($value as &$val) {
            if (!is_array($val)) {
                continue;
            }

            $val = implode($this->getOption('collection_glue'), $val);
        }

        return $value;
    }

    public function initOptions(): void
    {
        $this->getOptionsResolver()->setDefaults([
            'collection_glue' => ' '
        ]);

        $this->getOptionsResolver()->setAllowedTypes('collection_glue', 'string');
    }
}
