<?php
/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Tests\Data;

use FSi\Component\DataGrid\Data\ChainIndexingStrategy;

class ChainIndexingStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testIndexingStrategyWithInvalidStrategies()
    {
        $this->setExpectedException('InvalidArgumentException');
        $strategy = new ChainIndexingStrategy(array('test'));
    }

    public function testIndexingStrategyWithEmptyStrategiesArray()
    {
        $this->setExpectedException('InvalidArgumentException');
        $strategy = new ChainIndexingStrategy(array());
    }

    public function testGetIndex()
    {
        $strategy = $this->getMock('FSi\Component\DataGrid\Data\IndexingStrategyInterface');
        $strategy->expects($this->once())
                 ->method('getIndex')
                 ->will($this->returnValue(array('id')));

        $strategy = new ChainIndexingStrategy(array($strategy));

        $this->assertSame(array('id'), $strategy->getIndex('object'));
    }
}