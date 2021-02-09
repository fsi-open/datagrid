<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Tests\Extension\Core;

use FSi\Component\DataGrid\Extension\Core\ColumnType\Action;
use FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension\DefaultColumnOptionsExtension;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

class ActionTest extends TestCase
{
    /**
     * @var Action
     */
    private $column;

    protected function setUp(): void
    {
        $column = new Action();
        $column->setName('action');
        $column->initOptions();

        $extension = new DefaultColumnOptionsExtension();
        $extension->initOptions($column);

        $this->column = $column;
    }

    public function testFilterValueEmptyActionsOptionType()
    {
        $this->expectException(InvalidOptionsException::class);
        $this->expectExceptionMessage('The option "actions" with value "boo" is expected to be of type "array", but is of type "string".');
        $this->column->setOption('actions', 'boo');
        $this->column->filterValue([]);
    }

    public function testFilterValueInvalidActionInActionsOption()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->column->setOption('actions', ['edit' => 'asasdas']);
        $this->column->filterValue([]);
    }

    public function testFilterValueRequiredActionInActionsOption()
    {
        $this->column->setOption('actions', [
            'edit' => [
                'uri_scheme' => '/test/%s',
            ]
        ]);

        $this->assertSame(
            [
                'edit' => [
                    'url' => '/test/bar',
                    'field_mapping_values' => [
                        'foo' => 'bar'
                    ]
                ]
            ],
            $this->column->filterValue([
                'foo' => 'bar'
            ])
        );
    }

    public function testFilterValueAvailableActionInActionsOption()
    {
        $this->column->setOption('actions', [
            'edit' => [
                'uri_scheme' => '/test/%s',
                'domain' => 'fsi.pl',
                'protocol' => 'https://',
                'redirect_uri' => 'http://onet.pl/'
            ]
        ]);

        $this->assertSame(
            [
                'edit' => [
                    'url' => 'https://fsi.pl/test/bar?redirect_uri=' . urlencode('http://onet.pl/'),
                    'field_mapping_values' => [
                        'foo' => 'bar'
                    ]
                ]
            ],
            $this->column->filterValue([
                'foo' => 'bar'
            ])
        );
    }
}
