<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\Table\Data;

interface IndexingStrategyInterface
{
    /**
     * Method should return unique index for passed object.
     * 
     * @param mixed $object
     * @return array|null - if method can't return index for object it returns null value
     * in other case it should return array of keys that should be used as indexes
     */
    public function getIndex($object);
}