<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Extension\Doctrine;

use FSi\Component\DataGrid\DataGridAbstractExtension;
use FSi\Component\DataGrid\Extension\Doctrine\ColumnType;

class DoctrineExtension extends DataGridAbstractExtension
{
    /**
     * {@inheritDoc}
     */
    protected function loadColumnTypes()
    {
        return array(
            new ColumnType\Entity(),
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function loadColumnTypesExtensions()
    {
        return array(
            new ColumnTypeExtension\ValueFormatColumnOptionsExtension(),
        );
    }
}
