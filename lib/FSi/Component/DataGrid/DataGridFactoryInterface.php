<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid;

interface DataGridFactoryInterface
{
    /**
     * Check if column is registred in factory. Column types can be registred
     * only by extensions.
     * 
     * @param boolean $type
     */
    public function hasColumnType($type);

    /**
     * 
     * 
     * @throws UnexpectedTypeException if column is not registred in factory.
     * @param unknown_type $type
     */
    public function getColumnType($type);

    /**
     * Return all registred in factory DataGrid extensions as array.
     * 
     * @return array
     */
    public function getExtensions();
}
