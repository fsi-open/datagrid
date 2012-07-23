<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\Table\Column;

class ColumnView implements ColumnViewInterface
{
    protected $source;
    
    protected $value;
    
    protected $attributes = array();

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }
    
    public function setAttribute($name, $value)
    {
        $this->attributes[$name] = $value;
        return $this;
    }
    
    public function getAttribute($name)
    {
        if (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }
        
        return null;
    }
    
    public function getAttributes()
    {
        return $this->attributes;
    }
    
    public function hasAttribute($name)
    {
        return array_key_exists($name, $this->attributes);
    }

    public function setSource($source)
    {
        $this->source = $source;
        return $this;
    }
    
    public function getSource()
    {
        return $this->source;
    }
} 