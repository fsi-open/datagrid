<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Tests\Extension\Doctrine\ColumntypeExtension;

use FSi\Component\DataGrid\Column\ColumnInterface;
use FSi\Component\DataGrid\Exception\DataGridException;
use FSi\Component\DataGrid\Extension\Doctrine\ColumnTypeExtension\ValueFormatColumnOptionsExtension;
use PHPUnit\Framework\TestCase;

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

    public function testGlueAndEmptyValueAsStringWithoutOneValue()
    {
        $options = [
            'empty_value' => 'no',
            'value_glue' => ' ',
        ];

        $this->assertFilteredValue($options, [0 => ['id' => null, 'name' => 'Foo']], 'no Foo');
    }

    public function testGlueAndEmptyValueAsStringWithoutValues()
    {
        $options = [
            'empty_value' => 'no',
            'value_glue' => ' ',
        ];

        $this->assertFilteredValue($options, [0 => ['id' => null, 'name' => null]], 'no no');
    }

    public function testGlueAndEmptyValueAsArrayWithoutOneValue()
    {
        $options = [
            'empty_value' => ['name' => 'no'],
            'value_glue' => ' ',
        ];

        $this->assertFilteredValue($options, [0 => ['id' => 1, 'name' => null]], '1 no');
    }

    public function testGlueAndEmptyValueAsArrayWithoutValues()
    {
        $options = [
            'empty_value' => ['id' => 'no', 'name' => 'no'],
            'value_glue' => ' ',
        ];

        $this->assertFilteredValue($options, [0 => ['id' => null, 'name' => null]], 'no no');
    }

    public function testGlueMultipleAndEmptyValueAsArrayWithoutMultipleValues()
    {
        $options = [
            'empty_value' => ['id' => 'no', 'name' => 'no'],
            'glue_multiple' => '<br />',
            'value_glue' => ' ',
        ];

        $value = [
            0 => [
                'id' => null,
                'name' => null
            ],
            1 => [
                'id' => null,
                'name' => 'Foo'
            ],
        ];

        $this->assertFilteredValue($options, $value, 'no no<br />no Foo');
    }

    public function testGlueAndEmptyValueAsArrayWithoutKeyInEmptyValue()
    {
        $options = [
            'empty_value' => ['id2' => 'no', 'name' => 'no'],
        ];

        $this->expectException(DataGridException::class);
        $this->assertFilteredValue($options, [0 => ['id' => null, 'name' => 'Foo']], 'unreachable');
    }

    public function testMissingFormatAndGlue()
    {
        $this->assertFilteredValue([], [0 => ['id' => 1, 'name' => 'Foo']], '');
    }

    public function testFormatAndGlue()
    {
        $options = [
            'value_format' => '(%s)',
            'value_glue' => '<br />',
        ];

        $this->assertFilteredValue($options, [0 => ['id' => 1, 'name' => 'Foo']], '(1)<br />(Foo)');
    }

    public function testFormatWithTooManyPlaceholders()
    {
        $options = [
            'value_format' => '(%s) (%s)',
            'value_glue' => '<br />',
        ];

        $this->expectException(\PHPUnit_Framework_Error::class);
        $this->assertFilteredValue($options, [0 => ['id' => 1, 'name' => 'Foo']], 'unreachable');
    }

    public function testFormatGlueAndGlueMultiple()
    {
        $options = [
            'glue_multiple' => '<br />',
            'value_format' => '(%s)',
            'value_glue' => ' ',
        ];

        $value = [
            0 => [
                'id' => 1,
                'name' => 'Foo',
            ],
            1 => [
                'id' => 2,
                'name' => 'Bar',
            ],
        ];

        $this->assertFilteredValue($options, $value, '(1) (Foo)<br />(2) (Bar)');
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
