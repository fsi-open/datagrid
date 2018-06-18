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

class ValueFormatColumnOptionsExtensionTest extends TestCase
{
    public function test_build_cell_view()
    {
        $extension = new ValueFormatColumnOptionsExtension();

        $column = $this->createMock(ColumnTypeInterface::class);
        $view = $this->createMock(CellViewInterface::class);

        $column->expects($this->exactly(5))
            ->method('getOption')
            ->will($this->returnCallback(function($option) {
                switch($option) {
                    case 'value_glue':
                        return '-';
                        break;
                    case 'empty_value':
                        return '';
                        break;
                    case 'field_mapping':
                        return [];
                        break;
                }
            }));

        $view->expects($this->any())
            ->method('getValue')
            ->will($this->returnValue(['foo', 'bar']));

        $view->expects($this->any())
            ->method('setValue')
            ->with('foo-bar');

        $extension->buildCellView($column, $view);
    }

    public function test_build_cell_view_without_format_and_glue()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $column->expects($this->exactly(5))
            ->method('getOption')
            ->will($this->returnCallback(function($option) {
            switch($option) {
                case 'value_glue':
                case 'value_format':
                    return null;

                case 'empty_value':
                    return '';

                case 'field_mapping':
                    return [];

            }
        }));

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue(['foo']));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('foo');

        $extension->buildCellView($column, $view);
    }

    public function test_build_cell_view_with_format_and_glue()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $column->expects($this->exactly(5))
            ->method('getOption')
            ->will($this->returnCallback(function($option) {
            switch($option) {
                case 'value_format':
                    return '<b>%s</b>';

                case 'value_glue':
                    return '<br/>';

                case 'empty_value':
                    return '';

                case 'field_mapping':
                    return [];
            }
        }));

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue(['foo', 'bar']));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('<b>foo</b><br/><b>bar</b>');

        $extension->buildCellView($column, $view);
    }

    public function test_build_cell_view_without_format_and_glue_with_value_array()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $column->expects($this->exactly(5))
            ->method('getOption')
            ->will($this->returnCallback(function($option) {
                switch($option) {
                    case 'value_format':
                    case 'value_glue':
                        return null;

                    case 'empty_value':
                        return '';

                    case 'field_mapping':
                        return [];
                }
            }));

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue(['foo', 'bar']));

        $this->expectException(InvalidArgumentException::class);
        $extension->buildCellView($column, $view);
    }

    public function test_build_cell_View_with_valid_template()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $column->expects($this->exactly(5))
            ->method('getOption')
            ->will($this->returnCallback(function($option) {
                switch($option) {
                    case 'value_format':
                        return '<b>%s</b>';

                    case 'value_glue':
                    case 'empty_value':
                        return '';

                    case 'field_mapping':
                        return [];
                }
            }));

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue(['foo']));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('<b>foo</b>');

        $extension->buildCellView($column, $view);
    }

    public function test_build_cell_view_with_valid_format_and_value_array()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $column->expects($this->exactly(5))
            ->method('getOption')
            ->will($this->returnCallback(function($option) {
                switch($option) {
                    case 'value_format':
                        return '<b>%s</b><br/><b>%s</b>';

                    case 'value_glue':
                        return null;

                    case 'empty_value':
                        return '';

                    case 'field_mapping':
                        return [];
                }
            }));

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue(['foo', 'bar']));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('<b>foo</b><br/><b>bar</b>');

        $extension->buildCellView($column, $view);
    }

    public function test_build_cell_view_with_format_that_have_too_many_placeholders()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $column->expects($this->exactly(5))
            ->method('getOption')
            ->will($this->returnCallback(function($option) {
                switch($option) {
                    case 'value_format':
                        return '%s%s';

                    case 'value_glue':
                        return null;

                    case 'empty_value':
                        return '';

                    case 'field_mapping':
                        return [];
                }
            }));

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue(['foo']));

        $this->expectException(Error::class);
        $extension->buildCellView($column, $view);
    }

    public function test_build_cell_view_with_format_that_have_not_enough_placeholders()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $column->expects($this->exactly(5))
            ->method('getOption')
            ->will($this->returnCallback(function($option) {
                switch($option) {
                    case 'value_format':
                        return '<b>%s</b>';

                    case 'value_glue':
                        return null;

                    case 'empty_value':
                        return '';

                    case 'field_mapping':
                        return [];
                }
            }));

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue(['foo', 'bar']));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('<b>foo</b>');

        $extension->buildCellView($column, $view);
    }

    public function test_build_cell_view_with_empty_template()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $column->expects($this->exactly(5))
            ->method('getOption')
            ->will($this->returnCallback(function($option) {
                switch($option) {
                    case 'value_format':
                        return '';

                    case 'value_glue':
                        break;

                    case 'empty_value':
                        return '';

                    case 'field_mapping':
                        return [];
                }
            }));

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue(['foo']));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('');

        $extension->buildCellView($column, $view);
    }

    public function test_build_cell_view_without_empty_value()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $column->expects($this->exactly(5))
            ->method('getOption')
            ->will($this->returnCallback(function($option) {
                switch($option) {
                    case 'value_format':
                        return null;

                    case 'value_glue':
                        return ' ';

                    case 'empty_value':
                        return '';

                    case 'field_mapping':
                        return [];
                }
            }));

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue([null]));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('');

        $extension->buildCellView($column, $view);
    }

    public function test_build_cell_view_with_empty_value()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $column->expects($this->exactly(5))
            ->method('getOption')
            ->will($this->returnCallback(function($option) {
                switch($option) {
                    case 'value_format':
                    case 'value_glue':
                        return null;

                    case 'empty_value':
                        return 'empty';

                    case 'field_mapping':
                        return [];
                }
            }));

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue([null]));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('empty');

        $extension->buildCellView($column, $view);
    }

    public function test_build_cell_view_with_empty_value_and_multiple_values()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $column->expects($this->exactly(5))
            ->method('getOption')
            ->will($this->returnCallback(function($option) {
                switch($option) {
                    case 'value_format':
                        return null;

                    case 'value_glue':
                        return ' ';

                    case 'empty_value':
                        return 'empty';

                    case 'field_mapping':
                        return [];
                }
            }));

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue([
                'val',
                '',
                null,
            ]));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('val empty empty');

        $extension->buildCellView($column, $view);
    }

    public function test_build_cell_view_with_multiple_empty_value_and_multiple_values()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $column->expects($this->exactly(5))
            ->method('getOption')
            ->will($this->returnCallback(function($option) {
                switch($option) {
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
            }));

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue('default'));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('default');

        $extension->buildCellView($column, $view);
    }

    public function test_build_cell_view_with_empty_value_that_not_exists_in_mapping_fields()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $column->expects($this->exactly(2))
            ->method('getOption')
            ->will($this->returnCallback(function($option) {
                switch($option) {
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
            }));

        $this->expectException(InvalidArgumentException::class);
        $extension->buildCellView($column, $view);
    }

    public function test_build_cell_view_with_multiple_empty_value_multiple_values_and_template()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $column->expects($this->exactly(5))
            ->method('getOption')
            ->will($this->returnCallback(function($option) {
                switch($option) {
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
            }));

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue([
                'fo' => '',
                'ba' => '',
                'ca' => null,
            ]));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('"empty-fo" "empty-bar" ""');

        $extension->buildCellView($column, $view);
    }

    public function test_build_cell_view_with_format_that_is_clousure()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $column->expects($this->exactly(5))
            ->method('getOption')
            ->will($this->returnCallback(function($option) {
                switch($option) {
                    case 'value_format':
                        return function($data) {
                            return $data['fo'] . '-' . $data['ba'];
                        };

                    case 'value_glue':
                        return null;

                    case 'empty_value':
                        return [];

                    case 'field_mapping':
                        return ['fo', 'ba'];
                }
            }));

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue([
                'fo' => 'fo',
                'ba' => 'ba',
                ]
        ));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('fo-ba');

        $extension->buildCellView($column, $view);
    }

    public function test_build_cell_view_with_value_that_is_zero()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->createMock(CellViewInterface::class);
        $column = $this->createMock(ColumnTypeInterface::class);

        $column->expects($this->exactly(5))
            ->method('getOption')
            ->will($this->returnCallback(function($option) {
                switch($option) {
                    case 'value_glue':
                        return '';

                    case 'empty_value':
                        return 'This should not be used.';

                    case 'field_mapping':
                        return ['fo'];
                }
            }));

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue([
                    'fo' => 0,
                ]
            ));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with(0);

        $extension->buildCellView($column, $view);
    }

    public function test_set_value_format_that_is_clousure()
    {
        $column = new Text();
        $extension = new ValueFormatColumnOptionsExtension();
        $column->addExtension($extension);

        $column->initOptions();
        $extension->initOptions($column);

        $column->setOptions([
            'value_format' => function($data) {
                return (string) $data;
            }
        ]);

        $this->assertEquals(['for' => 'bar'], $extension->filterValue($column, ['for' => 'bar']));
    }
}
