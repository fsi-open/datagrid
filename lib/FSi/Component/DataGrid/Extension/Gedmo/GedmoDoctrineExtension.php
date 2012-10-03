<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Extension\Gedmo;

use Doctrine\Common\Persistence\ManagerRegistry;
use FSi\Component\DataGrid\DataGridAbstractExtension;
use FSi\Component\DataGrid\Extension\Gedmo\ColumnType;

class GedmoDoctrineExtension extends DataGridAbstractExtension
{
    protected $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    protected function loadColumnTypes()
    {
        return array(
            new ColumnType\Tree($this->registry)
        );
    }
}