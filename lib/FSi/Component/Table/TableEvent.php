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

use FSi\Component\Table\TableInterface;
use Symfony\Component\EventDispatcher\Event;

class TableEvent extends Event
{
    /**
     * @var TableInterface
     */
    protected $table;
    
    protected $data;
    
    public function __construct(TableInterface $table, $data)
    {
        $this->table = $table;
        $this->data = $data;
    }
    
    /**
     * Returns the form at the source of the event.
     *
     * @return TableInterface
     */
    public function getTable()
    {
        return $this->table;
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