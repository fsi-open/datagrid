<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Extension\Core\ColumnType;

use FSi\Component\DataGrid\DataGridFactory;
use FSi\Component\DataGrid\Column\ColumnAbstractType;

class Grid extends ColumnAbstractType 
{
    protected $factory; 

    public function __construct(DataGridFactory $factory)
    {
        $this->factory = $factory;
    }

    public function getId()
    {
        return 'grid';
    }

    public function filterValue($value)
    {
        $index = rand(0,100);//$view->getAttribute('row');
        $value = (count($value['newses'])) ? $value['newses'] : array();
        $grid = $this->factory->createDataGrid($this->getName().$index);
        $grid->setData($value);
        $grid->addColumn('id', 'integer', array(
                'label' => 'Identyfikator',
            ))->addColumn('title', 'text', array(
                'label' => 'Tytuł Newsa',
            ));
            
        return $grid->createView();
    }

    public function getDefaultOptionsValues()
    {
        return array(
            'format' => 'Y-m-d H:i:s'
        );
    }

    public function getRequiredOptions()
    {
        return array('format');
    }

    public function getAvailableOptions()
    {
        return array('format', 'input', 'mapping_fields_format');
    }
}
