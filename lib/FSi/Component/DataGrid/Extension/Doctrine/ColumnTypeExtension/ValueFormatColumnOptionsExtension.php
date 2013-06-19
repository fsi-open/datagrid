<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Extension\Doctrine\ColumnTypeExtension;

use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\Column\CellViewInterface;
use FSi\Component\DataGrid\Column\HeaderViewInterface;
use FSi\Component\DataGrid\Column\ColumnAbstractTypeExtension;
use FSi\Component\DataGrid\Exception\DataGridException;

class ValueFormatColumnOptionsExtension extends ColumnAbstractTypeExtension
{
    /**
     * {@inheritDoc}
     */
    public function buildCellView(ColumnTypeInterface $column, CellViewInterface $view)
    {
        $value = array();
        $values = $this->populateValue($view->getValue(), $column->getOption('empty_value'));
        $glue = $column->getOption('value_glue');
        $format = $column->getOption('value_format');

        foreach ($values as $val) {
            $objectValue = null;

            if (isset($glue) && !isset($format)) {
                $objectValue = implode($glue, $val);
            }

            if (isset($format)) {
                if (isset($glue)) {
                    $formatedValues = array();
                    foreach ($val as $fieldValue) {
                        $formatedValues[] = sprintf($format, $fieldValue);
                    }

                    $objectValue = implode($glue, $formatedValues);
                } else {
                    $objectValue = vsprintf($format, $val);
                }
            }

            $value[] = $objectValue;
        }

        $value = implode($column->getOption('glue_multiple'), $value);

        $view->setValue($value);
    }

    /**
     * {@inheritDoc}
     **/
     public function getExtendedColumnTypes()
     {
         return array(
             'entity',
         );
     }

    /**
     * {@inheritDoc}
     */
    public function initOptions(ColumnTypeInterface $column)
    {
        $column->getOptionsResolver()->setDefaults(array(
            'glue_multiple' => ' ',
            'value_glue' => null,
            'value_format' => null,
            'empty_value' => null
        ));

        $column->getOptionsResolver()->setAllowedTypes(array(
            'glue_multiple' => array('string'),
            'value_glue' => array('string', 'null'),
            'value_format' => array('string', 'null'),
        	'empty_value' => array('array', 'string', 'null')
        ));
    }
    
    /**
     * @param $values
     * @param $emptyValue
     * @return array
     */
    private function populateValue($values, $emptyValue)
    {
        if (isset($emptyValue)) {
            foreach ($values as &$val) {
                foreach ($val as $fieldKey => &$fieldValue) {
                    if (!isset($fieldValue)) {
                        if (is_string($emptyValue)) {
                            $fieldValue = $emptyValue;
                        } else if (is_array($emptyValue)) {
                            if (isset($emptyValue[$fieldKey])) {
                                $fieldValue = $emptyValue[$fieldKey];
                            } else {
                                throw new DataGridException(
                                    sprintf('Not found key "%s" in empty_value array', $fieldKey)
                                );
                            } 
                        }
                    }
                }
            }
        }
        
        return $values;  
    }
}
