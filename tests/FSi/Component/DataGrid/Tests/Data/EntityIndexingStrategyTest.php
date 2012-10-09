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
    public function testInvalidObject()
    {
        $em = new EntityManagerMock();
        $strategy = new EntityIndexingStrategy($em);

        $this->assertSame(null, $strategy->getIndex('foo'));
    }

    public function testGetIndexFailure()
    {
        $metadataFactory = $this->getMock('Doctrine\ORM\Mapping\ClassMetadataFactory');
        $metadataFactory->expects($this->once())
                        ->method('hasMetadataFor')
                        ->will($this->returnValue(false));

        $em = new EntityManagerMock();
        $em->_setMetadataFactory($metadataFactory);


        $strategy = new EntityIndexingStrategy($em);

        $this->assertSame(null, $strategy->getIndex(new Entity('test')));
    }

    public function testGetIndex()
    {
        $metadataFactory = $this->getMock('Doctrine\ORM\Mapping\ClassMetadataFactory');
        $metadataFactory->expects($this->once())
                        ->method('hasMetadataFor')
                        ->will($this->returnValue(true));

        $classMetadata = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata')
                            ->disableOriginalConstructor()
                            ->getMock();

        $classMetadata->expects($this->once())
                      ->method('getIdentifierColumnNames')
                      ->will($this->returnValue(array('id')));

        $metadataFactory->expects($this->once())
                        ->method('getMetadataFor')
                        ->will($this->returnValue($classMetadata));

        $em = new EntityManagerMock();
        $em->_setMetadataFactory($metadataFactory);

        $strategy = new EntityIndexingStrategy($em);

        $this->assertSame(array('id'), $strategy->getIndex(new Entity('test')));
    }
}