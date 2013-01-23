<?php
/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Tests\Extension\Core\ColumnType;

use FSi\Component\DataGrid\Extension\Core\ColumnType\DateTime;

class DateTimeTest extends \PHPUnit_Framework_TestCase
{
    public function testBasicFilterValue()
    {
        $dateTimeObject = new \DateTime('2012-05-03 12:41:11');

        $value = array(
            'datetime' => $dateTimeObject
        );

        $column = new DateTime();
        $column->setName('datetime');
        $column->setOption('mapping_fields', array('datetime'));

        $this->assertSame(
            $column->filterValue($value),
            array(
                'datetime' => $dateTimeObject->format('Y-m-d H:i:s')
            )
        );
    }

    public function testFormatOption()
    {
        $dateTimeObject = new \DateTime('2012-05-03 12:41:11');

        $value = array(
            'datetime' => $dateTimeObject
        );

        $column = new DateTime();
        $column->setName('datetime');
        $column->setOption('mapping_fields', array('datetime'));
        $column->setOption('datetime_format', 'Y.d.m');

        $this->assertSame(
            $column->filterValue($value),
            array(
                'datetime' => $dateTimeObject->format('Y.d.m')
            )
        );
    }

    public function testMappingFieldsOptionInputTimestamp()
    {
        $dateTimeObject = new \DateTime('2012-05-03 12:41:11');

        $brokenValue = array(
            'datetime' => $dateTimeObject
        );
        $value = array(
            'datetime' => $dateTimeObject->getTimestamp()
        );

        $column = new DateTime();
        $column->setName('datetime');
        $column->setOption('input', 'timestamp');

        $this->setExpectedException('InvalidArgumentException');
        $column->filterValue($brokenValue);

        $column->filterValue($value);
        $this->assertSame(
            $column->filterValue($value),
            array(
                'datetime' => $dateTimeObject->format('Y-m-d H:i:s')
            )
        );
    }

    public function testMappingFieldsOptionInputStringMissingMappingFieldsFormat()
    {
        $dateTimeObject = new \DateTime('2012-05-03 12:41:11');
        $value = array(
            'datetime' => $dateTimeObject->format('Y-m-d H:i:s')
        );

        $column = new DateTime();
        $column->setName('datetime');
        $column->setOption('input', 'string');

        $this->setExpectedException('FSi\Component\DataGrid\Exception\DataGridColumnException');
        $column->filterValue($value);
    }

    public function testMappingFieldsOptionInputString()
    {
        $dateTimeObject = new \DateTime('2012-05-03 12:41:11');

        $brokenValue = array(
            'datetime' => $dateTimeObject
        );

        $value = array(
            'datetime' => $dateTimeObject->format('Y-m-d H:i:s')
        );

        $column = new DateTime();
        $column->setName('datetime');
        $column->setOption('input', 'string');

        $column->setOption('mapping_fields_format', 'Y-m-d H:i:s');

        $this->assertSame(
            $column->filterValue($value),
            array(
                'datetime' => $dateTimeObject->format('Y-m-d H:i:s')
            )
        );

        $this->setExpectedException('FSi\Component\DataGrid\Exception\DataGridColumnException');
        $column->filterValue($brokenValue);
    }

    public function testMappingFieldsOptionInputArrayMissingMappingFieldsFormat()
    {
        $dateTimeObject = new \DateTime('2012-05-03 12:41:11');
        $dateObject = new \DateTime('2012-05-03');
        $value = array(
            'datetime' => $dateTimeObject->format('Y-m-d H:i:s'),
            'time' => $dateObject->format('Y-m-d H:i:s')
        );

        $column = new DateTime();
        $column->setName('datetime');
        $column->setOption('input', 'array');

        $this->setExpectedException('FSi\Component\DataGrid\Exception\DataGridColumnException');
        $column->filterValue($value);
    }

    public function testMappingFieldsOptionInputArrayWrongMappingFieldsFormat()
    {
        $dateTimeObject = new \DateTime('2012-05-03 12:41:11');
        $dateObject = new \DateTime('2012-05-03');
        $value = array(
            'datetime' => $dateTimeObject->format('Y-m-d H:i:s'),
            'time' => $dateObject->format('Y-m-d H:i:s')
        );

        $column = new DateTime();
        $column->setName('datetime');
        $column->setOption('input', 'array');
        $column->setOption('mapping_fields_format', array(
            'datetime' => 'string',
            'time' => 'string'
        ));

        $this->setExpectedException('FSi\Component\DataGrid\Exception\DataGridColumnException');
        $column->filterValue($value);
    }

    public function testMappingFieldsOptionInputArray()
    {
        $dateTimeObject = new \DateTime('2012-05-03 12:41:11');
        $dateObject = new \DateTime('2012-05-03');
        $value = array(
            'datetime' => $dateTimeObject,
            'time' => $dateObject,
            'string' => $dateTimeObject->format('Y-m-d H:i:s'),
            'timestamp' => $dateTimeObject->getTimestamp()
        );

        $column = new DateTime();
        $column->setName('datetime');
        $column->setOption('input', 'array');
        $column->setOption('mapping_fields_format', array(
            'datetime' => array('input' => 'datetime'),
            'time' => array('input' => 'datetime'),
            'string' => array('input' => 'string', 'datetime_format' => 'Y-m-d H:i:s'),
            'timestamp' => array('input' => 'timestamp')
        ));

        $this->assertSame(
            $column->filterValue($value),
            array(
                'datetime' => $dateTimeObject->format('Y-m-d H:i:s'),
                'time' => $dateObject->format('Y-m-d 00:00:00'),
                'string' => $dateTimeObject->format('Y-m-d H:i:s'),
                'timestamp' => date('Y-m-d H:i:s', $dateTimeObject->getTimestamp()),
            )
        );
    }

    public function testMappingFieldsOptionInputArrayWithFormat()
    {
        $dateTimeObject = new \DateTime('2012-05-03 12:41:11');
        $dateObject = new \DateTime('2012-05-03');
        $value = array(
            'datetime' => $dateTimeObject,
            'time' => $dateObject,
            'string' => $dateTimeObject->format('Y-m-d H:i:s'),
            'timestamp' => $dateTimeObject->getTimestamp()
        );

        $column = new DateTime();
        $column->setName('datetime');
        $column->setOption('input', 'array');
        $column->setOption('datetime_format', 'Y.d.m');
        $column->setOption('mapping_fields_format', array(
            'datetime' => array('input' => 'datetime'),
            'time' => array('input' => 'datetime'),
            'string' => array('input' => 'string', 'datetime_format' => 'Y-m-d H:i:s'),
            'timestamp' => array('input' => 'timestamp')
        ));

        $this->assertSame(
            $column->filterValue($value),
            array(
                'datetime' => $dateTimeObject->format('Y.d.m'),
                'time' => $dateObject->format('Y.d.m'),
                'string' => $dateTimeObject->format('Y.d.m'),
                'timestamp' => $dateTimeObject->format('Y.d.m')
            )
        );
    }
}
