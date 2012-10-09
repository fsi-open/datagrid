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

class DateTimeTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testBasicFilterValue()
    {
        $value = array(
            'datetime' => new \DateTime('2012-05-03 12:41:11')
        );
        $column = new DateTime();
        $column->setName('datetime');
        $column->setOption('mapping_fields', array('datetime'));

        $this->assertSame(
            $column->filterValue($value),
            array(
                'datetime' => '2012-05-03 12:41:11'
            )
        );
    }

    public function testFormatOption()
    {
        $value = array(
            'datetime' => new \DateTime('2012-05-03 12:41:11')
        );

        $column = new DateTime();
        $column->setName('datetime');
        $column->setOption('mapping_fields', array('datetime'));
        $column->setOption('format', 'Y.d.m');

        $this->assertSame(
            $column->filterValue($value),
            array(
                'datetime' => '2012.03.05'
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
                'datetime' => '2012-05-03 12:41:11'
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
                'datetime' => '2012-05-03 12:41:11'
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
            'string' => $dateTimeObject->format('Y-m-d H:i:s'),
            'time' => $dateObject,
            'timestamp' => $dateTimeObject->getTimestamp()
        );

        $column = new DateTime();
        $column->setName('datetime');
        $column->setOption('input', 'array');
        $column->setOption('mapping_fields_format', array(
            'datetime' => array('input' => 'datetime'),
            'time' => array('input' => 'datetime'),
            'string' => array('input' => 'string', 'format' => 'Y-m-d H:i:s'),
            'timestamp' => array('input' => 'timestamp')
        ));

        $this->assertSame(
            $column->filterValue($value),
            array(
                'datetime' => '2012-05-03 12:41:11',
                'time' => '2012-05-03 00:00:00',
                'string' => '2012-05-03 12:41:11',
                'timestamp' => '2012-05-03 10:41:11'
            )
        );
    }

    public function testMappingFieldsOptionInputArrayWithFormat()
    {
        $dateTimeObject = new \DateTime('2012-05-03 12:41:11');
        $dateObject = new \DateTime('2012-05-03');
        $value = array(
            'datetime' => $dateTimeObject,
            'string' => $dateTimeObject->format('Y-m-d H:i:s'),
            'time' => $dateObject,
            'timestamp' => $dateTimeObject->getTimestamp()
        );

        $column = new DateTime();
        $column->setName('datetime');
        $column->setOption('input', 'array');
        $column->setOption('format', 'Y.d.m');
        $column->setOption('mapping_fields_format', array(
            'datetime' => array('input' => 'datetime'),
            'time' => array('input' => 'datetime'),
            'string' => array('input' => 'string', 'format' => 'Y-m-d H:i:s'),
            'timestamp' => array('input' => 'timestamp')
        ));

        $this->assertSame(
            $column->filterValue($value),
            array(
                'datetime' => '2012.03.05',
                'time' => '2012.03.05',
                'string' => '2012.03.05',
                'timestamp' => '2012.03.05'
            )
        );
    }
}

