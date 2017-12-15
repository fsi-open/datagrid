<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Tests\Extension\Core\ColumnType;

use FSi\Component\DataGrid\Extension\Core\ColumnType\Text;
use PHPUnit\Framework\TestCase;

class TextTest extends TestCase
{
    public function testTrimOption()
    {
        $column = new Text();
        $column->initOptions();
        $column->setOption('trim', true);

        $value = [
            ' VALUE ',
        ];

        $this->assertSame(
            ['VALUE'],
            $column->filterValue($value)
        );
    }
}
