<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Tests\Extension\Core\ColumnType;

use FSi\Component\DataGrid\Extension\Core\ColumnType\DateTime;
use FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension\DefaultColumnOptionsExtension;
use FSi\Component\DataGrid\Exception\DataGridColumnException;
use PHPUnit\Framework\TestCase;

class DateTimeTest extends TestCase
{
    /**
     * @var DateTime
     */
    private $column;

    protected function setUp()
    {
        $column = new DateTime();
        $column->setName('datetime');
        $column->initOptions();

        $extension = new DefaultColumnOptionsExtension();
        $extension->initOptions($column);

        $this->column = $column;
    }

    public function testBasicFilterValue()
    {
        $dateTimeObject = new \DateTime('2012-05-03 12:41:11');

        $value = [
            'datetime' => $dateTimeObject
        ];

        $this->column->setOption('field_mapping', ['datetime']);

        $this->assertSame(
            $this->column->filterValue($value),
            [
                'datetime' => $dateTimeObject->format('Y-m-d H:i:s')
            ]
        );
    }

    public function testFilterValueFromDateTimeImmutable()
    {
        if (!class_exists(\DateTimeImmutable::class)) {
            $this->markTestSkipped();
        }

        $dateTimeObject = new \DateTimeImmutable('2012-05-03 12:41:11');

        $value = [
            'datetime' => $dateTimeObject
        ];

        $this->column->setOption('field_mapping', ['datetime']);

        $this->assertSame(
            $this->column->filterValue($value),
            [
                'datetime' => $dateTimeObject->format('Y-m-d H:i:s')
            ]
        );
    }

    public function testFilterValueWithNull()
    {
        $value = [
            'datetime' => null
        ];

        $this->column->setOptions([
        ]);

        $this->assertSame(
            $this->column->filterValue($value),
            [
                'datetime' => null
            ]
        );

        $inputTypes = ['datetime', 'string', 'timestamp'];
        if (interface_exists(\DateTimeInterface::class)) {
            $inputTypes[] = 'datetime_interface';
        }

        foreach ($inputTypes as $input_type) {

            $this->column->setOptions([
                'input_type' => $input_type
            ]);

            $this->assertSame(
                $this->column->filterValue($value),
                [
                    'datetime' => null
                ]
            );
        }
    }

    public function testFormatOption()
    {
        $dateTimeObject = new \DateTime('2012-05-03 12:41:11');

        $value = [
            'datetime' => $dateTimeObject
        ];

        $this->column->setOptions([
            'field_mapping' => ['datetime'],
            'datetime_format' => 'Y.d.m'
        ]);

        $this->assertSame(
            $this->column->filterValue($value),
            [
                'datetime' => $dateTimeObject->format('Y.d.m')
            ]
        );
    }

    public function testFormatOptionWithDateTimeImmutable()
    {
        if (!class_exists(\DateTimeImmutable::class)) {
            $this->markTestSkipped();
        }

        $dateTimeObject = new \DateTimeImmutable('2012-05-03 12:41:11');

        $value = [
            'datetime' => $dateTimeObject
        ];

        $this->column->setOptions([
            'field_mapping' => ['datetime'],
            'datetime_format' => 'Y.d.m'
        ]);

        $this->assertSame(
            $this->column->filterValue($value),
            [
                'datetime' => $dateTimeObject->format('Y.d.m')
            ]
        );
    }

    public function testMappingFieldsOptionInputTimestamp()
    {
        $dateTimeObject = new \DateTime('2012-05-03 12:41:11');
        $brokenValue = [
            'datetime' => $dateTimeObject
        ];
        $value = [
            'datetime' => $dateTimeObject->getTimestamp()
        ];

        $this->column->setOptions([
            'input_type' => 'timestamp',
        ]);

        $this->column->filterValue($value);
        $this->assertSame(
            $this->column->filterValue($value),
            [
                'datetime' => $dateTimeObject->format('Y-m-d H:i:s')
            ]
        );

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Value in column "datetime" should be timestamp but "object" type was detected. Maybe you should consider using different "input" opition value?');
        $this->column->filterValue($brokenValue);
    }

