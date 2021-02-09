<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Tests\Data;

use FSi\Component\DataGrid\Tests\Fixtures\Entity;
use FSi\Component\DataGrid\Data\DataRowset;
use PHPUnit\Framework\TestCase;
use TypeError;

class DataRowsetTest extends TestCase
{
    public function testCreateWithInvalidData(): void
    {
        $this->expectException(TypeError::class);

        new DataRowset('Invalid Data');
    }

    public function testCreateRowset(): void
    {
        $data = [
            'e1' => new Entity('entity1'),
            'e2' => new Entity('entity2')
        ];

        $rowset = new DataRowset($data);

        foreach ($rowset as $index => $row) {
            self::assertSame($data[$index], $row);
        }

        self::assertSame(2, $rowset->count());
    }
}
