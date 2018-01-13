<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Tests\Fixtures;


use FSi\Component\DataGrid\Column\ColumnTypeExtensionInterface;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\DataGridExtensionInterface;
use FSi\Component\DataGrid\DataGridInterface;

class SimpleDataGridExtension implements DataGridExtensionInterface
{
    /**
     * @var ColumnTypeExtensionInterface
     */
    private $columnTypeExtension;

    /**
     * @var ColumnTypeInterface|null
     */
    private $columnType;

    public function __construct(ColumnTypeExtensionInterface $columnTypeExtension, ?ColumnTypeInterface $columnType)
    {
        $this->columnTypeExtension = $columnTypeExtension;
        $this->columnType = $columnType;
    }

    public function hasColumnType(string $type): bool
    {
        return null !== $this->columnType && ($this->columnType->getId() === $type || is_a($this->columnType, $type));
    }

    public function getColumnType(string $type): ColumnTypeInterface
    {
        if (!$this->hasColumnType($type)) {
            throw new \RuntimeException(sprintf('Column of type "%s" does not exist', $type));
        }

        return $this->columnType;
    }

    public function hasColumnTypeExtensions(ColumnTypeInterface $columnType): bool
    {
        foreach ($this->columnTypeExtension->getExtendedColumnTypes() as $extendedColumnType) {
            if (is_a($columnType, $extendedColumnType)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param ColumnTypeInterface $columnType
     * @return ColumnTypeExtensionInterface[]
     */
    public function getColumnTypeExtensions(ColumnTypeInterface $columnType): array
    {
        foreach ($this->columnTypeExtension->getExtendedColumnTypes() as $extendedColumnType) {
            if (is_a($columnType, $extendedColumnType)) {
                return [$this->columnTypeExtension];
            }
        }

        return [];
    }
}
