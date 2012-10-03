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

use FSi\Component\DataGrid\Column\ColumnViewInterface;
use FSi\Component\DataGrid\Column\ColumnAbstractType;

class Text extends ColumnAbstractType 
{
    public function getId()
    {
        return 'text';
    }
    
    public function filterValue($value)
    {
        $trim = (boolean)$this->getOption('trim');
        if (isset($trim) && $trim == true) {
            foreach ($value as &$val) {
                $val = trim($val);
            }
        }

        return $value;
    }

    public function buildView(ColumnViewInterface $view)
    {
        $glue = $this->getOption('glue');
        $value = $view->getValue();

        if (is_array($value)) {
            $glue = $this->getOption('glue');
            $value = implode($glue, $value);
        }

        $view->setValue((string)$value);
    }

    public function getAvailableOptions()
    {
        return array('trim');
    }
}