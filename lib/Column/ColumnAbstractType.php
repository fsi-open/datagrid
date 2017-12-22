<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Column;

use FSi\Component\DataGrid\Exception\DataGridColumnException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessor;

abstract class ColumnAbstractType implements ColumnTypeInterface
{
    /**
     * @var PropertyAccessor
     */
    protected $propertyAccessor;

    public function __construct()
    {
        $this->propertyAccessor = new PropertyAccessor();
    }

    public function getValue(ColumnInterface $column, $object)
    {
        $values = [];
        if (!$column->hasOption('field_mapping') || !count($column->getOption('field_mapping'))) {
            throw new DataGridColumnException(
                sprintf('"field_mapping" option is missing in column "%s"', $column->getName())
            );
        }

        foreach ($column->getOption('field_mapping') as $field) {
            $values[$field] = $this->propertyAccessor->getValue($object, $field);
        }

        return $values;
    }

    public function filterValue(ColumnInterface $column, $value)
    {
        return $value;
    }

    public function buildCellView(ColumnInterface $column, CellViewInterface $view): void
    {
    }

    public function buildHeaderView(ColumnInterface $column, HeaderViewInterface $view): void
    {
    }

    public function initOptions(OptionsResolver $optionsResolver): void
    {
    }
}
