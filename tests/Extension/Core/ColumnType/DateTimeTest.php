<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Tests\Extension\Core\ColumnType;

use FSi\Component\DataGrid\DataGridFactory;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataGrid\Extension\Core\ColumnType\DateTime;
use FSi\Component\DataGrid\Exception\DataGridColumnException;
use FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension\DefaultColumnOptionsExtension;
use FSi\Component\DataGrid\Tests\Fixtures\SimpleDataGridExtension;
use PHPUnit\Framework\TestCase;

class DateTimeTest extends TestCase
{
    /**
     * @var DataGridFactoryInterface
     */
    private $dataGridFactory;

    public function setUp()
    {
        $this->dataGridFactory = new DataGridFactory(
            [new SimpleDataGridExtension(new DefaultColumnOptionsExtension(), new DateTime())]
        );
    }

    public function testDateTimeValue()
    {
        $column = $this->dataGridFactory->createColumn($this->getDataGridMock(), DateTime::class, 'datetime', [
            'field_mapping' => ['datetime'],
        ]);

        $dateTimeObject = new \DateTime('2012-05-03 12:41:11');
        $cellView = $this->dataGridFactory->createCellView($column, (object) [
            'datetime' => $dateTimeObject,
        ]);

        $this->assertSame(
            ['datetime' => $dateTimeObject->format('Y-m-d H:i:s')],
            $cellView->getValue()
        );
    }

    public function testDateTimeImmutableValue()
    {
        if (!class_exists(\DateTimeImmutable::class)) {
            $this->markTestSkipped();
        }

        $column = $this->dataGridFactory->createColumn($this->getDataGridMock(), DateTime::class, 'datetime', [
            'field_mapping' => ['datetime'],
        ]);

        $dateTimeObject = new \DateTimeImmutable('2012-05-03 12:41:11');
        $cellView = $this->dataGridFactory->createCellView($column, (object) [
            'datetime' => $dateTimeObject,
        ]);

        $this->assertSame(
            ['datetime' => $dateTimeObject->format('Y-m-d H:i:s')],
            $cellView->getValue()
        );
    }

    public function testNullValue()
    {
        $column = $this->dataGridFactory->createColumn($this->getDataGridMock(), DateTime::class, 'datetime', [
            'field_mapping' => ['datetime'],
        ]);

        $cellView = $this->dataGridFactory->createCellView($column, (object) [
            'datetime' => null,
        ]);

        $this->assertSame(['datetime' => null], $cellView->getValue());

        $inputTypes = ['datetime', 'string', 'timestamp'];
        if (interface_exists(\DateTimeInterface::class)) {
            $inputTypes[] = 'datetime_interface';
        }

        foreach ($inputTypes as $input_type) {
            $column = $this->dataGridFactory->createColumn($this->getDataGridMock(), DateTime::class, 'datetime', [
                'field_mapping' => ['datetime'],
                'input_type' => $input_type,
            ]);

            $cellView = $this->dataGridFactory->createCellView($column, (object) [
                'datetime' => null,
            ]);

            $this->assertSame(['datetime' => null], $cellView->getValue());
        }
    }

    public function testFormatOption()
    {
        $column = $this->dataGridFactory->createColumn($this->getDataGridMock(), DateTime::class, 'datetime', [
            'field_mapping' => ['datetime'],
            'datetime_format' => 'Y.d.m',
        ]);

        $dateTimeObject = new \DateTime('2012-05-03 12:41:11');
        $cellView = $this->dataGridFactory->createCellView($column, (object) [
            'datetime' => $dateTimeObject,
        ]);

        $this->assertSame(
            ['datetime' => $dateTimeObject->format('Y.d.m')],
            $cellView->getValue()
        );
    }

    public function testFormatOptionWithDateTimeImmutable()
    {
        if (!class_exists(\DateTimeImmutable::class)) {
            $this->markTestSkipped();
        }

        $column = $this->dataGridFactory->createColumn($this->getDataGridMock(), DateTime::class, 'datetime', [
            'field_mapping' => ['datetime'],
            'datetime_format' => 'Y.d.m',
        ]);

        $dateTimeObject = new \DateTimeImmutable('2012-05-03 12:41:11');
        $cellView = $this->dataGridFactory->createCellView($column, (object) [
            'datetime' => $dateTimeObject,
        ]);

        $this->assertSame(
            ['datetime' => $dateTimeObject->format('Y.d.m')],
            $cellView->getValue()
        );
    }

