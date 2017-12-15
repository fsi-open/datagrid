<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\DataMapper;

use FSi\Component\DataGrid\Exception\DataMappingException;
use InvalidArgumentException;

class ChainMapper implements DataMapperInterface
{
    /**
     * @var DataMapperInterface[]
     */
    protected $mappers = [];

    /**
     * @param DataMapperInterface[] $mappers
     * @throws InvalidArgumentException
     */
    public function __construct(array $mappers)
    {
        if (!count($mappers)) {
            throw new InvalidArgumentException('There must be at least one mapper in chain.');
        }

        foreach ($mappers as $mapper) {
            if (!$mapper instanceof DataMapperInterface) {
                throw new InvalidArgumentException(
                    sprintf('Mapper needs to implement "%s"', DataMapperInterface::class)
                );
            }

            $this->mappers[] = $mapper;
        }
    }

    /**
     * @inheritdoc
     */
    public function getData(string $field, $object)
    {
        $data = null;
        $dataFound = false;
        $lastMsg = null;

        foreach ($this->mappers as $mapper) {
            try {
                $data = $mapper->getData($field, $object);
            } catch (DataMappingException $e) {
                $data = null;
                $lastMsg = $e->getMessage();

                continue;
            }

            $dataFound = true;
            break;
        }

        if (!$dataFound) {
            if (null === $lastMsg) {
                $lastMsg = sprintf('Cant find any data that fit "%s" field.', $field);
            }

            throw new DataMappingException($lastMsg);
        }

        return $data;
    }

    /**
     * @inheritdoc
     */
    public function setData(string $field, $object, $value): void
    {
        $dataChanged = false;
        $lastMsg = null;

        foreach ($this->mappers as $mapper) {
            try {
                $mapper->setData($field, $object, $value);
            } catch (DataMappingException $e) {
                $lastMsg = $e->getMessage();
                continue;
            }

            $dataChanged = true;
            break;
        }

        if (!$dataChanged) {
            if (!isset($lastMsg)) {
                $lastMsg = sprintf('Cant find any data that fit "%s" field.', $field);
            }

            throw new DataMappingException($lastMsg);
        }
    }
}
