<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\Table\Extension\Core\ColumnTypeExtension;

use FSi\Component\Table\Column\ColumnTypeInterface;
use FSi\Component\Table\Column\ColumnViewInterface;
use FSi\Component\Table\Column\ColumnAbstractTypeExtension;
use FSi\Component\Table\Exception\TableColumnException;

class DefaultColumnOptionsExtension extends ColumnAbstractTypeExtension 
{

    public function getExtendedColumnTypes()
    {
        return array(
            'text',
            'date_time',
            'int'
        );
    }

    public function getDefaultOptionsValues(ColumnTypeInterface $column)
    {
        return array(
            'label' => $column->getName(),
            'mapping_fields' => array($column->getName())
        );
    }

    public function getRequiredOptions(ColumnTypeInterface $column)
    {
        return array('mapping_fields');
    }

    public function getAvailableOptions(ColumnTypeInterface $column)
    {
        return array('label', 'mapping_fields');
    }
}