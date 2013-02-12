<?php
/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Tests\Extension\Symfony\ColumnTypeExtension;

use FSi\Component\DataGrid\Extension\Symfony\ColumnTypeExtension\FormExtension;
use Symfony\Component\Form\FormRegistry;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\ResolvedFormTypeFactory;
use Symfony\Component\Form\Extension\Core\CoreExtension;
use Symfony\Component\Form\Extension\Csrf\CsrfExtension;
use FSi\Component\DataGrid\Tests\Fixtures\Entity;

class FormExtensionTest extends \PHPUnit_Framework_TestCase
{
    private $formFactory;

    protected function setUp()
    {
        if (!class_exists('Symfony\Component\Form\FormRegistry')) {
            $this->markTestSkipped('Symfony Column Extension require Symfony\Component\Form\FormRegistry class.');
        }
        $resolvedTypeFactory = new ResolvedFormTypeFactory();
        $formRegistry = new FormRegistry(array(
                new CoreExtension()
            ),
            $resolvedTypeFactory
        );

        $this->formFactory = new FormFactory($formRegistry, $resolvedTypeFactory);
    }

    public function testBindData()
    {
        $self = $this;

        $dataGrid = $this->getMock('FSi\Component\DataGrid\DataGridInterface');
        $dataGrid->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('grid'));

        $extension = new FormExtension($this->formFactory);
        $extension->setDataGrid($dataGrid);
        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $column->expects($this->any())
            ->method('getId')
            ->will($this->returnValue('text'));

        $column->expects($this->any())
            ->method('getDataMapper')
            ->will($this->returnCallback(function() use ($self) {
                $dataMapper = $self->getMock('FSi\Component\DataGrid\DataMapper\DataMapperInterface');
                $dataMapper->expects($self->any())
                    ->method('getData')
                    ->will($self->returnCallback(function($field, $object){
                        $method = 'get' . ucfirst($field);
                        return $object->$method();
                    }));

                $dataMapper->expects($self->any())
                    ->method('setData')
                    ->will($self->returnCallback(function($field, $object, $value){
                        $method = 'set' . ucfirst($field);
                        return $object->$method($value);
                    }));


                return $dataMapper;
            }));

        $column->expects($this->any())
            ->method('getOption')
             ->will($this->returnCallback(function($option){
                 switch($option) {
                     case 'mapping_fields':
                        return array('name', 'author');
                     break;
                     case 'editable':
                         return true;
                     break;
                     case 'fields_options':
                         return array(
                             'name' => array(
                                 'type' => 'text'
                             ),
                             'author' => array(
                                 'type' => 'text'
                             )
                         );
                     break;
                 }
             }));

        $object = new Entity('old_name');
        $data = array(
            'name' => 'object',
            'author' => 'norbert@fsi.pl',
            'invalid_data' => 'test'
        );

        $extension->bindData($column, $data, $object, 1);

        $this->assertSame($object->getAuthor(), 'norbert@fsi.pl');
        $this->assertSame($object->getName(), 'object');
    }
}
