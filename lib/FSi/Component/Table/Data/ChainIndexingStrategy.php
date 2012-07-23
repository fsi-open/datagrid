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

use FSi\Component\Table\Data\IndexingStrategyInterface;

class ChainIndexingStrategy implements IndexingStrategyInterface
{
    protected $strategies;
    
    public function __construct(array $strategies)
    {
        foreach ($strategies as $strategy) {
            if (!($strategy instanceof IndexingStrategyInterface))
                throw new UnexpectedTypeException($mapper, 'FSi\Component\Table\Data\IndexingStrategyInterface');
        }
        
        $this->strategies = $strategies;
    }

    public function getIndex($object)
    {
        foreach ($this->strategies as $strategy) {
            $index = $strategy->getIndex($object);
            if (!is_null($index))
                return $index;
        }
        
        return null;
    }
}