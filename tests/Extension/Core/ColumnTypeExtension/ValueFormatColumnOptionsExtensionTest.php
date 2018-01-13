<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Tests\Extension\Core\ColumnTypeExtension;

use FSi\Component\DataGrid\Column\ColumnInterface;
use FSi\Component\DataGrid\DataGridFactory;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension\DefaultColumnOptionsExtension;
use FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension\ValueFormatColumnOptionsExtension;
use FSi\Component\DataGrid\Extension\Core\ColumnType\Text;
use FSi\Component\DataGrid\Tests\Fixtures\SimpleDataGridExtension;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;

class ValueFormatColumnOptionsExtensionTest extends TestCase
{
    /**
     * @var ValueFormatColumnOptionsExtension
     */
    private $extension;

    protected function setUp()
    {
        $this->extension = new ValueFormatColumnOptionsExtension();
    }

    public function testValueGlueOption()
    {
        $options = [
            'empty_value' => '',
            'field_mapping' => [],
            'value_glue' => '-',
        ];

        $this->assertFilteredValue($options, ['foo', 'bar'], 'foo-bar');
    }

    public function testEmptyFormatOptions()
    {
        $options = [
            'empty_value' => '',
            'field_mapping' => [],
            'value_glue' => null,
            'value_format' => null,
        ];

        $this->assertFilteredValue($options, ['foo'], 'foo');
    }

    public function testFormatAndGlueOptions()
    {
        $options = [
            'value_format' => '<b>%s</b>',
            'value_glue' => '<br/>',
            'empty_value' => '',
            'field_mapping' => [],
        ];

        $this->assertFilteredValue($options, ['foo', 'bar'], '<b>foo</b><br/><b>bar</b>');
    }

    public function testEmptyFormatAndGlueWithArrayValue()
    {
        $options = [
            'value_format' => null,
            'value_glue' => null,
            'empty_value' => '',
            'field_mapping' => [],
        ];

        $this->expectException(InvalidArgumentException::class);
        $this->assertFilteredValue($options, ['foo', 'bar'], 'unreachable');
    }

    public function testTemplate()
    {
        $options = [
            'value_format' => '<b>%s</b>',
            'value_glue' => '',
            'empty_value' => '',
            'field_mapping' => [],
        ];

        $this->assertFilteredValue($options, ['foo'], '<b>foo</b>');
    }

    public function testFormatWithoutGlueWithArrayValue()
    {
        $options = [
            'value_format' => '<b>%s</b><br/><b>%s</b>',
            'value_glue' => null,
            'empty_value' => '',
            'field_mapping' => [],
        ];

        $this->assertFilteredValue($options, ['foo', 'bar'], '<b>foo</b><br/><b>bar</b>');
    }

    public function testFormatThatHaveTooManyPlaceholders()
    {
        $options = [
            'value_format' => '%s%s',
            'value_glue' => null,
            'empty_value' => '',
            'field_mapping' => [],
        ];

        $this->expectException(\PHPUnit_Framework_Error::class);
        $this->assertFilteredValue($options, ['foo'], 'unreachable');
    }

    public function testFormatThatHaveNotEnoughPlaceholders()
    {
        $options = [
            'value_format' => '<b>%s</b>',
            'value_glue' => null,
            'empty_value' => '',
            'field_mapping' => [],
        ];

        $this->assertFilteredValue($options, ['foo', 'bar'], '<b>foo</b>');
    }

    public function testEmptyTemplate()
    {
        $options = [
            'empty_value' => '',
            'value_format' => '',
            'field_mapping' => [],
        ];

        $this->assertFilteredValue($options, ['foo', 'bar'], '');
    }

    public function testArrayEmptyValue()
    {
        $options = [
            'empty_value' => [],
            'field_mapping' => [],
        ];

        $this->assertFilteredValue($options, [null], '');
    }

    public function testEmptyValue()
    {
        $options = [
            'empty_value' => 'empty',
            'field_mapping' => [],
        ];

        $this->assertFilteredValue($options, [null], 'empty');
    }

    public function testSingleEmptyValueWithArrayValue()
    {
        $options = [
            'empty_value' => 'empty',
            'value_glue' => ' ',
            'field_mapping' => [],
        ];

        $this->assertFilteredValue($options, ['val', '', null], 'val empty empty');
    }

    public function testMultipleEmptyValueWithArrayValue()
    {
        $options = [
            'empty_value' => [
                'fo' => 'foo',
                'ba' => 'bar'
            ],
            'value_glue' => ' ',
            'field_mapping' => ['fo', 'ba'],
        ];

        $this->assertFilteredValue($options, ['fo' => null, 'ba' => null], 'foo bar');
    }

    public function test_build_cell_view_with_empty_value_that_not_exists_in_mapping_fields()
    {
        $options = [
            'empty_value' => [
                'fo' => 'empty',
            ],
            'field_mapping' => ['fos'],
        ];

        $this->expectException(InvalidArgumentException::class);
        $this->assertFilteredValue($options, ['unused'], 'unreachable');
    }

    public function testMultipleEmptyValueWithArrayValueAndTemplate()
    {
        $options = [
            'empty_value' => [
                'fo' => 'empty-foo',
                'ba' => 'empty-bar'
            ],
            'value_format' => '"%s" "%s" "%s"',
            'field_mapping' => ['fo', 'ba', 'ca'],
        ];

        $this->assertFilteredValue($options, ['fo' => '', 'ba' => '', 'ca' => null], '"empty-foo" "empty-bar" ""');
    }

    public function testClosureFormat()
    {
        $options = [
            'empty_value' => [],
            'value_format' => function($data) {
                return $data['fo'] . '-' . $data['ba'];
            },
            'field_mapping' => ['fo', 'ba'],
        ];

        $this->assertFilteredValue($options, ['fo' => 'foo', 'ba' => 'bar'], 'foo-bar');
    }

    public function testZeroValue()
    {
        $options = [
            'empty_value' => 'should not be used',
            'value_glue' => '',
            'field_mapping' => ['fo'],
        ];

        $this->assertFilteredValue($options, ['fo' => 0], '0');
    }

    public function test_set_value_format_that_is_clousure()
    {
        $dataGridFactory = new DataGridFactory(
            new EventDispatcher(),
            [
                new SimpleDataGridExtension(new DefaultColumnOptionsExtension(), new Text()),
                new SimpleDataGridExtension($this->extension, null),
            ]
        );

        $column = $dataGridFactory->createColumn($this->createMock(DataGridInterface::class), Text::class, 'text', [
            'field_mapping' => ['text'],
            'value_format' => function($data) {
                return sprintf('%s %s', $data['text'], $data['text']);
            }
        ]);
        $cellView = $dataGridFactory->createCellView($column, 0, (object) ['text' => 'bar']);

        $this->assertSame('bar bar', $cellView->getValue());
    }

    private function assertFilteredValue(array $options, $value, $filteredValue): void
    {
        $column = $this->createMock(ColumnInterface::class);

        $column->expects($this->any())
            ->method('getOption')
            ->will($this->returnCallback(function(string $option) use ($options) {
                if (array_key_exists($option, $options)) {
                    return $options[$option];
                }
            }));

        $this->assertSame($filteredValue, $this->extension->filterValue($column, $value));
    }
}
