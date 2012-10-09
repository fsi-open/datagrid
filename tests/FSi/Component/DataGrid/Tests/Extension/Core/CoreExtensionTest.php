<?php
/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Tests\Extension\Core;

use FSi\Component\DataGrid\Extension\Core\CoreExtension;
use FSi\Component\DataGrid\Extension\Core\ColumnType;

class CoreExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testLoadedTypes()
    {
        $extension = new CoreExtension();
        $this->assertTrue($extension->hasColumnType('text'));
        $this->assertTrue($extension->hasColumnType('number'));
        $this->assertTrue($extension->hasColumnType('datetime'));
        $this->assertTrue($extension->hasColumnType('action'));
        $this->assertTrue($extension->hasColumnType('money'));

        $this->assertFalse($extension->hasColumnType('foo'));
    }

    public function testLoadedExtensions()
    {
        $extension = new CoreExtension();
        $this->assertTrue($extension->hasColumnTypeExtensions('text'));
        $this->assertTrue($extension->hasColumnTypeExtensions('text'));
        $this->assertTrue($extension->hasColumnTypeExtensions('number'));
        $this->assertTrue($extension->hasColumnTypeExtensions('datetime'));
        $this->assertTrue($extension->hasColumnTypeExtensions('action'));
        $this->assertTrue($extension->hasColumnTypeExtensions('money'));
        $this->assertTrue($extension->hasColumnTypeExtensions('gedmo.tree'));
        $this->assertTrue($extension->hasColumnTypeExtensions('entity'));
    }
}