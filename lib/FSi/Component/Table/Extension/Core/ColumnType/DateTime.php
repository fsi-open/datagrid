<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\Table\Extension\Core\ColumnType;

use FSi\Component\Table\Column\ColumnViewInterface;
use FSi\Component\Table\Column\ColumnAbstractType;

class DateTime extends ColumnAbstractType 
{
    public function getId()
    {
        return 'date_time';
    }
    
    public function filterValue($value)
    {
        if (is_array($value))
            $value = current($value);

        if (is_string($value)) {
            try {
                $value = new \DateTime($value);
            } catch (Exception $e) { 
            }
        }
            
        if ($value instanceof \DateTime) 
            return $value->format($this->getOption('format'));
            
        return null;
    }
    
    public function getDefaultOptionsValues()
    {
        return array('format' => 'Y-m-d H:i:s');
    }
    
    public function getRequiredOptions()
    {
        return array('format');
    }
    
    public function getAvailableOptions()
    {
        return array('format');
    }
}