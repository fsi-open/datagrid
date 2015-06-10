<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Tests\Fixtures;

use Gedmo\Tree\RepositoryInterface;

class EntityRepositoryMock implements RepositoryInterface
{
    public function getRootNodes($sortByField = null, $direction = 'asc')
    {
    }

    public function getNodesHierarchy($node = null, $direct = false, array $options = array(), $includeNode = false)
    {
    }

    public function getChildren($node = null, $direct = false, $sortByField = null, $direction = 'ASC', $includeNode = false)
    {
    }

    public function childCount($node = null, $direct = false)
    {
        return 2;
    }

    public function childrenHierarchy($node = null, $direct = false, array $options = array(), $includeNode = false)
    {
    }

    public function buildTree(array $nodes, array $options = array())
    {
    }

    public function buildTreeArray(array $nodes)
    {
    }

    public function setChildrenIndex($childrenIndex)
    {
    }

    public function getChildrenIndex()
    {
    }
}
