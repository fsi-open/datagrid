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

class Text extends ColumnAbstractType
{
    public function getId(): string
    {
        return 'text';
    }

    public function filterValue($value)
    {
        $trim = $this->getOption('trim');
        if ($trim === true) {
            foreach ($value as &$val) {
                if (empty($val)) {
                    continue;
                }

                $val = trim($val);
            }
        }

        return $value;
    }

    public function initOptions(): void
    {
        $this->getOptionsResolver()->setDefaults([
            'trim' => false
        ]);

        $this->getOptionsResolver()->setAllowedTypes('trim', 'bool');
    }
}