    public function testMappingFieldsOptionInputStringMissingMappingFieldsFormat()
    {
        $dateTimeObject = new \DateTime('2012-05-03 12:41:11');
        $value = [
            'datetime' => $dateTimeObject->format('Y-m-d H:i:s')
        ];

        $this->column->setOption('input_type', 'string');

        $this->expectException(DataGridColumnException::class);
        $this->expectExceptionMessage('"mapping_fields_format" option is missing. Example: "mapping_fields_format" => "Y-m-d H:i:s"');
        $this->column->filterValue($value);
    }

    public function testMappingFieldsOptionInputString()
    {
        $dateTimeObject = new \DateTime('2012-05-03 12:41:11');

        $brokenValue = [
            'datetime' => $dateTimeObject
        ];

        $value = [
            'datetime' => $dateTimeObject->format('Y-m-d H:i:s')
        ];

        $this->column->setOptions([
            'input_type' => 'string',
            'input_field_format' => 'Y-m-d H:i:s'
        ]);

        $this->assertSame(
            $this->column->filterValue($value),
            [
                'datetime' => $dateTimeObject->format('Y-m-d H:i:s')
            ]
        );

        $this->expectException(DataGridColumnException::class);
        $this->expectExceptionMessage('Value in field "datetime" is not a valid string.');
        $this->column->filterValue($brokenValue);
    }

    public function testMappingFieldsOptionInputArrayMissingMappingFieldsFormat()
    {
        $dateTimeObject = new \DateTime('2012-05-03 12:41:11');
        $dateObject = new \DateTime('2012-05-03');

        $value = [
            'datetime' => $dateTimeObject->format('Y-m-d H:i:s'),
            'time' => $dateObject->format('Y-m-d H:i:s')
        ];

        $this->column->setOption('input_type', 'array');
        $this->expectException(DataGridColumnException::class);
        $this->expectExceptionMessage('"input_field_format" option is missing. Example: "input_field_format" => array("mapping_field_name" => array("input" => "datetime"))');
        $this->column->filterValue($value);
    }

    public function testMappingFieldsOptionInputArrayMissingMappingFieldsFormatForDateTimeImmutable()
    {
        if (!class_exists(\DateTimeImmutable::class)) {
            $this->markTestSkipped();
        }

        $dateTimeObject = new \DateTimeImmutable('2012-05-03 12:41:11');
        $dateObject = new \DateTimeImmutable('2012-05-03');

        $value = [
            'datetime' => $dateTimeObject->format('Y-m-d H:i:s'),
            'time' => $dateObject->format('Y-m-d H:i:s')
        ];

        $this->column->setOption('input_type', 'array');

        $this->expectException(DataGridColumnException::class);
        $this->expectExceptionMessage('"input_field_format" option is missing. Example: "input_field_format" => array("mapping_field_name" => array("input" => "datetime"))');
        $this->column->filterValue($value);
    }

    public function testMappingFieldsOptionInputArrayWrongMappingFieldsFormat()
    {
        $dateTimeObject = new \DateTime('2012-05-03 12:41:11');
        $dateObject = new \DateTime('2012-05-03');
        $value = [
            'datetime' => $dateTimeObject->format('Y-m-d H:i:s'),
            'time' => $dateObject->format('Y-m-d H:i:s')
        ];

        $this->column->setOptions([
            'input_type' => 'string',
            'input_field_format' => [
                'datetime' => 'string',
                'time' => 'string'
            ]
        ]);

        $this->expectException(DataGridColumnException::class);
        $this->expectExceptionMessage('When using input type "string", "mapping_fields_format" option must be an string that contains valid data format');
        $this->column->filterValue($value);
    }

