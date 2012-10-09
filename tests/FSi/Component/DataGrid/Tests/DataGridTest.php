<?php
/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Tests;

use FSi\Component\DataGrid\DataGrid;
use FSi\Component\DataGrid\Tests\Fixtures\FooExtension;
use FSi\Component\DataGrid\Tests\Fixtures\ColumnType\FooType;
use FSi\Component\DataGrid\Tests\Fixtures\Entity;

class DataGridTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DataGridFactoryInterface
     */
    private $factory;

    /**
     * @var IndexingStrategyInterface
     */
    private $indexingStrategy;

    /**
     * @var DataMapper
     */
    private $dataMapper;

    /**
     * @var DataGrid
     */
    private $datagrid;

    protected function setUp()
    {
        $this->dataMapper = $this->getMock('FSi\Component\DataGrid\DataMapper\DataMapperInterface');
        $this->dataMapper->expects($this->any())
                     ->method('getData')
                     ->will($this->returnCallback(function($field, $object){
                         switch($field) {
                             case 'name':
                                    return $object->getName();
                                 break;
                         }
                     }));
        $this->dataMapper->expects($this->any())
                     ->method('setData')
                     ->will($this->returnCallback(function($field, $object, $value){
                         switch($field) {
                             case 'name':
                                    return $object->setName($value);
                                 break;
                         }
                     }));

        $this->indexingStrategy = $this->getMock('FSi\Component\DataGrid\Data\IndexingStrategyInterface');
        $this->indexingStrategy->expects($this->any())
            ->method('getIndex')
            ->will($this->returnValue(
                array('name')
            ));

        $this->factory = $this->getMock('FSi\Component\DataGrid\DataGridFactoryInterface');
        $this->factory->expects($this->any())
            ->method('getExtensions')
            ->will($this->returnValue(array(
                new FooExtension(),
            )));
        $this->factory->expects($this->any())
            ->method('getColumnType')
            ->with($this->equalTo('foo'))
            ->will($this->returnValue(
                new FooType()
            ));

        $this->datagrid = new DataGrid('grid', $this->factory, $this->dataMapper, $this->indexingStrategy);
    }

    public function testGetName()
    {
        $this->assertSame('grid', $this->datagrid->getName());
    }

    public function testHasAddGetRemoveColumn()
    {
        $this->assertFalse($this->datagrid->hasColumn('foo1'));
        $this->datagrid->addColumn('foo1', 'foo');
        $this->assertTrue($this->datagrid->hasColumn('foo1'));

        $this->assertInstanceOf('FSi\Component\DataGrid\Tests\Fixtures\ColumnType\FooType', $this->datagrid->getColumn('foo1'));

        $this->setExpectedException('InvalidArgumentException');
        $this->datagrid->getColumn('bar');

        $this->assertTrue($this->datagrid->hasColumn('foo1'));
        $this->datagrid->removeColumn('foo1');
        $this->assertFalse($this->datagrid->hasColumn('foo1'));
    }

    public function testGetDataMapper()
    {
        $this->assertInstanceOf('FSi\Component\DataGrid\DataMapper\DataMapperInterface', $this->datagrid->getDataMapper());
    }

    public function testGetIndexingStrategy()
    {
        $this->assertInstanceOf('FSi\Component\DataGrid\Data\IndexingStrategyInterface', $this->datagrid->getIndexingStrategy());
    }

    public function testSetData()
    {
        $gridData = array(
            new Entity('entity1'),
            new Entity('entity2')
        );

        $this->datagrid->setData($gridData);

        $gridBrokenData = false;
        $this->setExpectedException('InvalidArgumentException');
        $this->datagrid->setData($gridBrokenData);
    }

    public function testBindData()
    {
        $gridBrokenData = false;
        $this->setExpectedException('InvalidArgumentException');
        $this->datagrid->bindData($gridBrokenData);
    }

    public function testCreateView()
    {
        $this->datagrid->addColumn('foo1', 'foo');
        $gridData = array(
            new Entity('entity1'),
            new Entity('entity2')
        );

        $this->datagrid->setData($gridData);
        $this->assertInstanceOf('FSi\Component\DataGrid\DataGridViewInterface',$this->datagrid->createView());
    }
}