<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Tests\Extension\Core\ColumnTypeExtension;

use FSi\Component\DataGrid\Column\CellViewInterface;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\Extension\Core\ColumnType\Text;
use FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension\ValueFormatColumnOptionsExtension;
use InvalidArgumentException;
use PHPUnit\Framework\Error\Error;
use PHPUnit\Framework\TestCase;
use ValueError;
use const PHP_VERSION_ID;

class ValueFormatColumnOptionsExtensionTest extends TestCase
{
    public function testBuildCellView(): void
    {
        $extension = new ValueFormatColumnOptionsExtension();

        $column = $this->createMock(ColumnTypeInterface::class);
        $view = $this->createMock(CellViewInterface::class);

        $column->expects(self::exactly(5))
            ->method('getOption')
            ->willReturnCallback(
                function ($option) {
                    switch ($option) {
                        case 'value_glue':
                            return '-';

                        case 'empty_value':
                            return '';

                        case 'field_mapping':
                            return [];
                    }

                    return null;
                }
            );

        $view->method('getValue')->willReturn(['foo', 'bar']);
        $view->method('setValue')->with('foo-bar');

        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithoutFormatAndGlue(): void
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $column->expects(self::exactly(5))
            ->method('getOption')
            ->willReturnCallback(
                function ($option) {
                    switch ($option) {
                        case 'value_glue':
                        case 'value_format':
                            return null;

                        case 'empty_value':
                            return '';

                        case 'field_mapping':
                            return [];
                    }

                    return null;
                }
            );

        $view->expects(self::once())->method('getValue')->willReturn(['foo']);
        $view->expects(self::once())->method('setValue')->with('foo');

        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithFormatAndGlue(): void
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $column->expects(self::exactly(5))
            ->method('getOption')
            ->willReturnCallback(
                function ($option) {
                    switch ($option) {
                        case 'value_format':
                            return '<b>%s</b>';

                        case 'value_glue':
                            return '<br/>';

                        case 'empty_value':
                            return '';

                        case 'field_mapping':
                            return [];
                    }

                    return null;
                }
            );

        $view->expects(self::once())->method('getValue')->willReturn(['foo', 'bar']);
        $view->expects(self::once())->method('setValue')->with('<b>foo</b><br/><b>bar</b>');

        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithoutFormatAndGlueWithValueArray(): void
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $column->expects(self::exactly(5))
            ->method('getOption')
            ->willReturnCallback(
                function ($option) {
                    switch ($option) {
                        case 'value_format':
                        case 'value_glue':
                            return null;

                        case 'empty_value':
                            return '';

                        case 'field_mapping':
                            return [];
                    }

                    return null;
                }
            );

        $view->expects(self::once())->method('getValue')->willReturn(['foo', 'bar']);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'At least one of "value_format" or "value_glue" option is missing in column: "".'
        );
        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithValidTemplate(): void
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $column->expects(self::exactly(5))
            ->method('getOption')
            ->willReturnCallback(
                function ($option) {
                    switch ($option) {
                        case 'value_format':
                            return '<b>%s</b>';

                        case 'value_glue':
                        case 'empty_value':
                            return '';

                        case 'field_mapping':
                            return [];
                    }

                    return null;
                }
            );

        $view->expects(self::once())->method('getValue')->willReturn(['foo']);
        $view->expects(self::once())->method('setValue')->with('<b>foo</b>');

        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithValidFormatAndValueArray(): void
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $column->expects(self::exactly(5))
            ->method('getOption')
            ->willReturnCallback(
                function ($option) {
                    switch ($option) {
                        case 'value_format':
                            return '<b>%s</b><br/><b>%s</b>';

                        case 'value_glue':
                            return null;

                        case 'empty_value':
                            return '';

                        case 'field_mapping':
                            return [];
                    }

                    return null;
                }
            );

        $view->expects(self::once())->method('getValue')->willReturn(['foo', 'bar']);
        $view->expects(self::once())->method('setValue')->with('<b>foo</b><br/><b>bar</b>');

        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithFormatThatHaveTooManyPlaceholders(): void
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $column->expects(self::exactly(5))
            ->method('getOption')
            ->willReturnCallback(
                function ($option) {
                    switch ($option) {
                        case 'value_format':
                            return '%s%s';

                        case 'value_glue':
                            return null;

                        case 'empty_value':
                            return '';

                        case 'field_mapping':
                            return [];
                    }

                    return null;
                }
            );

        $view->expects(self::once())->method('getValue')->willReturn(['foo']);

        if (PHP_VERSION_ID >= 80000) {
            $this->expectException(ValueError::class);
        } else {
            $this->expectException(Error::class);
        }
        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithFormatThatHaveNotEnoughPlaceholders(): void
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $column->expects(self::exactly(5))
            ->method('getOption')
            ->willReturnCallback(
                function ($option) {
                    switch ($option) {
                        case 'value_format':
                            return '<b>%s</b>';

                        case 'value_glue':
                            return null;

                        case 'empty_value':
                            return '';

                        case 'field_mapping':
                            return [];
                    }

                    return null;
                }
            );

        $view->expects(self::once())->method('getValue')->willReturn(['foo', 'bar']);
        $view->expects(self::once())->method('setValue')->with('<b>foo</b>');

        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithEmptyTemplate(): void
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $column->expects(self::exactly(5))
            ->method('getOption')
            ->willReturnCallback(
                function ($option) {
                    switch ($option) {
                        case 'empty_value':
                        case 'value_format':
                            return '';

                        case 'value_glue':
                            break;

                        case 'field_mapping':
                            return [];
                    }

                    return null;
                }
            );

