<?php
/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Tests\Extension\Symfony;

use FSi\Component\DataGrid\Extension\Symfony\FormExtension;

class FormExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testSymfonyFormExtension()
    {
        if (!class_exists('Symfony\Component\Form\FormFactory')) {
            $this->markTestSkipped('Symfony\Component\Form\FormFactoryis required for testSymfonyFormExtension');
        }

        $formFactory = $this->getMock('Symfony\Component\Form\FormFactoryInterface');
        $extension = new FormExtension($formFactory);

        $this->assertFalse($extension->hasColumnType('foo'));
        $this->assertTrue($extension->hasColumnTypeExtensions('text'));
    }
}