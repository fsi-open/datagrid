<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Tests\Extension\Symfony\ColumnTypeExtension;

use Doctrine\ORM\Mapping\ClassMetadata;
use FSi\Component\DataGrid\Extension\Symfony\ColumnTypeExtension\FormExtension;
use FSi\Component\DataGrid\Tests\Fixtures\EntityCategory;
use Symfony\Bridge\Doctrine\Form\DoctrineOrmExtension;
use Symfony\Component\Form\Extension\Csrf\CsrfExtension;
use Symfony\Component\Form\Extension\Csrf\CsrfProvider\DefaultCsrfProvider;
use Symfony\Component\Form\FormRegistry;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\ResolvedFormTypeFactory;
use Symfony\Component\Form\Extension\Core\CoreExtension;
use FSi\Component\DataGrid\Tests\Fixtures\Entity;

class FormExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FormExtension
     */
    private $extension;

    protected function setUp()
    {
        if (!class_exists('Symfony\Component\Form\FormRegistry')) {
            $this->markTestSkipped('Symfony Column Extension require Symfony\Component\Form\FormRegistry class.');
        }

        $repository = $this->getMock('Doctrine\Common\Persistence\ObjectRepository');
        $repository->expects($this->any())
            ->method('findAll')
            ->will($this->returnValue(array(
                new EntityCategory(1, 'category name 1'),
                new EntityCategory(2, 'category name 2'),
            )));

        $entityClass = 'FSi\Component\DataGrid\Tests\Fixtures\EntityCategory';
        $classMetadata = new ClassMetadata('FSi\Component\DataGrid\Tests\Fixtures\EntityCategory');
        $classMetadata->identifier = array('id');
        $classMetadata->fieldMappings = array(
            'id' => array(
                'fieldName' => 'id',
                'type' => 'integer',
            )
        );
        $classMetadata->reflFields = array(
            'id' => new \ReflectionProperty($entityClass, 'id'),
        );

        $objectManager = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $objectManager->expects($this->any())
            ->method('getClassMetadata')
            ->with($this->equalTo('FSi\Component\DataGrid\Tests\Fixtures\EntityCategory'))
            ->will($this->returnValue($classMetadata));
        $objectManager->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($repository));
        $objectManager->expects($this->any())
            ->method('contains')
            ->will($this->returnValue(true));

        $managerRegistry = $this->getMock('Doctrine\Common\Persistence\ManagerRegistry');
        $managerRegistry->expects($this->any())
            ->method('getManagerForClass')
            ->will($this->returnValue($objectManager));
        $managerRegistry->expects($this->any())
            ->method('getManagers')
            ->will($this->returnValue(array()));

        $resolvedTypeFactory = new ResolvedFormTypeFactory();
        $formRegistry = new FormRegistry(array(
                new CoreExtension(),
                new DoctrineOrmExtension($managerRegistry),
                new CsrfExtension(new DefaultCsrfProvider('test'))
            ),
            $resolvedTypeFactory
        );

        $formFactory = new FormFactory($formRegistry, $resolvedTypeFactory);

        $dataGrid = $this->getMock('FSi\Component\DataGrid\DataGridInterface');
        $dataGrid->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('grid'));

        $this->extension = new FormExtension($formFactory);
        $this->extension->setDataGrid($dataGrid);
    }

    public function testSimpleBindData()
    {
        $column = $this->createColumnMock();
        $this->setColumnId($column, 'text');
        $this->setColumnOptions($column, array(
            'field_mapping' => array('name', 'author'),
            'editable' => true,
            'form_options' => array(),
            'form_type' => array(
                'name' => array('type' => 'text'),
                'author' => array('type' => 'text'),
            )
        ));

        $object = new Entity('old_name');
        $data = array(
            'name' => 'object',
            'author' => 'norbert@fsi.pl',
            'invalid_data' => 'test'
        );

        $this->extension->bindData($column, $data, $object, 1);

        $this->assertSame('norbert@fsi.pl', $object->getAuthor());
        $this->assertSame('object', $object->getName());
    }

    public function testEntityBindData()
    {
        $nestedEntityClass = 'FSi\Component\DataGrid\Tests\Fixtures\EntityCategory';

        $column = $this->createColumnMock();
        $this->setColumnId($column, 'entity');
        $this->setColumnOptions($column, array(
            'editable' => true,
            'relation_field' => 'category',
            'field_mapping' => array('name'),
            'form_options' => array(
                'category' => array(
                    'class' => $nestedEntityClass,
                )
            ),
            'form_type' => array(),
        ));

        $object = new Entity('name123');
        $data = array(
            'category' => 1,
        );

        $this->assertSame($object->getCategory(), null);

        $this->extension->bindData($column, $data, $object, 1);

        $this->assertInstanceOf($nestedEntityClass, $object->getCategory());
        $this->assertSame('category name 1', $object->getCategory()->getName());
    }

    private function createColumnMock()
    {
        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $column->expects($this->any())
            ->method('getDataMapper')
            ->will($this->getDataMapperReturnCallback());

        return $column;
    }

    private function setColumnId($column, $id)
    {
        $column->expects($this->any())
            ->method('getId')
            ->will($this->returnValue($id));
    }

    private function setColumnOptions($column, $optionsMap)
    {
        $column->expects($this->any())
            ->method('getOption')
            ->will($this->returnCallback(function($option) use($optionsMap) {
                return $optionsMap[$option];
            }));
    }

    private function getDataMapperReturnCallback()
    {
        $dataMapper = $this->getMock('FSi\Component\DataGrid\DataMapper\DataMapperInterface');
        $dataMapper->expects($this->any())
            ->method('getData')
            ->will($this->returnCallback(function($field, $object){
                $method = 'get' . ucfirst($field);
                return $object->$method();
            }));

        $dataMapper->expects($this->any())
            ->method('setData')
            ->will($this->returnCallback(function($field, $object, $value){
                $method = 'set' . ucfirst($field);
                return $object->$method($value);
            }));

        return $this->returnCallback(function() use ($dataMapper) {
            return $dataMapper;
        });
    }
}
