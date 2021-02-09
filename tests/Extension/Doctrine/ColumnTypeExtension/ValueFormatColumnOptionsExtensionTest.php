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
    public function testBuildCellViewWithGlueAndEmptyValueAsStringAndWithoutOneValue()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue([
                0 => [
                    'id' => null,
                    'name' => 'Foo'
                ]
            ]));

        $column->expects($this->at(0))
            ->method('getOption')
            ->with('empty_value')
            ->will($this->returnValue('no'));

        $column->expects($this->at(1))
            ->method('getOption')
            ->with('value_glue')
            ->will($this->returnValue(' '));

        $column->expects($this->at(3))
            ->method('getOption')
            ->with('glue_multiple')
            ->will($this->returnValue(' '));

        $column->expects($this->at(2))
            ->method('getOption')
            ->with('value_format')
            ->will($this->returnValue(null));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('no Foo');

        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithGlueAndEmptyValueAsStringAndWithoutValues()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue([
                0 => [
                    'id' => null,
                    'name' => null
                ]
            ]));

        $column->expects($this->at(0))
            ->method('getOption')
            ->with('empty_value')
            ->will($this->returnValue('no'));

        $column->expects($this->at(1))
            ->method('getOption')
            ->with('value_glue')
            ->will($this->returnValue(' '));

        $column->expects($this->at(2))
            ->method('getOption')
            ->with('value_format')
            ->will($this->returnValue(null));

        $column->expects($this->at(3))
            ->method('getOption')
            ->with('glue_multiple')
            ->will($this->returnValue(' '));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('no no');

        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithGlueAndEmptyValueAsArrayAndWithoutOneValue()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue([
                0 => [
                    'id' => 1,
                    'name' => null
                ]
            ]));

        $column->expects($this->at(0))
            ->method('getOption')
            ->with('empty_value')
            ->will($this->returnValue(['name' => 'no']));

        $column->expects($this->at(1))
            ->method('getOption')
            ->with('value_glue')
            ->will($this->returnValue(' '));

        $column->expects($this->at(2))
            ->method('getOption')
            ->with('value_format')
            ->will($this->returnValue(null));

        $column->expects($this->at(3))
            ->method('getOption')
            ->with('glue_multiple')
            ->will($this->returnValue(' '));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('1 no');

        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithGlueAndEmptyValueAsArrayAndWithoutValues()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue([
                0 => [
                    'id' => null,
                    'name' => null
                ]
            ]));

        $column->expects($this->at(0))
            ->method('getOption')
            ->with('empty_value')
            ->will($this->returnValue(['id' => 'no','name' => 'no']));

        $column->expects($this->at(1))
            ->method('getOption')
            ->with('value_glue')
            ->will($this->returnValue(' '));

        $column->expects($this->at(2))
            ->method('getOption')
            ->with('value_format')
            ->will($this->returnValue(null));

        $column->expects($this->at(3))
            ->method('getOption')
            ->with('glue_multiple')
            ->will($this->returnValue(' '));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('no no');

        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithGlueAndGlueMultipleAndEmptyValueAsArrayAndWithoutMultipleValues()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue([
                0 => [
                    'id' => null,
                    'name' => null
                ],
                1 => [
                    'id' => null,
                    'name' => 'Foo'
                ]
            ]));

        $column->expects($this->at(0))
            ->method('getOption')
            ->with('empty_value')
            ->will($this->returnValue(['id' => 'no','name' => 'no']));

        $column->expects($this->at(1))
            ->method('getOption')
            ->with('value_glue')
            ->will($this->returnValue(' '));

        $column->expects($this->at(2))
            ->method('getOption')
            ->with('value_format')
            ->will($this->returnValue(null));

        $column->expects($this->at(3))
            ->method('getOption')
            ->with('glue_multiple')
            ->will($this->returnValue('<br />'));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('no no<br />no Foo');

        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithGlueAndEmptyValueAsArrayAndNotFoundKeyInEmptyValue()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue([
                0 => [
                    'id' => null,
                    'name' => 'Foo'
                ]
            ]));

        $column->expects($this->at(0))
            ->method('getOption')
            ->with('empty_value')
            ->will($this->returnValue(['id2' => 'no','name' => 'no']));

        $this->expectException(DataGridException::class);
        $this->expectExceptionMessage('Not found key "id" in empty_value array');
        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithoutFormatAndGlue()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue([
                0 => [
                    'id' => 1,
                    'name' => 'Foo'
                ]
            ]));

        $column->expects($this->at(1))
            ->method('getOption')
            ->with('value_glue')
            ->will($this->returnValue(null));

        $column->expects($this->at(2))
            ->method('getOption')
            ->with('value_format')
            ->will($this->returnValue(null));

        $column->expects($this->at(3))
            ->method('getOption')
            ->with('glue_multiple')
            ->will($this->returnValue(' '));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('');

        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithFormatAndGlue()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $view->expects($this->at(0))
        ->method('getValue')
        ->will($this->returnValue([
            0 => [
                'id' => 1,
                'name' => 'Foo'
            ]
        ]));

        $column->expects($this->at(1))
            ->method('getOption')
            ->with('value_glue')
            ->will($this->returnValue('<br/>'));

        $column->expects($this->at(2))
            ->method('getOption')
            ->with('value_format')
            ->will($this->returnValue('(%s)'));

        $column->expects($this->at(3))
            ->method('getOption')
            ->with('glue_multiple')
            ->will($this->returnValue(' '));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('(1)<br/>(Foo)');

        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithFormatAndGlueWithToManyPlaceholders()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $view->expects($this->at(0))
        ->method('getValue')
            ->will($this->returnValue([
                0 => [
                    'id' => 1,
                    'name' => 'Foo'
                ]
            ]));

        $column->expects($this->at(1))
            ->method('getOption')
            ->with('value_glue')
            ->will($this->returnValue('<br/>'));

        $column->expects($this->at(2))
            ->method('getOption')
            ->with('value_format')
            ->will($this->returnValue('(%s) (%s)'));

        if (PHP_VERSION_ID >= 80000) {
            $this->expectException(ArgumentCountError::class);
        } else {
            $this->expectException(Error::class);
        }
        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithFormatGlueAndGlueMultiple()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue([
                0 => [
                    'id' => 1,
                    'name' => 'Foo',
                ],
                1 => [
                    'id' => 2,
                    'name' => 'Bar',
                ]
            ]));

        $column->expects($this->at(1))
            ->method('getOption')
            ->with('value_glue')
            ->will($this->returnValue(' '));

        $column->expects($this->at(2))
            ->method('getOption')
            ->with('value_format')
            ->will($this->returnValue('(%s)'));

        $column->expects($this->at(3))
            ->method('getOption')
            ->with('glue_multiple')
            ->will($this->returnValue('<br>'));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('(1) (Foo)<br>(2) (Bar)');

        $extension->buildCellView($column, $view);
    }
}
