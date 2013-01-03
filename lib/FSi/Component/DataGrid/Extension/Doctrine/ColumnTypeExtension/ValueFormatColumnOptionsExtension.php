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

class ValueFormatColumnOptionsExtension extends ColumnAbstractTypeExtension
{
    public function buildCellView(ColumnTypeInterface $column, CellViewInterface $view)
    {
        $value = array();
        $values = $view->getValue();
        $glue = $column->getOption('glue');
        $format = $column->getOption('format');

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

    public function getExtendedColumnTypes()
    {
        return array(
            'entity'
        );
    }

    public function getDefaultOptionsValues(ColumnTypeInterface $column)
    {
        return array(
            'glue_multiple' => ' '
        );
    }

    public function getAvailableOptions(ColumnTypeInterface $column)
    {
        return array('glue', 'glue_multiple', 'format');
    }

}