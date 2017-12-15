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
use Symfony\Component\PropertyAccess\Exception\RuntimeException;
use Symfony\Component\PropertyAccess\PropertyAccess;

class PropertyAccessorMapper implements DataMapperInterface
{
    /**
     * @inheritdoc
     */
    public function getData(string $field, $object)
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        try {
            $data = $accessor->getValue($object, $field);
        } catch (RuntimeException $e) {
            throw new DataMappingException($e->getMessage());
        }

        return $data;
    }

    /**
     * @inheritdoc
     */
    public function setData(string $field, $object, $value): void
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        try {
            $accessor->setValue($object, $field, $value);
        } catch (RuntimeException $e) {
            throw new DataMappingException($e->getMessage());
        }
    }
}
