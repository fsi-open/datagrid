<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Extension\Gedmo\ColumnType;

use Doctrine\Common\Persistence\ManagerRegistry;
use FSi\Component\DataGrid\Column\CellViewInterface;
use FSi\Component\DataGrid\Column\ColumnAbstractType;
use FSi\Component\DataGrid\Exception\DataGridColumnException;
use FSi\Component\DataIndexer\DoctrineDataIndexer;
use Gedmo\Tree\Strategy;
use Gedmo\Tree\TreeListener;
use Symfony\Component\PropertyAccess\PropertyAccess;

class Tree extends ColumnAbstractType
{
    /**
     * @var ManagerRegistry
     */
    protected $registry;

    /**
     * @var Strategy
     */
    protected $strategy;

    protected $allowedStrategies = array(
        'nested'
    );

    protected $viewAttributes = array();

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        return 'gedmo.tree';
    }

    /**
     * {@inheritDoc}
     */
    public function getValue($object)
    {
        if (!is_object($object)) {
            throw new \InvalidArgumentException('Column "gedmo.tree" must read value from object.');
        }

        $value = parent::getValue($object);
        $em = $this->registry->getManager($this->getOption('em'));

        // Check if tree listener is registred.
        $treeListener = null;

        foreach ($em->getEventManager()->getListeners() as $listeners) {
            foreach ($listeners as $listener) {
                if ($listener instanceof TreeListener) {
                    $treeListener = $listener;
                    break;
                }
            }
            if ($treeListener) {
                break;
            }
        }

        if (is_null($treeListener)) {
            throw new DataGridColumnException('Gedmo Tree listener was not found on your entity manager, it must be hooked into the event manager');
        }

        // Get Tree strategy
        try {
            $this->strategy = $treeListener->getStrategy($em, get_class($object));
        } catch (\Exception $e) {
            throw new DataGridColumnException(
                sprintf('"%s" is not implementing gedmo tree strategy. Maybe you should consider using a different column type?', get_class($object))
            );
        }

        if (!in_array($this->strategy->getName(), $this->allowedStrategies)) {
            throw new DataGridColumnException(
                sprintf('Strategy "%s" is not supported by "%s" column.', $this->strategy->getName(), $this->getId())
            );
        }

        $config = $treeListener->getConfiguration($em, get_class($object));

        $doctrineDataIndexer = new DoctrineDataIndexer($this->registry, get_class($object));
        $propertyAccessor = PropertyAccess::getPropertyAccessor();

        $id = $doctrineDataIndexer->getIndex($object);
        $left = $propertyAccessor->getValue($object, $config['left']);
        $right = $propertyAccessor->getValue($object, $config['right']);
        $root = isset($config['root']) ? $propertyAccessor->getValue($object, $config['root']) : null;
        $level = (isset($config['level'])) ? $propertyAccessor->getValue($object, $config['level']) : null;
        $parent = $propertyAccessor->getValue($object, $config['parent']);
        $parentId = null;
        if (isset($parent)) {
            $parentId = $doctrineDataIndexer->getIndex($parent);
        }

        $this->viewAttributes = array(
            'id' => $id,
            'root' => $root,
            'parent' => $parentId,
            'left' => $left,
            'right' => $right,
            'level' => $level,
            'children' => $em->getRepository(get_class($object))->childCount($object),
        );

        return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function filterValue($value)
    {
        return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function buildCellView(CellViewInterface $view)
    {
        foreach ($this->getViewAttributes() as $attrName => $attrValue) {
            $view->setAttribute($attrName, $attrValue);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getViewAttributes()
    {
        return $this->viewAttributes;
    }

    /**
     * {@inheritDoc}
     */
    public function initOptions()
    {
        $this->getOptionsResolver()->setDefaults(array(
            'em' => null,
        ));
    }
}