    public function testTimestampValue()
    {
        $dateTimeObject = new \DateTime('2012-05-03 12:41:11');
        $brokenValue = (object) [
            'datetime' => $dateTimeObject
        ];
        $value = (object) [
            'datetime' => $dateTimeObject->getTimestamp()
        ];

        $column = $this->dataGridFactory->createColumn($this->getDataGridMock(), DateTime::class, 'datetime', [
            'field_mapping' => ['datetime'],
            'input_type' => 'timestamp',
        ]);

        $cellView = $this->dataGridFactory->createCellView($column, $value);

        $this->assertSame(
            ['datetime' => $dateTimeObject->format('Y-m-d H:i:s')],
            $cellView->getValue()
        );

        $this->expectException(\InvalidArgumentException::class);
        $this->dataGridFactory->createCellView($column, $brokenValue);
    }

    public function testStringValueWithMissingFieldsFormat()
    {
        $dateTimeObject = new \DateTime('2012-05-03 12:41:11');
        $value = (object) [
            'datetime' => $dateTimeObject->format('Y-m-d H:i:s')
        ];

        $column = $this->dataGridFactory->createColumn($this->getDataGridMock(), DateTime::class, 'datetime', [
            'field_mapping' => ['datetime'],
            'input_type' => 'string',
        ]);

        $this->expectException(DataGridColumnException::class);
        $this->dataGridFactory->createCellView($column, $value);
    }

    public function testStringValue()
    {
        $dateTimeObject = new \DateTime('2012-05-03 12:41:11');
        $brokenValue = (object) [
            'datetime' => $dateTimeObject
        ];
        $value = (object) [
            'datetime' => $dateTimeObject->format('Y-m-d H:i:s')
        ];

        $column = $this->dataGridFactory->createColumn($this->getDataGridMock(), DateTime::class, 'datetime', [
            'field_mapping' => ['datetime'],
            'input_field_format' => 'Y-m-d H:i:s',
            'input_type' => 'string',
        ]);
        $cellView = $this->dataGridFactory->createCellView($column, $value);

        $this->assertSame(
            ['datetime' => $dateTimeObject->format('Y-m-d H:i:s')],
            $cellView->getValue()
        );

        $this->expectException(DataGridColumnException::class);
        $this->dataGridFactory->createCellView($column, $brokenValue);
    }

    public function testArrayValueWithMissingFieldsFormat()
    {
        $dateTimeObject = new \DateTime('2012-05-03 12:41:11');
        $dateObject = new \DateTime('2012-05-03');
        $value = (object) [
            'datetime' => $dateTimeObject->format('Y-m-d H:i:s'),
            'time' => $dateObject->format('Y-m-d H:i:s'),
        ];

        $column = $this->dataGridFactory->createColumn($this->getDataGridMock(), DateTime::class, 'datetime', [
            'field_mapping' => ['datetime', 'time'],
            'input_type' => 'array',
        ]);

        $this->expectException(DataGridColumnException::class);
        $this->dataGridFactory->createCellView($column, $value);
    }

    public function testArrayValueWithMissingFieldsFormatForDateTimeImmutable()
    {
        if (!class_exists(\DateTimeImmutable::class)) {
            $this->markTestSkipped();
        }

        $dateTimeObject = new \DateTimeImmutable('2012-05-03 12:41:11');
        $dateObject = new \DateTimeImmutable('2012-05-03');
        $value = (object) [
            'datetime' => $dateTimeObject->format('Y-m-d H:i:s'),
            'time' => $dateObject->format('Y-m-d H:i:s'),
        ];

        $column = $this->dataGridFactory->createColumn($this->getDataGridMock(), DateTime::class, 'datetime', [
            'field_mapping' => ['datetime', 'time'],
            'input_type' => 'array',
        ]);

        $this->expectException(DataGridColumnException::class);
        $this->dataGridFactory->createCellView($column, $value);
    }

    public function testArrayValueWithWrongFieldsFormat()
    {
        $dateTimeObject = new \DateTime('2012-05-03 12:41:11');
        $dateObject = new \DateTime('2012-05-03');
        $value = (object) [
            'datetime' => $dateTimeObject->format('Y-m-d H:i:s'),
            'time' => $dateObject->format('Y-m-d H:i:s'),
        ];

        $column = $this->dataGridFactory->createColumn($this->getDataGridMock(), DateTime::class, 'datetime', [
            'field_mapping' => ['datetime', 'time'],
            'input_type' => 'string',
            'input_field_format' => [
                'datetime' => 'string',
                'time' => 'string'
            ]
        ]);

        $this->expectException(DataGridColumnException::class);
        $this->dataGridFactory->createCellView($column, $value);
    }

