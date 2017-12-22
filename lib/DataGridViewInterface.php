<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid;

use FSi\Component\DataGrid\Column\HeaderViewInterface;

interface DataGridViewInterface extends \Iterator, \Countable, \ArrayAccess
{
    public function getName(): string;

    /**
     * @return HeaderViewInterface[]
     */
    public function getHeaders(): array;
}
