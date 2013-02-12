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

interface DataGridEventInterface
{
    /**
     * Returns the form at the source of the event.
     *
     * @return DataGridInterface
     */
    public function getDataGrid();

    /**
     * Returns the data associated with this event.
     *
     * @return mixed
     */
    public function getData();

    /**
     * Allows updating data for example if you need to filter values
     *
     * @param mixed $data
     */
    public function setData($data);
}
