<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid;

use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataGrid\DataGridEventInterface;
use Symfony\Component\EventDispatcher\Event;

class DataGridEvent extends Event implements DataGridEventInterface
{
    /**
     * @var DataGridInterface
     */
    protected $dataGrid;

    /**
     * @var mixed
     */
    protected $data;

    public function __construct(DataGridInterface $dataGrid, $data)
    {
        $this->dataGrid = $dataGrid;
        $this->data = $data;
    }

    public function getDataGrid(): DataGridInterface
    {
        return $this->dataGrid;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data): void
    {
        $this->data = $data;
    }
}
