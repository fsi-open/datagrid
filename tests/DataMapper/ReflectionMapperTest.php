<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Tests\DataMapper;

use FSi\Component\DataGrid\DataMapper\ReflectionMapper;
use FSi\Component\DataGrid\Tests\Fixtures\EntityMapper;
use FSi\Component\DataGrid\Exception\DataMappingException;
use PHPUnit\Framework\TestCase;

class ReflectionMapperTest extends TestCase
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

        $this->expectException(DataMappingException::class);
        $this->expectExceptionMessage(sprintf('Method "getSurname()" is not public in class "%s"', EntityMapper::class));
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

        $this->expectException(DataMappingException::class);
        $this->expectExceptionMessage(sprintf('Method "hasPrivateCollection()" is not public in class "%s"', EntityMapper::class));
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

        $this->expectException(DataMappingException::class);
        $this->expectExceptionMessage(sprintf('Method "isProtectedReady()" is not public in class "%s"', EntityMapper::class));
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

        $this->expectException(DataMappingException::class);
        $this->expectExceptionMessage(sprintf('Property "private_id" is not public in class "%s"', EntityMapper::class));
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

        $this->expectException(DataMappingException::class);
        $this->expectExceptionMessage(sprintf('Method "setProtectedName()" is not public in class "%s"', EntityMapper::class));
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

        $this->expectException(DataMappingException::class);
        $this->expectExceptionMessage(sprintf('Method "addProtectedTag()" is not public in class "%s"', EntityMapper::class));
        $mapper->setData('protected_tag', $entity, 'bar');
    }
}
