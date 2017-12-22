<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Column;

use FSi\Component\DataGrid\DataGridInterface;

interface ColumnInterface
{
    public function getType(): ColumnTypeInterface;

    public function getName(): string;

    public function getDataGrid(): DataGridInterface;

    public function getOption(string $name);

    public function hasOption(string $name): bool;
}
