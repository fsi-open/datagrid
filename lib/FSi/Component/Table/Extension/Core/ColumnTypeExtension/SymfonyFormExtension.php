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

class SymfonyFormExtension extends ColumnAbstractTypeExtension 
{
	public function bindData(ColumnTypeInterface $column, $data, $object)
	{
		if ($column->getOption('editable') === false) {
			throw new TableColumnException(
					sprintf('Column "%s" is not editable. You can change it '.
							'by setting option "editable" into true.', $column->getName()
					)
			);
		}
		var_dump($data);
		var_dump(get_class($object));
	}

    public function buildView(ColumnTypeInterface $column, ColumnViewInterface $view)
    {
        $view->setAttribute('test', time());
    }

    public function getExtendedColumnTypes()
    {
        return array(
            'text',
            'int'
        );
    }

    public function getDefaultOptionsValues(ColumnTypeInterface $column)
    {
    	return array('editable' => false);
    }

    public function getRequiredOptions(ColumnTypeInterface $column)
    {
    	return array('editable');
    }

    public function getAvailableOptions(ColumnTypeInterface $column)
    {
    	return array('editable');
    }
}