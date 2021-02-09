<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Tests\Extension\Doctrine\ColumnTypeExtension;

use ArgumentCountError;
use FSi\Component\DataGrid\Exception\DataGridException;
use FSi\Component\DataGrid\Extension\Doctrine\ColumnTypeExtension\ValueFormatColumnOptionsExtension;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\Column\CellViewInterface;
use PHPUnit\Framework\Error\Error;
use PHPUnit\Framework\TestCase;
use const PHP_VERSION_ID;

class ValueFormatColumnOptionsExtensionTest extends TestCase
{
    public function testBuildCellViewWithGlueAndEmptyValueAsStringAndWithoutOneValue(): void
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $view->expects(self::at(0))
            ->method('getValue')
            ->willReturn(
                [
                    0 => [
                        'id' => null,
                        'name' => 'Foo'
                    ]
                ]
            );

        $column->expects(self::at(0))
            ->method('getOption')
            ->with('empty_value')
            ->willReturn('no');

        $column->expects(self::at(1))
            ->method('getOption')
            ->with('value_glue')
            ->willReturn(' ');

        $column->expects(self::at(2))
            ->method('getOption')
            ->with('value_format')
            ->willReturn(null);

        $column->expects(self::at(3))
            ->method('getOption')
            ->with('glue_multiple')
            ->willReturn(' ');

        $view->expects(self::at(1))
            ->method('setValue')
            ->with('no Foo');

        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithGlueAndEmptyValueAsStringAndWithoutValues(): void
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $view->expects(self::at(0))
            ->method('getValue')
            ->willReturn(
                [
                    0 => [
                        'id' => null,
                        'name' => null
                    ]
                ]
            );

        $column->expects(self::at(0))
            ->method('getOption')
            ->with('empty_value')
            ->willReturn('no');

        $column->expects(self::at(1))
            ->method('getOption')
            ->with('value_glue')
            ->willReturn(' ');

        $column->expects(self::at(2))
            ->method('getOption')
            ->with('value_format')
            ->willReturn(null);

        $column->expects(self::at(3))
            ->method('getOption')
            ->with('glue_multiple')
            ->willReturn(' ');

        $view->expects(self::at(1))
            ->method('setValue')
            ->with('no no');

        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithGlueAndEmptyValueAsArrayAndWithoutOneValue(): void
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $view->expects(self::at(0))
            ->method('getValue')
            ->willReturn(
                [
                    0 => [
                        'id' => 1,
                        'name' => null
                    ]
                ]
            );

        $column->expects(self::at(0))
            ->method('getOption')
            ->with('empty_value')
            ->willReturn(['name' => 'no']);

        $column->expects(self::at(1))
            ->method('getOption')
            ->with('value_glue')
            ->willReturn(' ');

        $column->expects(self::at(2))
            ->method('getOption')
            ->with('value_format')
            ->willReturn(null);

        $column->expects(self::at(3))
            ->method('getOption')
            ->with('glue_multiple')
            ->willReturn(' ');

        $view->expects(self::at(1))
            ->method('setValue')
            ->with('1 no');

        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithGlueAndEmptyValueAsArrayAndWithoutValues(): void
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $view->expects(self::at(0))
            ->method('getValue')
            ->willReturn(
                [
                    0 => [
                        'id' => null,
                        'name' => null
                    ]
                ]
            );

        $column->expects(self::at(0))
            ->method('getOption')
            ->with('empty_value')
            ->willReturn(['id' => 'no', 'name' => 'no']);

        $column->expects(self::at(1))
            ->method('getOption')
            ->with('value_glue')
            ->willReturn(' ');

        $column->expects(self::at(2))
            ->method('getOption')
            ->with('value_format')
            ->willReturn(null);

        $column->expects(self::at(3))
            ->method('getOption')
            ->with('glue_multiple')
            ->willReturn(' ');

        $view->expects(self::at(1))
            ->method('setValue')
            ->with('no no');

        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithGlueAndGlueMultipleAndEmptyValueAsArrayAndWithoutMultipleValues(): void
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $view->expects(self::at(0))
            ->method('getValue')
            ->willReturn(
                [
                    0 => [
                        'id' => null,
                        'name' => null
                    ],
                    1 => [
                        'id' => null,
                        'name' => 'Foo'
                    ]
                ]
            );

