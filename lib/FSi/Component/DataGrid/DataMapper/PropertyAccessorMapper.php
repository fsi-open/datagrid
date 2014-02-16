<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\DataMapper;

use FSi\Component\DataGrid\Exception\DataMappingExteption;
use Symfony\Component\PropertyAccess\Exception\RuntimeException;
use Symfony\Component\PropertyAccess\PropertyAccess;

class PropertyAccessorMapper implements DataMapperInterface
{
    /**
     * {@inheritdoc}
     */
    public function getData($field, $object)
    {
        $accessor = PropertyAccess::getPropertyAccessor();

        try {
            $data = $accessor->getValue($object, $field);
        } catch (RuntimeException $e) {
            throw new DataMappingExteption($e->getMessage());
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function setData($field, $object, $value)
    {
        $accessor = PropertyAccess::getPropertyAccessor();

        try {
            $accessor->setValue($object, $field, $value);
        } catch (RuntimeException $e) {
            throw new DataMappingExteption($e->getMessage());
        }
    }
}
