<?php
/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Tests\Extension\Core\ColumnType;

use FSi\Component\DataGrid\Extension\Core\ColumnType\Boolean;

class BooleanTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testBasicFilterValue()
    {
        $column = new Boolean();
        $column->setName('available');

        $column->setOption('true_value', 'true');
        $column->setOption('false_value', 'false');

        $this->assertSame($column->filterValue(true), 'true');
        $this->assertNotSame($column->filterValue(true), 'false');
    }

    public function testFilterValueWithTrueValuesInArray()
    {
        $column = new Boolean();
        $column->setName('available');

        $column->setOption('true_value', 'true');

        $this->assertSame(
            $column->filterValue(array(
                true,
                true
            )),
            'true');
    }

    public function testFilterValueWithMixedValuesInArray()
    {
        $column = new Boolean();
        $column->setName('available');

        $column->setOption('true_value', 'true');
        $column->setOption('false_value', 'false');

        $this->assertSame(
            $column->filterValue(array(
                true,
                1,
                new \DateTime()
            )),
            'true');

        $this->assertNotSame(
            $column->filterValue(array(
                true,
                1,
                new \DateTime()
            )),
            'false');
    }

    public function testFilterValueWithFalseValuesInArray()
    {
        $column = new Boolean();
        $column->setName('available');

        $column->setOption('true_value', 'true');
        $column->setOption('false_value', 'false');

        $this->assertNotSame(
            $column->filterValue(array(
                false,
                false
            )),
            'true');

        $this->assertSame(
            $column->filterValue(array(
                false,
                false
            )),
            'false');
    }


    public function testFilterValueWithMixedValuesAndFalseInArray()
    {
        $column = new Boolean();
        $column->setName('available');

        $column->setOption('true_value', 'true');
        $column->setOption('false_value', 'false');

        $this->assertNotSame(
            $column->filterValue(array(
                true,
                1,
                new \DateTime(),
                false
            )),
            'true');

        $this->assertSame(
            $column->filterValue(array(
                true,
                1,
                new \DateTime(),
                false
            )),
            'false');
    }
}
