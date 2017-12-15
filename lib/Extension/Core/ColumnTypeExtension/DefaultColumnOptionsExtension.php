<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension;

use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\Column\HeaderViewInterface;
use FSi\Component\DataGrid\Column\ColumnAbstractTypeExtension;

class DefaultColumnOptionsExtension extends ColumnAbstractTypeExtension
{
    public function buildHeaderView(ColumnTypeInterface $column, HeaderViewInterface $view): void
    {
        $view->setLabel($column->getOption('label'));
        $order = $column->getOption('display_order');
        if (null !== $order) {
            $view->setAttribute('display_order', $order);
        }
    }

    public function getExtendedColumnTypes(): array
    {
        return [
            'batch',
            'text',
            'boolean',
            'collection',
            'datetime',
            'number',
            'money',
            'gedmo_tree',
            'entity',
            'action',
        ];
    }

    public function initOptions(ColumnTypeInterface $column): void
    {
        $column->getOptionsResolver()->setDefaults([
            'label' => $column->getName(),
            'display_order' => null,
            'field_mapping' => [$column->getName()]
        ]);

        $column->getOptionsResolver()->setAllowedTypes('label', 'string');
        $column->getOptionsResolver()->setAllowedTypes('field_mapping', 'array');
        $column->getOptionsResolver()->setAllowedTypes('display_order', ['integer', 'null']);
    }
}