        $view->expects(self::once())->method('getValue')->willReturn(['foo']);
        $view->expects(self::once())->method('setValue')->with('');

        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithoutEmptyValue(): void
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $column->expects(self::exactly(5))
            ->method('getOption')
            ->willReturnCallback(
                function ($option) {
                    switch ($option) {
                        case 'value_format':
                            return null;

                        case 'value_glue':
                            return ' ';

                        case 'empty_value':
                            return '';

                        case 'field_mapping':
                            return [];
                    }

                    return null;
                }
            );

        $view->expects(self::once())->method('getValue')->willReturn([null]);
        $view->expects(self::once())->method('setValue')->with('');

        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithEmptyValue(): void
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $column->expects(self::exactly(5))
            ->method('getOption')
            ->willReturnCallback(
                function ($option) {
                    switch ($option) {
                        case 'value_format':
                        case 'value_glue':
                            return null;

                        case 'empty_value':
                            return 'empty';

                        case 'field_mapping':
                            return [];
                    }

                    return null;
                }
            );

        $view->expects(self::once())->method('getValue')->willReturn([null]);
        $view->expects(self::once())->method('setValue')->with('empty');

        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithEmptyValueAndMultipleValues(): void
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $column->expects(self::exactly(5))
            ->method('getOption')
            ->willReturnCallback(
                function ($option) {
                    switch ($option) {
                        case 'value_format':
                            return null;

                        case 'value_glue':
                            return ' ';

                        case 'empty_value':
                            return 'empty';

                        case 'field_mapping':
                            return [];
                    }

                    return null;
                }
            );

        $view->expects(self::once())
            ->method('getValue')
            ->willReturn(
                [
                    'val',
                    '',
                    null,
                ]
            );

        $view->expects(self::once())->method('setValue')->with('val empty empty');

        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithMultipleEmptyValueAndMultipleValues(): void
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $column->expects(self::exactly(5))
            ->method('getOption')
            ->willReturnCallback(
                function ($option) {
                    switch ($option) {
                        case 'value_format':
                        case 'value_glue':
                            return null;

                        case 'empty_value':
                            return [
                                'fo' => 'foo',
                                'ba' => 'bar'
                            ];

                        case 'field_mapping':
                            return ['fo', 'ba'];
                    }

                    return null;
                }
            );

        $view->expects(self::once())->method('getValue')->willReturn('default');
        $view->expects(self::once())->method('setValue')->with('default');

        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithEmptyValueThatNotExistsInMappingFields(): void
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $column->expects(self::exactly(2))
            ->method('getOption')
            ->willReturnCallback(
                function ($option) {
                    switch ($option) {
                        case 'value_format':
                        case 'value_glue':
                            return null;

                        case 'empty_value':
                            return [
                                'fo' => 'empty',
                            ];

                        case 'field_mapping':
                            return ['fos'];
                    }

                    return null;
                }
            );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Mapping field "fo" doesn\'t exist in column: "".');
        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithMultipleEmptyValueMultipleValuesAndTemplate(): void
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $column->expects(self::exactly(5))
            ->method('getOption')
            ->willReturnCallback(
                function ($option) {
                    switch ($option) {
                        case 'value_format':
                            return '"%s" "%s" "%s"';

                        case 'value_glue':
                            return null;

                        case 'empty_value':
                            return [
                                'fo' => 'empty-fo',
                                'ba' => 'empty-bar'
                            ];

                        case 'field_mapping':
                            return ['fo', 'ba', 'ca'];
                    }
                }
            );

        $view->expects(self::once())
            ->method('getValue')
            ->willReturn(
                [
                    'fo' => '',
                    'ba' => '',
                    'ca' => null,
                ]
            );

        $view->expects(self::once())->method('setValue')->with('"empty-fo" "empty-bar" ""');

        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithFormatThatIsClosure(): void
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $column->expects(self::exactly(5))
            ->method('getOption')
            ->willReturnCallback(
                function ($option) {
                    switch ($option) {
                        case 'value_format':
                            return static function ($data) {
                                return $data['fo'] . '-' . $data['ba'];
                            };

                        case 'value_glue':
                            return null;

                        case 'empty_value':
                            return [];

                        case 'field_mapping':
                            return ['fo', 'ba'];
                    }

                    return null;
                }
            );

        $view->expects(self::once())
            ->method('getValue')
            ->willReturn(
                [
                    'fo' => 'fo',
                    'ba' => 'ba',
                ]
            );

        $view->expects(self::once())->method('setValue')->with('fo-ba');

        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithValueThatIsZero(): void
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $column->expects(self::exactly(5))
            ->method('getOption')
            ->willReturnCallback(
                function ($option) {
                    switch ($option) {
                        case 'value_glue':
                            return '';

                        case 'empty_value':
                            return 'This should not be used.';

                        case 'field_mapping':
                            return ['fo'];
                    }

                    return null;
                }
            );

        $view->expects(self::once())
            ->method('getValue')
            ->willReturn(
                [
                    'fo' => 0,
                ]
            );

        $view->expects(self::once())->method('setValue')->with(0);

        $extension->buildCellView($column, $view);
    }

    public function testSetValueFormatThatIsClosure(): void
    {
        $column = new Text();
        $extension = new ValueFormatColumnOptionsExtension();
        $column->addExtension($extension);

        $column->initOptions();
        $extension->initOptions($column);

        $column->setOptions([
            'value_format' => function ($data) {
                return (string) $data;
            }
        ]);

        self::assertEquals(['for' => 'bar'], $extension->filterValue($column, ['for' => 'bar']));
    }
}
