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
    public function testMappersInChainWithInvalidMappers(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Mapper needs to implement "%s"', DataMapperInterface::class));
        new ChainMapper(['foo', 'bar']);
    }

    public function testMappersInChainWithEmptyMappersArray(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('There must be at least one mapper in chain.');
        new ChainMapper([]);
    }

    public function testGetDataFromTwoMappers(): void
    {
        $mapper = $this->createMock(DataMapperInterface::class);
        $mapper1 = $this->createMock(DataMapperInterface::class);

        $mapper->expects(self::once())->method('getData')->willThrowException(new DataMappingException());
        $mapper1->expects(self::once())->method('getData')->willReturn('foo');

        $chain = new ChainMapper([$mapper, $mapper1]);

        self::assertSame('foo', $chain->getData('foo', 'bar'));
    }

    public function testSetDataWithTwoMappers(): void
    {
        $mapper = $this->createMock(DataMapperInterface::class);
        $mapper1 = $this->createMock(DataMapperInterface::class);

        $mapper->expects(self::once())->method('setData')->willThrowException(new DataMappingException());
        $mapper1->expects(self::once())->method('setData')->with('foo', 'bar', 'test');

        $chain = new ChainMapper([$mapper, $mapper1]);

        $chain->setData('foo', 'bar', 'test');
    }
}
