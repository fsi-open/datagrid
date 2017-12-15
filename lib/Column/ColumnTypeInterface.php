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
use FSi\Component\DataGrid\DataMapper\DataMapperInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface ColumnTypeInterface
{
    public function getId(): string;

    public function getName(): string;

    public function setName(string $name): void;

    public function setDataGrid(DataGridInterface $dataGrid): void;

    public function getDataGrid(): DataGridInterface;

    public function setDataMapper(DataMapperInterface $dataMapper): void;

    public function getDataMapper(): DataMapperInterface;

    public function filterValue($value);

    public function getValue($object);

    public function createCellView($object, $index): CellViewInterface;

    public function buildCellView(CellViewInterface $view): void;

    public function createHeaderView(): HeaderViewInterface;

    public function buildHeaderView(HeaderViewInterface $view): void;

    public function bindData($data, $object, $index): void;

    public function initOptions(): void;

    public function setOption(string $name, $value): void;

    public function setOptions(array $options): void;

    public function getOption(string $name);

    public function hasOption(string $name): bool;

    /**
     * @param ColumnTypeExtensionInterface[] $extensions
     */
    public function setExtensions(array $extensions): void;

    public function addExtension(ColumnTypeExtensionInterface $extension): void;

    /**
     * @return ColumnTypeExtensionInterface[]
     */
    public function getExtensions(): array;

    public function getOptionsResolver(): OptionsResolver;
}
