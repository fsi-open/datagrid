<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Tests\Extension\Core\ColumnType;

use FSi\Component\DataGrid\Extension\Core\ColumnType\Boolean;
use PHPUnit\Framework\TestCase;

class BooleanTest extends TestCase
{
    /**
     * @var Boolean
     */
    private $column;

    protected function setUp(): void
    {
        $column = new Boolean();
        $column->setName('available');
        $column->initOptions();

        $this->column = $column;
    }

    public function testBasicFilterValue()
    {
        $this->column->setOptions([
            'true_value' => 'true',
            'false_value'=> 'false'
        ]);

        $this->assertSame($this->column->filterValue(true), 'true');
        $this->assertNotSame($this->column->filterValue(true), 'false');
    }

    public function testFilterValueWithTrueValuesInArray()
    {
        $this->column->setOption('true_value', 'true');

        $this->assertSame(
            $this->column->filterValue([
                true,
                true
            ]),
            'true'
        );
    }

    public function testFilterValueWithMixedValuesInArray()
    {
        $this->column->setOptions([
            'true_value' => 'true',
            'false_value'=> 'false'
        ]);

        $this->assertSame(
            $this->column->filterValue([
                true,
                1,
                new \DateTime()
            ]),
            'true'
        );
    }


    public function testFilterValueWithFalseValuesInArray()
    {
        $this->column->setOptions([
            'true_value' => 'true',
            'false_value'=> 'false'
        ]);

        $this->assertSame(
            $this->column->filterValue([
                false,
                false
            ]),
            'false'
        );
    }

    public function testFilterValueWithMixedValuesAndFalseInArray()
    {
        $this->column->setOptions([
            'true_value' => 'true',
            'false_value'=> 'false'
        ]);

        $this->assertSame(
            $this->column->filterValue([
                true,
                1,
                new \DateTime(),
                false
            ]),
            'false'
        );
    }

    public function testFilterValueWithMixedValuesAndNullInArray()
    {
        $this->column->setOptions([
            'true_value' => 'true',
            'false_value'=> 'false'
        ]);

        $this->assertSame(
            $this->column->filterValue([
                true,
                1,
                new \DateTime(),
                null
            ]),
            'true'
        );
    }

    public function testFilterValueWithAllNullsInArray()
    {
        $this->column->setOptions([
            'true_value' => 'true',
            'false_value'=> 'false'
        ]);

        $this->assertSame(
            $this->column->filterValue([
                null,
                null
            ]),
            ''
        );
    }
}
