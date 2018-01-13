<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Tests\Extension\Doctrine\ColumnType;

use FSi\Component\DataGrid\DataGridFactory;
use FSi\Component\DataGrid\Tests\Fixtures\Entity as Fixture;
use FSi\Component\DataGrid\Extension\Doctrine\ColumnType\Entity;
use FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension\DefaultColumnOptionsExtension;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataGrid\Tests\Fixtures\SimpleDataGridExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;

class EntityTypeTest extends TestCase
{
    public function testGetValue()
    {
        $dataGridFactory = new DataGridFactory(
            new EventDispatcher(),
            [new SimpleDataGridExtension(new DefaultColumnOptionsExtension(), new Entity())]
        );

        $dataGrid = $this->createMock(DataGridInterface::class);
        $column = $dataGridFactory->createColumn($dataGrid, Entity::class, 'foo', ['relation_field' => 'author']);

        $object = new Fixture('object');
        $object->setAuthor((object) ['foo' => 'bar']);

        $cellView = $dataGridFactory->createCellView($column, 0, $object);
        $this->assertSame([['foo' => 'bar']], $cellView->getValue());
        $this->assertSame(0, $cellView->getIndex());
    }
}
