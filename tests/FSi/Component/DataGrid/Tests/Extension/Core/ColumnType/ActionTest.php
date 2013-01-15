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

class ActionTest extends \PHPUnit_Framework_TestCase
{
    public function testFilterValueWrongActionsOptionType()
    {
        $column = new Action();
        $column->setOption('actions', 'boo');
        $this->setExpectedException('InvalidArgumentException');
        $column->filterValue(array());
    }

    public function testFilterValueEmptyActionsOptionType()
    {
        $column = new Action();
        $column->setOption('actions', array());

        $this->setExpectedException('InvalidArgumentException');
        $column->filterValue(array());
    }


    public function testFilterValueInvalidActionInActionsOption()
    {
        $column = new Action();
        $column->setOption('actions', array('edit' => array()));
        $this->setExpectedException('InvalidArgumentException');
        $column->filterValue(array());
    }


    public function testFilterValueRequiredActionInActionsOption()
    {
        $column = new Action();
        $column->setOption('actions', array(
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
            $column->filterValue(array(
               'foo' => 'bar'
            ))
        );
    }

    public function testFilterValueAvailableActionInActionsOption()
    {
        $column = new Action();
        $column->setOption('actions', array(
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
            $column->filterValue(array(
               'foo' => 'bar'
            ))
        );
    }

}