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
use FSi\Component\DataGrid\Data\EntityIndexingStrategy;
use FSi\Component\DataGrid\Tests\Fixtures\EntityManagerMock;

class EntityIndexingStrategyTest extends \PHPUnit_Framework_TestCase
{
    protected $dataMapper;

    protected function setUp()
    {
        $this->dataMapper = $this->getMock('FSi\Component\DataGrid\DataMapper\DataMapperInterface');
    }

    public function testInvalidObject()
    {
        $registry = $this->getMock('Doctrine\Common\Persistence\ManagerRegistry');

        $strategy = new EntityIndexingStrategy($registry);
        $this->assertSame(null, $strategy->getIndex('foo', $this->dataMapper));
    }

    public function testGetIndex()
    {
        $registry = $this->getMock('Doctrine\Common\Persistence\ManagerRegistry');
        $registry->expects($this->once())
            ->method('getManagerForClass')
            ->will($this->returnCallback(function() {

                $metadataFactory = $this->getMock('Doctrine\ORM\Mapping\ClassMetadataFactory');
                $metadataFactory->expects($this->once())
                    ->method('getMetadataFor')
                    ->will($this->returnCallback(function(){
                        $classMetadata = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata')
                                ->disableOriginalConstructor()
                                ->getMock();

                        $classMetadata->expects($this->once())
                                ->method('getIdentifierFieldNames')
                                ->will($this->returnValue(array('id')));

                        return $classMetadata;
                   }));

                $em = new EntityManagerMock();
                $em->_setMetadataFactory($metadataFactory);

                return $em;
            }));

        $strategy = new EntityIndexingStrategy($registry);

        $this->dataMapper->expects($this->once())
            ->method('getData')
            ->will($this->returnValue('test'));

        $this->assertSame('test', $strategy->getIndex(new Entity('test'), $this->dataMapper));
    }

    public function testRevertIndex()
    {
        $registry = $this->getMock('Doctrine\Common\Persistence\ManagerRegistry');
        $registry->expects($this->once())
            ->method('getManagerForClass')
            ->will($this->returnCallback(function() {

                $metadataFactory = $this->getMock('Doctrine\ORM\Mapping\ClassMetadataFactory');
                $metadataFactory->expects($this->once())
                    ->method('getMetadataFor')
                    ->will($this->returnCallback(function(){
                        $classMetadata = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata')
                            ->disableOriginalConstructor()
                            ->getMock();

                        $classMetadata->expects($this->once())
                            ->method('getIdentifierFieldNames')
                            ->will($this->returnValue(array('id')));

                        return $classMetadata;
                    }));

                $em = new EntityManagerMock();
                $em->_setMetadataFactory($metadataFactory);

                return $em;
            }));

        $index = 'test|id';

        $strategy = new EntityIndexingStrategy($registry);
        $this->assertSame(array('id' => 'test|id'), $strategy->revertIndex($index, 'Entity'));
    }

    public function testRevertIndexComposite()
    {
        $registry = $this->getMock('Doctrine\Common\Persistence\ManagerRegistry');
        $registry->expects($this->any())
        ->method('getManagerForClass')
        ->will($this->returnCallback(function() {

            $metadataFactory = $this->getMock('Doctrine\ORM\Mapping\ClassMetadataFactory');
            $metadataFactory->expects($this->any())
            ->method('getMetadataFor')
            ->will($this->returnCallback(function(){
                $classMetadata = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata')
                ->disableOriginalConstructor()
                ->getMock();

                $classMetadata->expects($this->any())
                ->method('getIdentifierFieldNames')
                ->will($this->returnValue(array('id', 'name')));

                return $classMetadata;
            }));

            $em = new EntityManagerMock();
            $em->_setMetadataFactory($metadataFactory);

            return $em;
        }));

        $strategy = new EntityIndexingStrategy($registry);
        foreach (array('_', '|') as $separator) {
            $index = '1'.$separator.'Foo';
            $strategy->setSeparator($separator);
            $this->assertSame(array('id' => '1', 'name' => 'Foo' ), $strategy->revertIndex($index, 'Entity'));
        }
    }
}