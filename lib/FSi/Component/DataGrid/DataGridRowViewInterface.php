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

interface DataGridRowViewInterface extends \Iterator, \Countable, \ArrayAccess
{
    /**
     * Return row index in DataGridView.
     *
     * @return int
     */
    public function getIndex();
}
