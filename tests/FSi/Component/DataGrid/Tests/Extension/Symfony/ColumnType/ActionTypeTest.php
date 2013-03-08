<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Tests\Extension\Symfony\ColumnType;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use FSi\Component\DataGrid\Extension\Symfony\ColumnType\Action;
use FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension\DefaultColumnOptionsExtension;

class ActionTypeTest extends \PHPUnit_Framework_TestCase
{
    private $container;
    private $column;

    protected function setUp()
    {
        if (!interface_exists('Symfony\Component\DependencyInjection\ContainerInterface')
            || !interface_exists('Symfony\Component\Routing\RouterInterface')
            || !class_exists('Symfony\Component\HttpFoundation\Request')) {
            $this->markTestSkipped('Symfony Column Action require Symfony\Component\DependencyInjection\ContainerInterface interface.');
        }

        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');

        $column = new Action($this->container);
        $column->setName('action');
        $column->initOptions();

        $extension = new DefaultColumnOptionsExtension();
        $extension->initOptions($column);

        $this->column = $column;
    }

    public function testFilterValueWrongActionsOptionType()
    {
        $this->column->setOption('actions', 'boo');

        $this->setExpectedException('InvalidArgumentException');
        $this->column->filterValue(array());
    }

    public function testFilterValueEmptyActionsOptionType()
    {
        $this->column->setOption('actions', array());

        $this->setExpectedException('InvalidArgumentException');
        $this->column->filterValue(array());
    }

    public function testFilterValueInvalidActionInActionsOption()
    {
        $this->column->setOption('actions', array('edit' => 'asdasd'));
        $this->setExpectedException('InvalidArgumentException');
        $this->column->filterValue(array());
    }

    public function testFilterValueRequiredActionInActionsOption()
    {
        $self = $this;

        $this->container->expects($this->any())
            ->method('get')
            ->will($this->returnCallback(function($serviceId) use ($self) {

                if ($serviceId == 'router') {
                    $router = $self->getMock('Symfony\Component\Routing\RouterInterface');
                    $router->expects($self->once())
                        ->method('generate')
                        ->with('foo', array('redirect_uri' => 'http://example.com/?test=1&test=2'), false)
                        ->will($self->returnValue('/test/bar?redirect_uri=' . urlencode(MyRequest::URI)));

                    return $router;
                }

                if ($serviceId == 'request') {
                    return new MyRequest();
                }
            }));

        $column = new Action($this->container);
        $column->setName('action');
        $column->initOptions();

        $extension = new DefaultColumnOptionsExtension();
        $extension->initOptions($column);


        $column->setOption('actions', array(
            'edit' => array(
                'route_name' => 'foo',
                'anchor' => 'test',
                'absolute' => false
            )
        ));

       $this->assertSame(
           array(
               'edit' => array(
                   'name' => 'edit',
                   'anchor' => 'test',
                   'url' => '/test/bar?redirect_uri=' . urlencode(MyRequest::URI),
               )
           ),
           $column->filterValue(array(
               'foo' => 'bar'
           ))
       );
    }

    public function testFilterValueAvailableActionInActionsOption()
    {
        $self = $this;

        $this->container->expects($this->any())
            ->method('get')
            ->will($this->returnCallback(function($serviceId) use ($self) {
                switch ($serviceId) {
                    case 'router':
                        $router = $self->getMock('Symfony\Component\Routing\RouterInterface');
                        $router->expects($self->once())
                            ->method('generate')
                            ->with('foo', array('foo' => 'bar', 'redirect_uri' => 'http://example.com/?test=1&test=2'), true)
                            ->will($self->returnValue('https://fsi.pl/test/bar?redirect_uri=' . urlencode(MyRequest::URI)));
                        return $router;
                        break;
                    case 'request':
                        return new MyRequest();
                        break;
                }
            }));

        $column = new Action($this->container);
        $column->setName('action');
        $column->initOptions();

        $extension = new DefaultColumnOptionsExtension();
        $extension->initOptions($column);

        $column->setOption('mapping_fields', array('foo'));
        $column->setOption('actions', array(
            'edit' => array(
                'route_name' => 'foo',
                'parameters' => array('foo' => 'foo'),
                'anchor' => 'test',
                'absolute' => true
            )
        ));

       $this->assertSame(
           array(
               'edit' => array(
                   'name' => 'edit',
                   'anchor' => 'test',
                   'url' => 'https://fsi.pl/test/bar?redirect_uri=' . urlencode(MyRequest::URI)
               )
           ),
           $column->filterValue(array(
               'foo' => 'bar'
           ))
       );
    }


    public function testFilterValueWithRedirectUriFalse()
    {
        $self = $this;

        $this->container->expects($this->any())
            ->method('get')
            ->will($this->returnCallback(function($serviceId) use ($self) {
                switch ($serviceId) {
                    case 'router':
                        $router = $self->getMock('Symfony\Component\Routing\RouterInterface');
                        $router->expects($self->once())
                            ->method('generate')
                            ->with('foo', array(), false)
                            ->will($self->returnValue('/test/bar'));

                        return $router;
                    break;
                    case 'request':
                        return new MyRequest();
                    break;
                }
            }));

        $column = new Action($this->container);
        $column->setName('action');
        $column->initOptions();

        $extension = new DefaultColumnOptionsExtension();
        $extension->initOptions($column);

        $column->setOption('actions', array(
            'edit' => array(
                'route_name' => 'foo',
                'anchor' => 'test',
                'absolute' => false,
                'redirect_uri' => false
            )
        ));

       $this->assertSame(
           array(
               'edit' => array(
                   'name' => 'edit',
                   'anchor' => 'test',
                   'url' => '/test/bar'
               )
           ),
           $column->filterValue(array(
               'foo' => 'bar'
           ))
       );
    }
}

class MyRequest extends Request
{
    const URI = 'http://example.com/?test=1&test=2';

    public function __construct()
    {
    }

    public function getUri()
    {
        return urldecode(self::URI);
    }
}
