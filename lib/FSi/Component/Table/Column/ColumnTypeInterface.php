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

use FSi\Component\Table\Column\ColumnViewInterface;
use FSi\Component\Table\DataMapper\DataMapperInterface;
use FSi\Component\Table\Column\ColumnTypeExtensionInterface;

interface ColumnTypeInterface 
{
    /**
     * Get column Type identity.
     */
    public function getId();
    
    /**
     * Get name under column is registred in table.
     */
    public function getName();
    
    /**
     * Filter value before passing it to view.
     * @param mixed $value
     */
    public function filterValue($value);
    
    /**
     * Create ColumnView object set source value on it.
     * @param mixed $object
     * @param mixed $index - row index in rowset
     */
    public function createView($object, $index);
    
    /**
     * 
     * @param ColumnViewInterface $view
     */
    public function buildView(ColumnViewInterface $view);
    
    /**
     * Return DataMapper
     */
    public function getDataMapper();

    /**
     * Binds data into object using DataMapper object. 
     * 
     * @param mixed $data
     * @param mixed $object
     */
    public function bindData($data, $object);

    public function setOption($name, $value);
    
    public function getOption($name);
    
    public function hasOption($name);
    
    public function setExtensions(array $extensions);
    
    public function addExtension(ColumnTypeExtensionInterface $extension);
    
    public function getExtensions();
    
}