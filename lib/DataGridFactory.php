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
use FSi\Component\DataGrid\Exception\DataGridColumnException;
use FSi\Component\DataGrid\Exception\UnexpectedTypeException;
use FSi\Component\DataGrid\DataMapper\DataMapperInterface;
use InvalidArgumentException;

class DataGridFactory implements DataGridFactoryInterface
{
    /**
     * @var DataGridInterface[]
     */
    protected $dataGrids = [];

    /**
     * @var ColumnTypeInterface[]
     */
    protected $columnTypes = [];

    /**
     * @var DataMapperInterface
     */
    protected $dataMapper;

    /**
     * @var DataGridExtensionInterface[]
     */
    protected $extensions = [];

    /**
     * @param DataGridExtensionInterface[] $extensions
     * @param DataMapperInterface $dataMapper
     * @throws InvalidArgumentException
     */
    public function __construct(array $extensions, DataMapperInterface $dataMapper)
    {
        foreach ($extensions as $extension) {
            if (!$extension instanceof DataGridExtensionInterface) {
                throw new InvalidArgumentException(sprintf(
                    'Each extension must implement "%s"',
                    DataGridExtensionInterface::class
                ));
            }
        }

        $this->dataMapper = $dataMapper;
        $this->extensions = $extensions;
    }

    public function createDataGrid(string $name = 'grid'): DataGridInterface
    {
        if (array_key_exists($name, $this->dataGrids)) {
            throw new DataGridColumnException(sprintf(
                'Datagrid name "%s" is not uniqe, it was used before to create datagrid',
                $name
            ));
        }

        $this->dataGrids[$name] = new DataGrid($name, $this, $this->dataMapper);

        return $this->dataGrids[$name];
    }

    public function hasColumnType(string $type): bool
    {
        if (array_key_exists($type, $this->columnTypes)) {
            return true;
        }

        try {
            $this->loadColumnType($type);
        } catch (UnexpectedTypeException $e) {
            return false;
        }

        return true;
    }

    /**
     * @param string $type
     * @return ColumnTypeInterface
     * @throws UnexpectedTypeException
     */
    public function getColumnType(string $type): ColumnTypeInterface
    {
        if ($this->hasColumnType($type)) {
            return clone $this->columnTypes[$type];
        }

        $this->loadColumnType($type);

        return clone $this->columnTypes[$type];
    }

    public function getExtensions(): array
    {
        return $this->extensions;
    }

    public function getDataMapper(): DataMapperInterface
    {
        return $this->dataMapper;
    }

    /**
     * @param string $type
     * @throws UnexpectedTypeException
     */
    private function loadColumnType(string $type): void
    {
        if (isset($this->columnTypes[$type])) {
            return;
        }

        $typeInstance = null;
        foreach ($this->extensions as $extension) {
            if ($extension->hasColumnType($type)) {
                $typeInstance = $extension->getColumnType($type);
                break;
            }
        }

        if (null === $typeInstance) {
            throw new UnexpectedTypeException(sprintf(
                'There is no column with type "%s" registered in factory.',
                $type
            ));
        }

        foreach ($this->extensions as $extension) {
            if ($extension->hasColumnTypeExtensions($type)) {
                $columnExtensions = $extension->getColumnTypeExtensions($type);
                foreach ($columnExtensions as $columnExtension) {
                    $typeInstance->addExtension($columnExtension);
                }
            }
        }

        $this->columnTypes[$type] = $typeInstance;
    }
}
