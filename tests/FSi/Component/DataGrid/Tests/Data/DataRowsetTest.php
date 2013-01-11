<?php
/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Tests\Data;

use FSi\Component\DataGrid\Tests\Fixtures\Entity;
use FSi\Component\DataGrid\Data\DataRowset;

class DataRowsetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException InvalidArgumentException
     */
    public function testCreateWithInvalidData()
    {
        $rowset = new DataRowset('Invalid Data');
    }

    public function testCreateRowset()
    {
        $data = array(
            'e1' => new Entity('entity1'),
            'e2' => new Entity('entity2')
        );

        $rowset = new DataRowset($data);

        foreach ($rowset as $index => $row) {
            $this->assertSame($data[$index], $row);
        }

        $this->assertSame(2, $rowset->count());
    }
}