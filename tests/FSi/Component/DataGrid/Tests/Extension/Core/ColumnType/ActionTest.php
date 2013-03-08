<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Tests\Extension\Core;

use FSi\Component\DataGrid\Extension\Core\ColumnType\Action;
use FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension\DefaultColumnOptionsExtension;

class ActionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \FSi\Component\DataGrid\Extension\Core\ColumnType\Action
     */
    private $column;

    public function setUp()
    {
        $column = new Action();
        $column->setName('action');
        $column->initOptions();

        $extension = new DefaultColumnOptionsExtension();
        $extension->initOptions($column);

        $this->column = $column;
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFilterValueEmptyActionsOptionType()
    {
        $this->column->setOption('actions', array());
        $this->column->filterValue(array());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFilterValueInvalidActionInActionsOption()
    {
        $this->column->setOption('actions', array('edit' => array()));
        $this->column->filterValue(array());
    }

    public function testFilterValueRequiredActionInActionsOption()
    {
        $this->column->setOption('actions', array(
            'edit' => array(
                'uri_scheme' => '/test/%s',
                'anchor' => 'test'
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
            $this->column->filterValue(array(
               'foo' => 'bar'
            ))
        );
    }

    public function testFilterValueAvailableActionInActionsOption()
    {
        $this->column->setOption('actions', array(
            'edit' => array(
                'uri_scheme' => '/test/%s',
                'anchor' => 'test',
                'domain' => 'fsi.pl',
                'protocole' => 'https://',
                'redirect_uri' => 'http://onet.pl/'
            )
        ));

        $this->assertSame(
            array(
               'edit' => array(
                   'name' => 'edit',
                   'anchor' => 'test',
                   'url' => 'https://fsi.pl/test/bar?redirect_uri=' . urlencode('http://onet.pl/')
               )
            ),
            $this->column->filterValue(array(
               'foo' => 'bar'
            ))
        );
    }
}
