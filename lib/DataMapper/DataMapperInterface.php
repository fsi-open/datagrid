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

interface DataMapperInterface
{
    /**
     * @param string $field
     * @param mixed $object
     * @return mixed
     * @throws DataMappingException
     */
    public function getData(string $field, $object);

    /**
     * @param string $field
     * @param mixed $object
     * @param mixed $value
     * @throws DataMappingException
     */
    public function setData(string $field, $object, $value): void;
}