        $column->expects(self::at(0))
            ->method('getOption')
            ->with('empty_value')
            ->willReturn(['id' => 'no', 'name' => 'no']);

        $column->expects(self::at(1))
            ->method('getOption')
            ->with('value_glue')
            ->willReturn(' ');

        $column->expects(self::at(2))
            ->method('getOption')
            ->with('value_format')
            ->willReturn(null);

        $column->expects(self::at(3))
            ->method('getOption')
            ->with('glue_multiple')
            ->willReturn('<br />');

        $view->expects(self::at(1))
            ->method('setValue')
            ->with('no no<br />no Foo');

        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithGlueAndEmptyValueAsArrayAndNotFoundKeyInEmptyValue(): void
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $view->expects(self::at(0))
            ->method('getValue')
            ->willReturn(
                [
                    0 => [
                        'id' => null,
                        'name' => 'Foo'
                    ]
                ]
            );

        $column->expects(self::at(0))
            ->method('getOption')
            ->with('empty_value')
            ->willReturn(['id2' => 'no', 'name' => 'no']);

        $this->expectException(DataGridException::class);
        $this->expectExceptionMessage('Not found key "id" in empty_value array');
        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithoutFormatAndGlue(): void
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $view->expects(self::at(0))
            ->method('getValue')
            ->willReturn(
                [
                    0 => [
                        'id' => 1,
                        'name' => 'Foo'
                    ]
                ]
            );

        $column->expects(self::at(1))
            ->method('getOption')
            ->with('value_glue')
            ->willReturn(null);

        $column->expects(self::at(2))
            ->method('getOption')
            ->with('value_format')
            ->willReturn(null);

        $column->expects(self::at(3))
            ->method('getOption')
            ->with('glue_multiple')
            ->willReturn(' ');

        $view->expects(self::at(1))
            ->method('setValue')
            ->with('');

        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithFormatAndGlue(): void
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $view->expects(self::at(0))
        ->method('getValue')
        ->willReturn(
            [
                0 => [
                    'id' => 1,
                    'name' => 'Foo'
                ]
            ]
        );

        $column->expects(self::at(1))
            ->method('getOption')
            ->with('value_glue')
            ->willReturn('<br/>');

        $column->expects(self::at(2))
            ->method('getOption')
            ->with('value_format')
            ->willReturn('(%s)');

        $column->expects(self::at(3))
            ->method('getOption')
            ->with('glue_multiple')
            ->willReturn(' ');

        $view->expects(self::at(1))
            ->method('setValue')
            ->with('(1)<br/>(Foo)');

        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithFormatAndGlueWithToManyPlaceholders(): void
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $view->expects(self::at(0))
        ->method('getValue')
            ->willReturn(
                [
                    0 => [
                        'id' => 1,
                        'name' => 'Foo'
                    ]
                ]
            );

        $column->expects(self::at(1))
            ->method('getOption')
            ->with('value_glue')
            ->willReturn('<br/>');

        $column->expects(self::at(2))
            ->method('getOption')
            ->with('value_format')
            ->willReturn('(%s) (%s)');

        if (PHP_VERSION_ID >= 80000) {
            $this->expectException(ArgumentCountError::class);
        } else {
            $this->expectException(Error::class);
        }
        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithFormatGlueAndGlueMultiple(): void
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $view->expects(self::at(0))
            ->method('getValue')
            ->willReturn(
                [
                    0 => [
                        'id' => 1,
                        'name' => 'Foo',
                    ],
                    1 => [
                        'id' => 2,
                        'name' => 'Bar',
                    ]
                ]
            );

        $column->expects(self::at(1))
            ->method('getOption')
            ->with('value_glue')
            ->willReturn(' ');

        $column->expects(self::at(2))
            ->method('getOption')
            ->with('value_format')
            ->willReturn('(%s)');

        $column->expects(self::at(3))
            ->method('getOption')
            ->with('glue_multiple')
            ->willReturn('<br>');

        $view->expects(self::at(1))
            ->method('setValue')
            ->with('(1) (Foo)<br>(2) (Bar)');

        $extension->buildCellView($column, $view);
    }
}
