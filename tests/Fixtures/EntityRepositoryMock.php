<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Tests\Fixtures;

use Gedmo\Tree\RepositoryInterface;
use RuntimeException;

class EntityRepositoryMock implements RepositoryInterface
{
    public function getRootNodes($sortByField = null, $direction = 'asc')
    {
        throw new RuntimeException('Method not implemented');
    }

    public function getNodesHierarchy($node = null, $direct = false, array $options = [], $includeNode = false)
    {
        throw new RuntimeException('Method not implemented');
    }

    public function getChildren(
        $node = null,
        $direct = false,
        $sortByField = null,
        $direction = 'ASC',
        $includeNode = false
    ) {
        throw new RuntimeException('Method not implemented');
    }

    public function childCount($node = null, $direct = false)
    {
        return 2;
    }

    public function childrenHierarchy($node = null, $direct = false, array $options = [], $includeNode = false)
    {
        throw new RuntimeException('Method not implemented');
    }

    public function buildTree(array $nodes, array $options = [])
    {
        throw new RuntimeException('Method not implemented');
    }

    public function buildTreeArray(array $nodes)
    {
        throw new RuntimeException('Method not implemented');
    }

    public function setChildrenIndex($childrenIndex)
    {
        throw new RuntimeException('Method not implemented');
    }

    public function getChildrenIndex()
    {
        throw new RuntimeException('Method not implemented');
    }
}