    public function testMappingFieldsOptionInputArray()
    {
        $dateTimeObject = new \DateTime('2012-05-03 12:41:11');
        if (class_exists(\DateTimeImmutable::class)) {
            $dateTimeImmutableObject = new \DateTimeImmutable('2012-05-03 12:41:11');
        }
        $dateObject = new \DateTime('2012-05-03');
        $value = [
            'datetime' => $dateTimeObject,
            'time' => $dateObject,
            'string' => $dateTimeObject->format('Y-m-d H:i:s'),
            'timestamp' => $dateTimeObject->getTimestamp()
        ];
        if (class_exists(\DateTimeImmutable::class)) {
            $value['datetime_immutable'] = $dateTimeImmutableObject;
        }

        $inputFieldFormat = [
            'datetime' => ['input_type' => 'datetime'],
            'time' => ['input_type' => 'datetime'],
            'string' => ['input_type' => 'string', 'datetime_format' => 'Y-m-d H:i:s'],
            'timestamp' => ['input_type' => 'timestamp']
        ];
        if (class_exists(\DateTimeImmutable::class)) {
            $inputFieldFormat['datetime_immutable'] = ['input_type' => 'datetime_interface'];
        }
        $this->column->setOptions([
            'input_type' => 'array',
            'input_field_format' => $inputFieldFormat
        ]);

        $expectedResult = [
            'datetime' => $dateTimeObject->format('Y-m-d H:i:s'),
            'time' => $dateObject->format('Y-m-d 00:00:00'),
            'string' => $dateTimeObject->format('Y-m-d H:i:s'),
            'timestamp' => date('Y-m-d H:i:s', $dateTimeObject->getTimestamp()),
        ];
        if (class_exists(\DateTimeImmutable::class)) {
            $expectedResult['datetime_immutable'] = $dateTimeImmutableObject->format('Y-m-d H:i:s');
        }
        $this->assertSame($this->column->filterValue($value), $expectedResult);
    }

    public function testMappingFieldsOptionInputArrayWithFormat()
    {
        $dateTimeObject = new \DateTime('2012-05-03 12:41:11');
        if (class_exists(\DateTimeImmutable::class)) {
            $dateTimeImmutableObject = new \DateTimeImmutable('2012-05-03 12:41:11');
        }
        $dateObject = new \DateTime('2012-05-03');
        $value = [
            'datetime' => $dateTimeObject,
            'time' => $dateObject,
            'string' => $dateTimeObject->format('Y-m-d H:i:s'),
            'timestamp' => $dateTimeObject->getTimestamp()
        ];
        if (class_exists(\DateTimeImmutable::class)) {
            $value['datetime_immutable'] = $dateTimeImmutableObject;
        }

        $inputFieldFormat = [
            'datetime' => ['input_type' => 'datetime'],
            'time' => ['input_type' => 'datetime'],
            'string' => ['input_type' => 'string', 'datetime_format' => 'Y-m-d H:i:s'],
            'timestamp' => ['input_type' => 'timestamp']
        ];

        if (class_exists(\DateTimeImmutable::class)) {
            $inputFieldFormat['datetime_immutable'] = ['input_type' => 'datetime_interface'];
        }
        $this->column->setOptions([
            'input_type' => 'array',
            'datetime_format' => 'Y.d.m',
            'input_field_format' => $inputFieldFormat
        ]);

        $expectedResult = [
            'datetime' => $dateTimeObject->format('Y.d.m'),
            'time' => $dateObject->format('Y.d.m'),
            'string' => $dateTimeObject->format('Y.d.m'),
            'timestamp' => $dateTimeObject->format('Y.d.m')
        ];
        if (class_exists(\DateTimeImmutable::class)) {
            $expectedResult['datetime_immutable'] = $dateTimeImmutableObject->format('Y.d.m');
        }
        $this->assertSame($this->column->filterValue($value), $expectedResult);
    }
}
