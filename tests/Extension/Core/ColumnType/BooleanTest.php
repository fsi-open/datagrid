<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Tests\Extension\Core\ColumnType;

use FSi\Component\DataGrid\Extension\Core\ColumnType\Boolean as BooleanColumnType;
use PHPUnit\Framework\TestCase;

class BooleanTest extends TestCase
{
    /**
     * @var BooleanColumnType
     */
    private $column;

    protected function setUp(): void
    {
        $column = new BooleanColumnType();
        $column->setName('available');
        $column->initOptions();

        $this->column = $column;
    }

    public function testBasicFilterValue(): void
    {
        $this->column->setOptions([
            'true_value' => 'true',
            'false_value'=> 'false'
        ]);

        self::assertSame($this->column->filterValue(true), 'true');
        self::assertNotSame($this->column->filterValue(true), 'false');
    }

    public function testFilterValueWithTrueValuesInArray(): void
    {
        $this->column->setOption('true_value', 'true');

        self::assertSame(
            $this->column->filterValue([
                true,
                true
            ]),
            'true'
        );
    }

    public function testFilterValueWithMixedValuesInArray(): void
    {
        $this->column->setOptions([
            'true_value' => 'true',
            'false_value'=> 'false'
        ]);

        self::assertSame(
            $this->column->filterValue([
                true,
                1,
                new \DateTime()
            ]),
            'true'
        );
    }


    public function testFilterValueWithFalseValuesInArray(): void
    {
        $this->column->setOptions([
            'true_value' => 'true',
            'false_value'=> 'false'
        ]);

        self::assertSame(
            $this->column->filterValue([
                false,
                false
            ]),
            'false'
        );
    }

    public function testFilterValueWithMixedValuesAndFalseInArray(): void
    {
        $this->column->setOptions([
            'true_value' => 'true',
            'false_value'=> 'false'
        ]);

        self::assertSame(
            $this->column->filterValue([
                true,
                1,
                new \DateTime(),
                false
            ]),
            'false'
        );
    }

    public function testFilterValueWithMixedValuesAndNullInArray(): void
    {
        $this->column->setOptions([
            'true_value' => 'true',
            'false_value'=> 'false'
        ]);

        self::assertSame(
            $this->column->filterValue([
                true,
                1,
                new \DateTime(),
                null
            ]),
            'true'
        );
    }

    public function testFilterValueWithAllNullsInArray(): void
    {
        $this->column->setOptions([
            'true_value' => 'true',
            'false_value'=> 'false'
        ]);

        self::assertSame(
            $this->column->filterValue([
                null,
                null
            ]),
            ''
        );
    }
}
