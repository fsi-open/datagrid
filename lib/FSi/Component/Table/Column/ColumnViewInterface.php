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

interface ColumnViewInterface
{
    public function setAttribute($name, $value);
    
    public function getAttribute($name);
    
    public function hasAttribute($name);
    
    public function setSource($source);
    
    public function getSource();
} 