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
use FSi\Component\DataGrid\Column\HeaderViewInterface;
use FSi\Component\DataGrid\Column\ColumnAbstractTypeExtension;

class ValueFormatColumnOptionsExtension extends ColumnAbstractTypeExtension
{
    public function buildCellView(ColumnTypeInterface $column, CellViewInterface $view)
    {
        $value = $view->getValue();
        $glue = $column->getOption('glue');
        $format = $column->getOption('format');

        if (is_array($value) && isset($glue) && !isset($format)) {
            $value = implode($glue, $value);
        }

        if (isset($format)) {
            if (is_array($value)) {
                if (isset($glue)) {
                    $formatedValues = array();
                    foreach ($value as $val) {
                        $formatedValues[] = sprintf($format, $val);
                    }

                    $value = implode($glue, $formatedValues);
                } else {
                    $value = vsprintf($format, $value);
                }
            } else {
                $value = sprintf($format, $value);
            }
        }

        if (is_array($value) && count($value) == 1) {
            $value = current($value);
        }

        if (!isset($glue, $format) && is_array($value)) {
            throw new \InvalidArgumentException(sprintf('At least one of "format" or "glue" option is missing in column: "%s".', $column->getName()));
        }

        $view->setValue($value);
    }

    public function getExtendedColumnTypes()
    {
        return array(
            'text',
            'datetime',
            'number',
            'money',
            'gedmo.tree'
        );
    }

    public function getAvailableOptions(ColumnTypeInterface $column)
    {
        return array('glue', 'format');
    }
}