<?php
/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Tests\Extension\Gedmo\ColumnType;

use FSi\Component\DataGrid\Tests\Fixtures\EntityTree;
use FSi\Component\DataGrid\Tests\Fixtures\EntityManagerMock;
use FSi\Component\DataGrid\Tests\Fixtures\EventManagerMock;
use FSi\Component\DataGrid\Extension\Gedmo\ColumnType\Tree;

class TreeTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testWrongValue()
    {
        if (!interface_exists('Doctrine\Common\Persistence\ManagerRegistry')) {
            $this->markTestSkipped('Doctrine\Common\Persistence\ManagerRegistry is required for testGetValue in gedmo.tree column type');
        }

        $registry = $this->getMock('Doctrine\Common\Persistence\ManagerRegistry');
        $column = new Tree($registry);
        $object = 'This is string, not object';

        $this->setExpectedException('InvalidArgumentException');
        $column->getValue($object);
    }

    public function testGetValue()
    {
        if (!interface_exists('Doctrine\Common\Persistence\ManagerRegistry')
            || !class_exists('Gedmo\Tree\TreeListener')) {
            $this->markTestSkipped('Doctrine\Common\Persistence\ManagerRegistry is required for testGetValue in gedmo.tree column type');
        }

        $dataGrid = $this->getMock('FSi\Component\DataGrid\DataGridInterface');
        $registry = $this->getMock('Doctrine\Common\Persistence\ManagerRegistry');
        $indexingStrategy = $this->getMock('FSi\Component\DataGrid\Data\IndexingStrategyInterface');

        $treeListener = $this->getMock('Gedmo\Tree\TreeListener');
        $strategy = $this->getMock('Gedmo\Tree\Strategy');
        $evm = new EventManagerMock(array($treeListener));
        $em = new EntityManagerMock();
        $em->_setEventManager($evm);

        $dataMapper = $this->getMock('FSi\Component\DataGrid\DataMapper\DataMapperInterface');

        $registry->expects($this->any())
            ->method('getManager')
            ->will($this->returnValue($em));

        $treeListener->expects($this->once())
            ->method('getStrategy')
            ->will($this->returnValue($strategy));

        $treeListener->expects($this->once())
            ->method('getConfiguration')
            ->will($this->returnValue(
                array(
                    'left' => 'left',
                    'right' => 'right',
                    'root' => 'root',
                    'level' => 'level',
                    'parent' => 'parent'
                )
            ));

        $strategy->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('nested'));

        $dataMapper->expects($this->any())
            ->method('getData')
            ->will($this->returnValue('foo'));

        $dataGrid->expects($this->once())
            ->method('getIndexingStrategy')
            ->will($this->returnValue($indexingStrategy));

        $indexingStrategy->expects($this->any())
            ->method('getIndex')
            ->will($this->returnCallback(function($object, $dataMapper){

                return $dataMapper->getData($object, 'foo');
            }));

        $column = new Tree($registry);
        $column->setName('tree');
        $column->setDataMapper($dataMapper);
        $column->setOption('mapping_fields', array('foo'));
        $column->setDataGrid($dataGrid);
        $object = new EntityTree();

        $column->getValue($object);

        $this->assertSame(
            array(
                "id" => "foo",
                "root" => "root",
                "parent" => "foo",
                "left" => "left",
                "right" => "right",
                "level" => "level",
                "children" => 2,
            ),
            $column->getViewAttributes()
        );
    }
}
