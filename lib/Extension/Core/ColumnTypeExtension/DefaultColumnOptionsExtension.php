<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension;

use FSi\Component\DataGrid\Column\ColumnInterface;
use FSi\Component\DataGrid\Column\HeaderViewInterface;
use FSi\Component\DataGrid\Column\ColumnAbstractTypeExtension;
use FSi\Component\DataGrid\Extension\Core\ColumnType\Action;
use FSi\Component\DataGrid\Extension\Core\ColumnType\Batch;
use FSi\Component\DataGrid\Extension\Core\ColumnType\Boolean;
use FSi\Component\DataGrid\Extension\Core\ColumnType\Collection;
use FSi\Component\DataGrid\Extension\Core\ColumnType\DateTime;
use FSi\Component\DataGrid\Extension\Core\ColumnType\Money;
use FSi\Component\DataGrid\Extension\Core\ColumnType\Number;
use FSi\Component\DataGrid\Extension\Core\ColumnType\Text;
use FSi\Component\DataGrid\Extension\Doctrine\ColumnType\Entity;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DefaultColumnOptionsExtension extends ColumnAbstractTypeExtension
{
    public function getExtendedColumnTypes(): array
    {
        return [
            Batch::class,
            Text::class,
            Boolean::class,
            Collection::class,
            DateTime::class,
            Number::class,
            Money::class,
            Entity::class,
            Action::class,
        ];
    }

    public function buildHeaderView(ColumnInterface $column, HeaderViewInterface $view): void
    {
        $view->setLabel($column->getOption('label'));
    }

    public function initOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults([
            'label' => function (Options $options, $previousValue) {
                if (null !== $previousValue) {
                    return $previousValue;
                }

                return $options['name'];
            },
            'display_order' => null,
            'field_mapping' => function (Options $options, $previousValue) {
                if (null !== $previousValue) {
                    return $previousValue;
                }

                return [$options['name']];
            },
        ]);

        $optionsResolver->setAllowedTypes('label', 'string');
        $optionsResolver->setAllowedTypes('field_mapping', 'array');
        $optionsResolver->setAllowedTypes('display_order', ['integer', 'null']);
    }
}
