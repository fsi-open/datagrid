<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Extension\Doctrine\ColumnTypeExtension;

use FSi\Component\DataGrid\Column\ColumnInterface;
use FSi\Component\DataGrid\Column\ColumnAbstractTypeExtension;
use FSi\Component\DataGrid\Exception\DataGridException;
use FSi\Component\DataGrid\Extension\Doctrine\ColumnType\Entity;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ValueFormatColumnOptionsExtension extends ColumnAbstractTypeExtension
{
    public function filterValue(ColumnInterface $column, $value)
    {
        $returnValue = [];
        if (($emptyValue = $column->getOption('empty_value')) !== null) {
            $value = $this->populateValues($value, $emptyValue);
        }
        $glue = $column->getOption('value_glue');
        $format = $column->getOption('value_format');

        foreach ($value as $val) {
            $objectValue = null;

            if (isset($glue) && !isset($format)) {
                $objectValue = implode($glue, $val);
            }

            if (isset($format)) {
                if (isset($glue)) {
                    $formattedValues = [];
                    foreach ($val as $fieldValue) {
                        $formattedValues[] = sprintf($format, $fieldValue);
                    }

                    $objectValue = implode($glue, $formattedValues);
                } else {
                    $objectValue = vsprintf($format, $val);
                }
            }

            $returnValue[] = $objectValue;
        }

        return implode($column->getOption('glue_multiple'), $returnValue);
    }

    public function getExtendedColumnTypes(): array
    {
        return [
            Entity::class,
        ];
    }

    public function initOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults([
            'glue_multiple' => ' ',
            'value_glue' => ' ',
            'value_format' => '%s',
            'empty_value' => null
        ]);

        $optionsResolver->setAllowedTypes('glue_multiple', ['string']);
        $optionsResolver->setAllowedTypes('value_glue', ['string', 'null']);
        $optionsResolver->setAllowedTypes('value_format', ['string', 'null']);
        $optionsResolver->setAllowedTypes('empty_value', ['array', 'string', 'null']);
    }

    private function populateValues(array $values, $emptyValue): array
    {
        foreach ($values as &$val) {
            foreach ($val as $fieldKey => &$fieldValue) {
                if (!isset($fieldValue)) {
                    $fieldValue = $this->populateValue($fieldKey, $fieldValue, $emptyValue);
                }
            }
        }

        return $values;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param mixed $emptyValue
     * @return mixed
     * @throws \FSi\Component\DataGrid\Exception\DataGridException
     */
    private function populateValue(string $key, $value, $emptyValue)
    {
        if (is_string($emptyValue)) {
            $value = $emptyValue;
        }

        if (is_array($emptyValue)) {
            if (isset($emptyValue[$key])) {
                $value = $emptyValue[$key];
            } else {
                throw new DataGridException(
                    sprintf('Not found key "%s" in empty_value array', $key)
                );
            }
        }

        return $value;
    }
}
