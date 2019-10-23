<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Tests\DataMapper;

use FSi\Component\DataGrid\DataMapper\ChainMapper;
use FSi\Component\DataGrid\Exception\DataMappingException;
use FSi\Component\DataGrid\DataMapper\DataMapperInterface;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ChainMapperTest extends TestCase
{
    public function testMappersInChainWithInvalidMappers()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Mapper needs to implement "%s"', DataMapperInterface::class));
        new ChainMapper([
            'foo',
            'bar'
        ]);
    }

    public function testMappersInChainWithEmptyMappersArray()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Mapper needs to implement "%s"', DataMapperInterface::class));
        new ChainMapper([
            'foo',
            'bar'
        ]);
    }

    public function testGetDataFromTwoMappers()
    {
        $mapper = $this->createMock(DataMapperInterface::class);
        $mapper1 = $this->createMock(DataMapperInterface::class);

        $mapper->expects($this->once())
               ->method('getData')
               ->will($this->throwException(new DataMappingException));

        $mapper1->expects($this->once())
               ->method('getData')
               ->will($this->returnValue('foo'));

        $chain = new ChainMapper([$mapper, $mapper1]);

        $this->assertSame(
            'foo',
            $chain->getData('foo', 'bar')
        );
    }

    public function testSetDataWithTwoMappers()
    {
        $mapper = $this->createMock(DataMapperInterface::class);
        $mapper1 = $this->createMock(DataMapperInterface::class);

        $mapper->expects($this->once())
               ->method('setData')
               ->will($this->throwException(new DataMappingException));

        $mapper1->expects($this->once())
               ->method('setData')
               ->with('foo', 'bar', 'test')
               ->will($this->returnValue(true));

        $chain = new ChainMapper([$mapper, $mapper1]);

        $chain->setData('foo', 'bar', 'test');
    }
}
