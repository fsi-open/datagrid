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
use FSi\Component\DataGrid\Column\ColumnInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Text extends ColumnAbstractType
{
    public function getId(): string
    {
        return 'text';
    }

    public function filterValue(ColumnInterface $column, $value)
    {
        if (true === $column->getOption('trim')) {
            foreach ($value as &$val) {
                if (empty($val)) {
                    continue;
                }

                $val = trim($val);
            }
        }

        return $value;
    }

    public function initOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults([
            'trim' => false
        ]);

        $optionsResolver->setAllowedTypes('trim', 'bool');
    }
}
