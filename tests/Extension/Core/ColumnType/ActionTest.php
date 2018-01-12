<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Tests\Extension\Core;

use FSi\Component\DataGrid\DataGridFactory;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataGrid\Extension\Core\ColumnType\Action;
use FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension\DefaultColumnOptionsExtension;
use FSi\Component\DataGrid\Tests\Fixtures\SimpleDataGridExtension;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

class ActionTest extends TestCase
{
    /**
     * @var DataGridFactoryInterface
     */
    private $dataGridFactory;

    public function setUp()
    {
        $this->dataGridFactory = new DataGridFactory(
            [new SimpleDataGridExtension(new DefaultColumnOptionsExtension(), new Action())]
        );
    }

    public function testEmptyActionsOptionType()
    {
        $this->expectException(InvalidOptionsException::class);
        $this->dataGridFactory->createColumn(
            $this->getDataGridMock(),
            Action::class,
            'action',
            ['actions' => 'boo']
        );
    }

    public function testInvalidActionInActionsOption()
    {
        $this->expectException(InvalidArgumentException::class);
        $column = $this->dataGridFactory->createColumn($this->getDataGridMock(), Action::class, 'action', [
            'actions' => [
                'edit' => 'asasdas'
            ],
            'field_mapping' => ['foo']
        ]);
        $this->dataGridFactory->createCellView($column, 0, (object) ['foo' => 'bar']);
    }

    public function testRequiredActionInActionsOption()
    {
        $column = $this->dataGridFactory->createColumn($this->getDataGridMock(), Action::class, 'action', [
            'actions' => [
                'edit' => [
                    'uri_scheme' => '/test/%s',
                ]
            ],
            'field_mapping' => ['foo'],
        ]);
        $cellView = $this->dataGridFactory->createCellView($column, 0, (object) ['foo' => 'bar']);

        $this->assertSame([
            'edit' => [
                'url' => '/test/bar',
                'field_mapping_values' => [
                    'foo' => 'bar'
                ]
            ]
        ], $cellView->getValue());
    }

    public function testAvailableActionInActionsOption()
    {
        $column = $this->dataGridFactory->createColumn($this->getDataGridMock(), Action::class,  'action', [
            'actions' => [
                'edit' => [
                    'uri_scheme' => '/test/%s',
                    'domain' => 'fsi.pl',
                    'protocol' => 'https://',
                    'redirect_uri' => 'http://onet.pl/'
                ]
            ],
            'field_mapping' => ['foo']
        ]);
        $cellView = $this->dataGridFactory->createCellView($column, 0, (object) ['foo' => 'bar']);

        $this->assertSame([
            'edit' => [
                'url' => 'https://fsi.pl/test/bar?redirect_uri=' . urlencode('http://onet.pl/'),
                'field_mapping_values' => [
                    'foo' => 'bar'
                ]
            ]
        ], $cellView->getValue());
    }

    private function getDataGridMock(): DataGridInterface
    {
        return $this->createMock(DataGridInterface::class);
    }
}
