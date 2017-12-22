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
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataGrid\Extension\Core\ColumnType\Collection;
use FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension\DefaultColumnOptionsExtension;
use FSi\Component\DataGrid\Tests\Fixtures\SimpleDataGridExtension;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    public function test_filter_value()
    {
        $dataGridFactory = new DataGridFactory(
            [new SimpleDataGridExtension(new DefaultColumnOptionsExtension(), new Collection())]
        );

        $column = $dataGridFactory->createColumn($this->getDataGridMock(), Collection::class, 'col', [
            'collection_glue' => ', ',
            'field_mapping' => ['collection1', 'collection2'],
        ]);

        $cellView = $dataGridFactory->createCellView($column, (object) [
            'collection1' => ['foo', 'bar'],
            'collection2' => 'test',
        ]);

        $this->assertSame(
            [
                'collection1' => 'foo, bar',
                'collection2' => 'test'
            ],
            $cellView->getValue()
        );
    }

    private function getDataGridMock(): DataGridInterface
    {
        return $this->createMock(DataGridInterface::class);
    }
}
