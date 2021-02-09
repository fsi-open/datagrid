<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Extension\Gedmo;

use Doctrine\Persistence\ManagerRegistry;
use FSi\Component\DataGrid\DataGridAbstractExtension;
use FSi\Component\DataGrid\Extension\Gedmo\ColumnType;

class GedmoDoctrineExtension extends DataGridAbstractExtension
{
    /**
     * @var ManagerRegistry
     */
    protected $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    protected function loadColumnTypes(): array
    {
        return [
            new ColumnType\Tree($this->registry),
        ];
    }
}
