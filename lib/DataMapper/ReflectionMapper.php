<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\DataMapper;

use FSi\Component\Reflection\ReflectionClass;
use FSi\Component\DataGrid\Exception\DataMappingException;

class ReflectionMapper implements DataMapperInterface
{
    /**
     * @inheritdoc
     */
    public function getData(string $field, $object)
    {
        if (!is_object($object)) {
            throw new DataMappingException('Reflection mapper needs object to retrieve data.');
        }

        $objectReflection = ReflectionClass::factory($object);
        $camelField = $this->camelize($field);
        $getter = 'get' . $camelField;
        $isser = 'is' . $camelField;
        $hasser = 'has' . $camelField;

        if ($objectReflection->hasMethod($getter)) {
            if (!$objectReflection->getMethod($getter)->isPublic()) {
                throw new DataMappingException(
                    sprintf('Method "%s()" is not public in class "%s"', $getter, $objectReflection->name)
                );
            }

            return $object->$getter();
        }

        if ($objectReflection->hasMethod($isser)) {
            if (!$objectReflection->getMethod($isser)->isPublic()) {
                throw new DataMappingException(
                    sprintf('Method "%s()" is not public in class "%s"', $isser, $objectReflection->name)
                );
            }

            return $object->$isser();
        }

        if ($objectReflection->hasMethod($hasser)) {
            if (!$objectReflection->getMethod($hasser)->isPublic()) {
                throw new DataMappingException(
                    sprintf('Method "%s()" is not public in class "%s"', $hasser, $objectReflection->name)
                );
            }

            return $object->$hasser();
        }

        if ($objectReflection->hasProperty($field)) {
            if (!$objectReflection->getProperty($field)->isPublic()) {
                throw new DataMappingException(sprintf(
                    'Property "%s" is not public in class "%s". Maybe you should create the method "%s()" or "%s()"?',
                    $field,
                    $objectReflection->name,
                    $getter,
                    $isser
                ));
            }
            $property = $objectReflection->getProperty($field);
            return $property->getValue($object);
        }

        throw new DataMappingException(sprintf(
            'Neither property "%s" nor method "%s()" nor method "%s()" exists in class "%s"',
            $field,
            $getter,
            $isser,
            $objectReflection->name
        ));
    }

    /**
     * @inheritdoc
     */
    public function setData(string $field, $object, $value): void
    {
        if (!is_object($object)) {
            throw new DataMappingException('Reflection mapper needs object to retrieve data.');
        }

        $objectReflection = ReflectionClass::factory($object);
        $camelField = $this->camelize($field);
        $setter = 'set' . $camelField;
        $adder = 'add' . $camelField;

        if ($objectReflection->hasMethod($setter)) {
            if (!$objectReflection->getMethod($setter)->isPublic()) {
                throw new DataMappingException(sprintf(
                    'Method "%s()" is not public in class "%s"',
                    $setter,
                    $objectReflection->name
                ));
            }

            $object->$setter($value);

            return;
        }

        if ($objectReflection->hasMethod($adder)) {
            if (!$objectReflection->getMethod($adder)->isPublic()) {
                throw new DataMappingException(sprintf(
                    'Method "%s()" is not public in class "%s"',
                    $adder,
                    $objectReflection->name
                ));
            }

            $object->$adder($value);

            return;
        }

        if ($objectReflection->hasProperty($field)) {
            if (!$objectReflection->getProperty($field)->isPublic()) {
                throw new DataMappingException(sprintf(
                    'Property "%s" is not public in class "%s". Maybe you should create method "%s()" or "%s()"?',
                    $field,
                    $objectReflection->name,
                    $setter,
                    $adder
                ));
            }

            $property = $objectReflection->getProperty($field);
            $property->setValue($object, $value);

            return;
        }

        throw new DataMappingException(sprintf(
            'Neither property "%s" nor method "%s()" exists in class "%s"',
            $setter,
            $adder,
            $objectReflection->name
        ));
    }

    private function camelize(string $string): string
    {
        return preg_replace_callback('/(^|_|\.)+(.)/', function ($match) {
            return ('.' === $match[1] ? '_' : '').strtoupper($match[2]);
        }, $string);
    }
}
