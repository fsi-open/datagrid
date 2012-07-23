<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\Table;

interface TableInterface 
{
    /**
     * Get column unique name.
     */
    public function getName();

    public function addColumn($name, $type = 'text', $otpions = array());

    public function removeColumn($name);

    public function getDataMapper();

    public function getColumn($name);

    public function getColumns();

    public function hasColumn($name);

    /**
     * Returns table rowset that contains source data. 
     */
    public function getRowset();

    /**
     * Create TableView object that should be used to render table.
     */
    public function createView();

    /**
     * Set data collection. This method is different from bind data and 
     * should not be used to update date. 
     * Data should be passed as array or object that implements 
     * \ArrayAccess, \Countable and \IteratorAggregate interfaces.
     * @param array $data
     */
    public function setData($data);

    /**
     * This method should be used only to update already set data. 
     * @param mixed $data
     */
    public function bindData($data);
}
