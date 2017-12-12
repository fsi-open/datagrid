<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
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
     * @var DataMapper
     */
    private $dataMapper;

    /**
     * @var DataGrid
     */
    private $datagrid;

    protected function setUp()
    {
        $this->dataMapper = $this->createMock('FSi\Component\DataGrid\DataMapper\DataMapperInterface');
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

        $this->factory = $this->createMock('FSi\Component\DataGrid\DataGridFactoryInterface');
        $this->factory->expects($this->any())
            ->method('getExtensions')
            ->will($this->returnValue([
                new FooExtension(),
            ]));

        $this->factory->expects($this->any())
            ->method('getColumnType')
            ->with($this->equalTo('foo'))
            ->will($this->returnValue(
                new FooType()
            ));

        $this->factory->expects($this->any())
            ->method('hasColumnType')
            ->with($this->equalTo('foo'))
            ->will($this->returnValue(true));

        $this->datagrid = new DataGrid('grid', $this->factory, $this->dataMapper);
    }

    public function testGetName()
    {
        $this->assertSame('grid', $this->datagrid->getName());
    }

    public function testHasAddGetRemoveClearColumn()
    {
        $this->assertFalse($this->datagrid->hasColumn('foo1'));
        $this->datagrid->addColumn('foo1', 'foo');
        $this->assertTrue($this->datagrid->hasColumn('foo1'));
        $this->assertTrue($this->datagrid->hasColumnType('foo'));
        $this->assertFalse($this->datagrid->hasColumnType('this_type_cant_exists'));

        $this->assertInstanceOf('FSi\Component\DataGrid\Tests\Fixtures\ColumnType\FooType', $this->datagrid->getColumn('foo1'));

        $this->assertTrue($this->datagrid->hasColumn('foo1'));
        $column = $this->datagrid->getColumn('foo1');

        $this->datagrid->removeColumn('foo1');
        $this->assertFalse($this->datagrid->hasColumn('foo1'));

        $this->datagrid->addColumn($column);
        $this->assertEquals($column, $this->datagrid->getColumn('foo1'));

        $this->assertEquals(1, count($this->datagrid->getColumns()));

        $this->datagrid->clearColumns();
        $this->assertEquals(0, count($this->datagrid->getColumns()));

        $this->setExpectedException('InvalidArgumentException');
        $this->datagrid->getColumn('bar');
    }

    public function testGetDataMapper()
    {
        $this->assertInstanceOf('FSi\Component\DataGrid\DataMapper\DataMapperInterface', $this->datagrid->getDataMapper());
    }

    public function testSetData()
    {
        $gridData = [
            new Entity('entity1'),
            new Entity('entity2')
        ];

        $this->datagrid->setData($gridData);

        $this->assertEquals(count($gridData), count($this->datagrid->createView()));

        $gridData = [
            ['some', 'data'],
            ['next', 'data']
        ];

        $this->datagrid->setData($gridData);

        $this->assertEquals(count($gridData), count($this->datagrid->createView()));

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
        $gridData = [
            new Entity('entity1'),
            new Entity('entity2')
        ];

        $this->datagrid->setData($gridData);
        $this->assertInstanceOf('FSi\Component\DataGrid\DataGridViewInterface',$this->datagrid->createView());
    }

    public function testSetDataForArray()
    {
        $gridData = [
            ['one'],
            ['two'],
            ['three'],
            ['four'],
            ['bazinga!'],
            ['five'],
        ];

        $this->datagrid->setData($gridData);
        $view = $this->datagrid->createView();

        $keys = [];
        foreach ($view as $row) {
            $keys[] = $row->getIndex();
        }

        $this->assertEquals(array_keys($gridData), $keys);
    }
}
