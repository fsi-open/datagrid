<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Tests\DataMapper;

use FSi\Component\DataGrid\DataMapper\ReflectionMapper;
use FSi\Component\DataGrid\Tests\Fixtures\EntityMapper;
use FSi\Component\DataGrid\Exception\DataMappingException;

class ReflectionMapperTest extends \PHPUnit_Framework_TestCase
{
    public function testGetter()
    {
        $mapper = new ReflectionMapper();
        $entity = new EntityMapper();
        $entity->setName('fooname');

        $this->assertSame('fooname',$mapper->getData('name', $entity));
    }

    public function testProtectedGetter()
    {
        $mapper = new ReflectionMapper();
        $entity = new EntityMapper();
        $entity->setSurname('foosurname');

        $this->setExpectedException(DataMappingException::class);
        $mapper->getData('surname', $entity);
    }

    public function testHaser()
    {
        $mapper = new ReflectionMapper();
        $entity = new EntityMapper();
        $entity->setCollection('collection');

        $this->assertTrue($mapper->getData('collection', $entity));
    }

    public function testProtectedHaser()
    {
        $mapper = new ReflectionMapper();
        $entity = new EntityMapper();
        $entity->setPrivateCollection('collection');

        $this->setExpectedException(DataMappingException::class);
        $mapper->getData('private_collection', $entity);
    }

    public function testIser()
    {
        $mapper = new ReflectionMapper();
        $entity = new EntityMapper();
        $entity->setReady(true);

        $this->assertTrue($mapper->getData('ready', $entity));
    }

    public function testProtectedIser()
    {
        $mapper = new ReflectionMapper();
        $entity = new EntityMapper();
        $entity->setProtectedReady(true);

        $this->setExpectedException(DataMappingException::class);
        $mapper->getData('protected_ready', $entity);
    }

    public function testProperty()
    {
        $mapper = new ReflectionMapper();
        $entity = new EntityMapper();
        $entity->setId('bar');

        $this->assertSame('bar',$mapper->getData('id', $entity));
    }

    public function testPrivateProperty()
    {
        $mapper = new ReflectionMapper();
        $entity = new EntityMapper();
        $entity->setPrivateId('bar');

        $this->setExpectedException(DataMappingException::class);
        $mapper->getData('private_id', $entity);
    }

    public function testSetter()
    {
        $mapper = new ReflectionMapper();
        $entity = new EntityMapper();

        $mapper->setData('name', $entity, 'fooname');
        $this->assertSame('fooname',$entity->getName());
    }

    public function testProtectedSetter()
    {
        $mapper = new ReflectionMapper();
        $entity = new EntityMapper();

        $this->setExpectedException(DataMappingException::class);
        $mapper->setData('protected_name', $entity, 'fooname');
    }

    public function testAdder()
    {
        $mapper = new ReflectionMapper();
        $entity = new EntityMapper();

        $mapper->setData('tag', $entity, 'bar');
        $this->assertSame(['bar'],$entity->getTags());
    }

    public function testProtectedAdder()
    {
        $mapper = new ReflectionMapper();
        $entity = new EntityMapper();

        $this->setExpectedException(DataMappingException::class);
        $mapper->setData('protected_tag', $entity, 'bar');
    }
}
