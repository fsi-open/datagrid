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

use FSi\Component\Reflection\ReflectionClass; 
use FSi\Component\Table\Exception\DataMappingExteption;
use FSi\Component\Table\DataMapper\DataMapperInterface;

class ReflectionMapper implements DataMapperInterface 
{

    /**
     * {@inheritdoc}
     */
    public function getData($field, $object)
    {    
        if (!is_object($object))
            throw new DataMappingExteption('Reflection mapper need object to retrieve data.');
            
        $objectReflection = ReflectionClass::factory($object);
        
        $propertiesReflection = $objectReflection->getProperties();
        
        foreach ($propertiesReflection as $property) {
            if (strcmp($property->name, $field) == 0) {
                $property->setAccessible(true);
                return $property->getValue($object);
            }
        }
        
        throw new DataMappingExteption(sprintf('Cant find any data that fit "%s" field.', $field));
    }
}