<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Tests\Extension\Gedmo;

use FSi\Component\DataGrid\Extension\Gedmo\GedmoDoctrineExtension;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;

class GedmoDoctrineExtensionTest extends TestCase
{
    public function testLoadedTypes(): void
    {
        $registry = $this->createMock(ManagerRegistry::class);
        $extension = new GedmoDoctrineExtension($registry);

        self::assertTrue($extension->hasColumnType('gedmo_tree'));
        self::assertFalse($extension->hasColumnType('foo'));
    }
}
