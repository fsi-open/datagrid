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
use FSi\Component\DataGrid\Extension\Core\ColumnType\Text;
use FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension\DefaultColumnOptionsExtension;
use FSi\Component\DataGrid\Tests\Fixtures\SimpleDataGridExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;

class TextTest extends TestCase
{
    public function testTrimOption()
    {
        $dataGridFactory = new DataGridFactory(
            new EventDispatcher(),
            [new SimpleDataGridExtension(new DefaultColumnOptionsExtension(), new Text())]
        );

        $column = $dataGridFactory->createColumn($this->getDataGridMock(), Text::class, 'text', ['trim' => true]);
        $cellView = $dataGridFactory->createCellView($column, 0, (object) ['text' => ' VALUE ']);

        $this->assertSame(['text' => 'VALUE'], $cellView->getValue());
    }

    private function getDataGridMock(): DataGridInterface
    {
        return $this->createMock(DataGridInterface::class);
    }
}
