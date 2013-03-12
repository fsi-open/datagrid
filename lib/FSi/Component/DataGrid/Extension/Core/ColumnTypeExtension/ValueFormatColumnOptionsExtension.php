<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension;

use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\Column\CellViewInterface;
use FSi\Component\DataGrid\Column\ColumnAbstractTypeExtension;

class ValueFormatColumnOptionsExtension extends ColumnAbstractTypeExtension
{
    /**
     * {@inheritDoc}
     */
    public function buildCellView(ColumnTypeInterface $column, CellViewInterface $view)
    {
        $this->validateEmptyValueOption($column);

        $value = $this->populateValue($view->getValue(), $column->getOption('empty_value'));
        $glue = $column->getOption('value_glue');
        $format = $column->getOption('value_format');

        if (is_array($value) && isset($glue) && !isset($format)) {
            $value = implode($glue, $value);
        }

        if (isset($format)) {
            if (is_array($value)) {
                if (isset($glue)) {
                    $formatedValues = array();
                    foreach ($value as $val) {
                        if ($format instanceof \Closure) {
                            $formatedValues[] = $format($val);
                        } else {
                            $formatedValues[] = sprintf($format, $val);
                        }
                    }

                    $value = implode($glue, $formatedValues);
                } else {
                    if ($format instanceof \Closure) {
                        $value = $format($value);
                    } else {
                        $value = vsprintf($format, $value);
                    }
                }
            } else {
                if ($format instanceof \Closure) {
                    $value = $format($value);
                } else {
                    $value = sprintf($format, $value);
                }
            }
        }

        if (is_array($value) && count($value) == 1) {
            reset($value);
            $value = current($value);
        }

        if (!isset($glue, $format) && is_array($value)) {
            throw new \InvalidArgumentException(sprintf('At least one of "format" or "glue" option is missing in column: "%s".', $column->getName()));
        }

        $view->setValue($value);
    }

    /**
     * {@inheritDoc}
     */
    public function getExtendedColumnTypes()
    {
        return array(
            'text',
            'boolean',
            'datetime',
            'number',
            'money',
            'gedmo.tree',
        );
    }

    /**
     * {@inheritDoc}
     */
    public function initOptions(ColumnTypeInterface $column)
    {
        $column->getOptionsResolver()->setDefaults(array(
            'value_glue' => null,
            'value_format' => null,
            'empty_value' => '',
        ));

        $column->getOptionsResolver()->setAllowedTypes(array(
            'value_glue' => array('string', 'null'),
            'value_format' => array(
                'string',
                'function',
                'null'
            ),
            'empty_value' => 'string'
        ));
    }

    /**
     * @param ColumnTypeInterface $column
     * @throws \InvalidArgumentException
     */
    private function validateEmptyValueOption(ColumnTypeInterface $column)
    {
        $emptyValue = $column->getOption('empty_value');
        $mappingFields = $column->getOption('field_mapping');

        if (is_string($emptyValue)) {
            return;
        }

        if (!is_array($emptyValue)) {
            throw new \InvalidArgumentException(
                sprintf('Option "empty_value" in column: "%s" must be a array.', $column->getName())
            );
        }

        foreach ($emptyValue as $field => $value) {
            if (!in_array($field, $mappingFields)) {
                throw new \InvalidArgumentException(
                    sprintf(
                        'Mapping field "%s" does\'t exists in column: "%s".',
                        $field,
                        $column->getName()
                    )
                );
            }

            if (!is_string($value)) {
                throw new \InvalidArgumentException(
                    sprintf(
                        'Option "empty_value" for field "%s" in column: "%s" must be a valid string.',
                        $field,
                        $column->getName()
                    )
                );
            }
        }
    }

    /**
     * @param mixed $value
     * @param string $emptyValue
     */
    private function populateValue($value, $emptyValue)
    {
        if (is_string($emptyValue)) {
            if (empty($value)) {
                return $emptyValue;
            }

            if (is_array($value)) {
                foreach ($value as &$val) {
                    if (empty($val)) {
                        $val = $emptyValue;
                    }
                }
            }

            return $value;
        }

        /**
         * If value is simple string and $empty_value is array there is no way
         * to guess which empty_value should be used.
         */
        if (is_string($value)) {
            return $value;
        }

        foreach ($value as $field => &$fieldValue)  {
            if (empty($fieldValue)) {
                $fieldValue = array_key_exists($field, $emptyValue)
                    ? $emptyValue[$field]
                    : '';
            }
        }

        return $value;
    }
}
