<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Column;

use FSi\Component\DataGrid\DataGridViewInterface;

interface CellViewInterface
{
    public function hasAttribute(string $name): bool;

    public function setAttribute(string $name, $value): void;

    public function getAttribute(string $name);

    public function getAttributes(): array;

    public function setSource($source): void;

    public function getSource();

    public function getValue();

    public function setValue($value): void;

    public function getType(): string;

    public function getName(): string;

    public function setDataGridView(DataGridViewInterface $dataGrid): void;

    public function getDataGridView(): DataGridViewInterface;
}
