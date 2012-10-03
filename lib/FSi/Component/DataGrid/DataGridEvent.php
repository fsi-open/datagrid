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

use FSi\Component\DataGrid\DataGridInterface;
use Symfony\Component\EventDispatcher\Event;

class DataGridEvent extends Event
{
    /**
     * @var DataGridInterface
     */
    protected $dataGrid;
    
    protected $data;
    
    public function __construct(DataGridInterface $dataGrid, $data)
    {
        $this->dataGrid = $dataGrid;
        $this->data = $data;
    }
    
    /**
     * Returns the form at the source of the event.
     *
     * @return DataGridInterface
     */
    public function getDataGrid()
    {
        return $this->dataGrid;
    }

    /**
     * Returns the data associated with this event.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
    
    /**
     * Allows updating data for example if you need to filter values
     *
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }
}