    public function testArrayValue()
    {
        $dateTimeObject = new \DateTime('2012-05-03 12:41:11');
        $dateObject = new \DateTime('2012-05-03');
        $value = [
            'datetime' => $dateTimeObject,
            'time' => $dateObject,
            'string' => $dateTimeObject->format('Y-m-d H:i:s'),
            'timestamp' => $dateTimeObject->getTimestamp()
        ];
        if (class_exists(\DateTimeImmutable::class)) {
            $dateTimeImmutableObject = new \DateTimeImmutable('2012-05-03 12:41:11');
            $value['datetime_immutable'] = $dateTimeImmutableObject;
        }
        $value = (object) $value;

        $fieldMapping = ['datetime', 'time', 'string', 'timestamp'];
        $inputFieldFormat = [
            'datetime' => ['input_type' => 'datetime'],
            'time' => ['input_type' => 'datetime'],
            'string' => ['input_type' => 'string', 'datetime_format' => 'Y-m-d H:i:s'],
            'timestamp' => ['input_type' => 'timestamp']
        ];
        if (class_exists(\DateTimeImmutable::class)) {
            $fieldMapping[] = 'datetime_immutable';
            $inputFieldFormat['datetime_immutable'] = ['input_type' => 'datetime_interface'];
        }

        $column = $this->dataGridFactory->createColumn($this->getDataGridMock(), DateTime::class, 'datetime', [
            'field_mapping' => $fieldMapping,
            'input_type' => 'array',
            'input_field_format' => $inputFieldFormat,
        ]);
        $cellView = $this->dataGridFactory->createCellView($column, $value);

        $expectedResult = [
            'datetime' => $dateTimeObject->format('Y-m-d H:i:s'),
            'time' => $dateObject->format('Y-m-d 00:00:00'),
            'string' => $dateTimeObject->format('Y-m-d H:i:s'),
            'timestamp' => date('Y-m-d H:i:s', $dateTimeObject->getTimestamp()),
        ];
        if (class_exists(\DateTimeImmutable::class)) {
            $expectedResult['datetime_immutable'] = $dateTimeImmutableObject->format('Y-m-d H:i:s');
        }

        $this->assertSame($expectedResult, $cellView->getValue());
    }

    public function testArrayValueWithFormat()
    {
        $dateTimeObject = new \DateTime('2012-05-03 12:41:11');
        $dateObject = new \DateTime('2012-05-03');
        $value = [
            'datetime' => $dateTimeObject,
            'time' => $dateObject,
            'string' => $dateTimeObject->format('Y-m-d H:i:s'),
            'timestamp' => $dateTimeObject->getTimestamp()
        ];
        if (class_exists(\DateTimeImmutable::class)) {
            $dateTimeImmutableObject = new \DateTimeImmutable('2012-05-03 12:41:11');
            $value['datetime_immutable'] = $dateTimeImmutableObject;
        }
        $value = (object) $value;

        $fieldMapping = ['datetime', 'time', 'string', 'timestamp'];
        $inputFieldFormat = [
            'datetime' => ['input_type' => 'datetime'],
            'time' => ['input_type' => 'datetime'],
            'string' => ['input_type' => 'string', 'datetime_format' => 'Y-m-d H:i:s'],
            'timestamp' => ['input_type' => 'timestamp']
        ];
        if (class_exists(\DateTimeImmutable::class)) {
            $fieldMapping[] = 'datetime_immutable';
            $inputFieldFormat['datetime_immutable'] = ['input_type' => 'datetime_interface'];
        }

        $column = $this->dataGridFactory->createColumn($this->getDataGridMock(), DateTime::class, 'datetime', [
            'datetime_format' => 'Y.d.m',
            'field_mapping' => $fieldMapping,
            'input_type' => 'array',
            'input_field_format' => $inputFieldFormat,
        ]);
        $cellView = $this->dataGridFactory->createCellView($column, $value);

        $expectedResult = [
            'datetime' => $dateTimeObject->format('Y.d.m'),
            'time' => $dateObject->format('Y.d.m'),
            'string' => $dateTimeObject->format('Y.d.m'),
            'timestamp' => $dateTimeObject->format('Y.d.m')
        ];
        if (class_exists(\DateTimeImmutable::class)) {
            $expectedResult['datetime_immutable'] = $dateTimeImmutableObject->format('Y.d.m');
        }

        $this->assertSame($expectedResult, $cellView->getValue());
    }

    private function getDataGridMock(): DataGridInterface
    {
        return $this->createMock(DataGridInterface::class);
    }
}
