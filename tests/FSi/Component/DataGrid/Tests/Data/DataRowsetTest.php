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

use FSi\Component\DataGrid\Tests\Fixtures\Entity;

use FSi\Component\DataGrid\Data\DataRowset;

class DataRowsetTest extends \PHPUnit_Framework_TestCase
{
    protected $strategy;

    protected $dataMapper;

    protected function setUp()
    {
        $this->strategy = $this->getMock('FSi\Component\DataGrid\Data\IndexingStrategyInterface');
        $this->dataMapper = $this->getMock('FSi\Component\DataGrid\DataMapper\DataMapperInterface');
    }

    public function testSetDataWithInvalidData()
    {
        $rowset = new DataRowset($this->strategy, $this->dataMapper);
        $this->setExpectedException('InvalidArgumentException');
        $rowset->setData('broken data');
    }

    public function testSetData()
    {
        $this->strategy->expects($this->any())
            ->method('getIndex')
            ->will($this->returnValue(array('name')));

        $this->dataMapper->expects($this->any())
            ->method('getIndex')
            ->will($this->returnCallback(function($identifier, $object) {
                return $object->getName();
            }));

        $this->dataMapper->expects($this->any())
            ->method('getData')
            ->will($this->returnCallback(function($identifier, $object) {
                return $object->getName();
            }));


        $entity1 = new Entity('entity1');
        $entity2 = new Entity('entity2');
        $data = array(
            $entity1,
            $entity2
        );

        $rowset = new DataRowset($this->strategy, $this->dataMapper);
        $rowset->setData($data);
        $this->assertSame($entity1, $rowset->getObjectByIndex('entity1'));
        $this->assertSame(2, $rowset->count());
        $this->assertSame($entity1, $rowset->current());
        $rowset->next();
        $this->assertSame($entity2, $rowset->current());
    }
}