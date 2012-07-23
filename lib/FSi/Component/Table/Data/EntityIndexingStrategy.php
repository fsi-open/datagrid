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
use Doctrine\ORM\EntityManager;

class EntityIndexingStrategy implements IndexingStrategyInterface
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;        
    }
    
    public function getIndex($object)
    {
        if (!is_object($object)){
            return null;
        }
            
        $class = get_class($object);
        $metadataFactory = $this->em->getMetadataFactory();
        
        if (!$metadataFactory->hasMetadataFor($class)) {
            return null;
        }
        
        $metadata = $metadataFactory->getMetadataFor($class);
        return $metadata->getIdentifierColumnNames();
    }
}