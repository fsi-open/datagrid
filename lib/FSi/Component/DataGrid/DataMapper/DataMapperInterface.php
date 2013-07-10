<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\DataMapper;

interface DataMapperInterface
{
    /**
     * Get data from object for specified column type.
     *
     * @param string $field
     * @param mixed $object
     * @throws DataMappingExteption - thrown when mapper cant fit any object data into column
     * @return boolean - return false if can't get value from object
     */
    public function getData($field, $object);

    /**
     * Sets data to object for specified column type.
     *
     * @param string $field
     * @param mixed $object
     * @param mixed $value
     * @throws DataMappingExteption - thrown when mapper cant fit any object data into column
     * @return boolean - return true if value was correctly changed
     */
    public function setData($field, $object, $value);
}
