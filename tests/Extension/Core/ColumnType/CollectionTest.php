<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Tests\Extension\Core\ColumnType;

use FSi\Component\DataGrid\Extension\Core\ColumnType\Collection;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    public function testFilterValue(): void
    {
        $column = new Collection();
        $column->initOptions();
        $column->setOption('collection_glue', ' ');
        $value = [
            ['foo', 'bar'],
            'test'
        ];

        self::assertSame(
            ['foo bar', 'test'],
            $column->filterValue($value)
        );
    }
}
