<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\Table\DataMapper;

use FSi\Component\Table\Exception\DataMappingExteption;
use FSi\Component\Table\DataMapper\DataMapperInterface;

class ChainMapper implements DataMapperInterface 
{
    protected $mappers = array();
    
    public function __construct(array $mappers)
    {
        foreach ($mappers as $mapper) {
            if (!($mapper instanceof DataMapperInterface))
                throw new UnexpectedTypeException($mapper, 'FSi\Component\Table\DataMapper\DataMapperInterface');
            
            $this->mappers[] = $mapper;
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function getData($field, $object)
    {
        $data = null;
        $dataFound = false;
        
        foreach ($this->mappers as $mapper) {
            try {
                $data = $mapper->getData($field, $object);
            } catch (DataMappingExteption $e) {
                $data = null;
                continue;
            }

            $dataFound = true;
            break;
        }
        
        if (!$dataFound)
            throw new DataMappingExteption(sprintf('Cant find any data that fit "%s" field.', $field));

        return $data;
    }
}