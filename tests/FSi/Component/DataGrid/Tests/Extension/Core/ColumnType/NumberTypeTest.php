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

use FSi\Component\DataGrid\Extension\Core\ColumnType\Number;

class NumberTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testPrecision()
    {
        $value = array(
            'number' => 10.123
        );

        $column = new Number();
        $column->setOption('precision', 2);
        $column->setOption('round_mode', Number::ROUND_HALF_UP);

        $this->assertSame(
            $column->filterValue($value),
            array(
            	'number' => 10.12
            )
        );
    }

    public function testRoundMode()
    {
        $column = new Number();
        $column->setOption('round_mode', Number::ROUND_HALF_UP);
        $this->assertSame(
            $column->filterValue(array(
                'number' => 10.123
            )),
            array(
            	'number' => 10.12
            )
        );

        $this->assertSame(
            $column->filterValue(array(
                'number' => 10.126
            )),
            array(
            	'number' => 10.13
            )
        );
    }
}