<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\DataMapper;

use FSi\Component\DataGrid\Exception\DataMappingExteption;
use FSi\Component\DataGrid\DataMapper\DataMapperInterface;

class ChainMapper implements DataMapperInterface
{
    protected $mappers = array();

    public function __construct(array $mappers)
    {
        if (!count($mappers)) {
            throw new \InvalidArgumentException('There must be at least one mapper in chain.');
        }

        foreach ($mappers as $mapper) {
            if (!($mapper instanceof DataMapperInterface)) {
                throw new \InvalidArgumentException('Mapper needs to implement FSi\Component\DataGrid\DataMapper\DataMapperInterface');
            }
            $this->mappers[] = $mapper;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getData($field, $object)
    {
        $data = null;
        $dataFound = false;
        $lastMsg = null;
        foreach ($this->mappers as $mapper) {
            try {
                $data = $mapper->getData($field, $object);
            } catch (DataMappingExteption $e) {
                $data = null;
                $lastMsg = $e->getMessage();
                continue;
            }

            $dataFound = true;
            break;
        }

        if (!$dataFound) {
            if (!isset($lastMsg)) {
                $lastMsg = sprintf('Cant find any data that fit "%s" field.', $field);
            }
            throw new DataMappingExteption($lastMsg);
        }

        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function setData($field, $object, $value)
    {
        $data = null;
        $dataChanged = false;
        $lastMsg = null;

        foreach ($this->mappers as $mapper) {
            try {
                $mapper->setData($field, $object, $value);
            } catch (DataMappingExteption $e) {
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

            throw new DataMappingExteption($lastMsg);
        }

        return true;
    }
}
