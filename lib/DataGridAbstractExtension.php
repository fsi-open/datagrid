<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid;

use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\Column\ColumnTypeExtensionInterface;
use FSi\Component\DataGrid\Exception\UnexpectedTypeException;
use FSi\Component\DataGrid\Exception\DataGridException;

abstract class DataGridAbstractExtension implements DataGridExtensionInterface
{
    /**
     * @var ColumnTypeExtensionInterface[][]
     */
    protected $columnTypesExtensions;

    /**
     * @var ColumnTypeInterface[]
     */
    protected $columnTypes;

    public function getColumnType(string $type): ColumnTypeInterface
    {
        if (null === $this->columnTypes) {
            $this->initColumnTypes();
        }

        if (!array_key_exists($type, $this->columnTypes)) {
            throw new DataGridException(sprintf(
                'The column type "%s" can not be loaded by this extension',
                $type
            ));
        }

        return $this->columnTypes[$type];
    }

    public function hasColumnType(string $type): bool
    {
        if (null === $this->columnTypes) {
            $this->initColumnTypes();
        }

        return array_key_exists($type, $this->columnTypes);
    }

    public function hasColumnTypeExtensions(ColumnTypeInterface $columnType): bool
    {
        if (null === $this->columnTypesExtensions) {
            $this->initColumnTypesExtensions();
        }

        foreach (array_keys($this->columnTypesExtensions) as $extendedType) {
            if (is_a($columnType, $extendedType)) {
                return true;
            }
        }

        return false;
    }

    public function getColumnTypeExtensions(ColumnTypeInterface $columnType): array
    {
        if (null === $this->columnTypesExtensions) {
            $this->initColumnTypesExtensions();
        }

        foreach ($this->columnTypesExtensions as $extendedType => $extensions) {
            if (is_a($columnType, $extendedType)) {
                return $extensions;
            }
        }

        throw new DataGridException(sprintf(
            'Extension for column type "%s" can not be loaded by this DataGrid extension',
            get_class($columnType)
        ));
    }

    /**
     * @return ColumnTypeInterface[]
     */
    protected function loadColumnTypes(): array
    {
        return [];
    }

    /**
     * @return ColumnTypeExtensionInterface[]
     */
    protected function loadColumnTypesExtensions(): array
    {
        return [];
    }

    private function initColumnTypes(): void
    {
        $this->columnTypes = [];

        $columnTypes = $this->loadColumnTypes();

        foreach ($columnTypes as $columnType) {
            if (!$columnType instanceof ColumnTypeInterface) {
                throw new UnexpectedTypeException(sprintf(
                    'Column type must implement "%s"',
                    ColumnTypeInterface::class
                ));
            }

            $this->columnTypes[$columnType->getId()] = $columnType;
            $this->columnTypes[get_class($columnType)] = $columnType;
        }
    }

    private function initColumnTypesExtensions(): void
    {
        $columnTypesExtensions = $this->loadColumnTypesExtensions();
        $this->columnTypesExtensions = [];

        foreach ($columnTypesExtensions as $extension) {
            if (!$extension instanceof ColumnTypeExtensionInterface) {
                throw new UnexpectedTypeException(sprintf(
                    'Extension must implement %s',
                    ColumnTypeExtensionInterface::class
                ));
            }

            $types = $extension->getExtendedColumnTypes();
            foreach ($types as $type) {
                if (!array_key_exists($type, $this->columnTypesExtensions)) {
                    $this->columnTypesExtensions[$type] = [];
                }

                $this->columnTypesExtensions[$type][] = $extension;
            }
        }
    }
}
