<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Extension\Gedmo\ColumnType;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use FSi\Component\DataGrid\Column\CellViewInterface;
use FSi\Component\DataGrid\Column\ColumnAbstractType;
use FSi\Component\DataGrid\Exception\DataGridColumnException;
use FSi\Component\DataIndexer\DoctrineDataIndexer;
use Gedmo\Tree\TreeListener;
use Symfony\Component\PropertyAccess\PropertyAccess;

class Tree extends ColumnAbstractType
{
    /**
     * @var Doctrine\Common\Persistence\ManagerRegistry
     */
    protected $registry;

    /**
     * @var Gedmo\Tree\Strategy
     */
    protected $strategy;

    /**
     * @var array
     */
    protected $allowedStrategies;

    /**
     * @var array
     */
    protected $viewAttributes;

    /**
     * @var array
     */
    protected $classStrategies;

    /**
     * @param Doctrine\Common\Persistence\ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
        $this->viewAttributes = array();
        $this->classStrategies = array();
        $this->allowedStrategies = array('nested');
    }

    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        return 'gedmo_tree';
    }

    /**
     * {@inheritDoc}
     */
    public function getValue($object)
    {
        if (!is_object($object)) {
            throw new \InvalidArgumentException('Column "gedmo_tree" must read value from object.');
        }

        $value = parent::getValue($object);
        $em = $this->registry->getManager($this->getOption('em'));

        // Check if tree listener is registred.
        $treeListener = $this->getTreeListener($em);
        if (is_null($treeListener)) {
            throw new DataGridColumnException('Gedmo TreeListener was not found in your entity manager.');
        }

        // Get Tree strategy.
        $this->strategy = $this->getClassStrategy($em, $treeListener, get_class($object));
        if (!isset($this->strategy )) {
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
        $left = isset($config['left']) ? $propertyAccessor->getValue($object, $config['left']) : null;
        $right = isset($config['right']) ? $propertyAccessor->getValue($object, $config['right']) : null;
        $root = isset($config['root']) ? $propertyAccessor->getValue($object, $config['root']) : null;
        $level = (isset($config['level'])) ? $propertyAccessor->getValue($object, $config['level']) : null;
        $parent = (isset($config['parent'])) ? $propertyAccessor->getValue($object, $config['parent']) : null;
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
    public function initOptions()
    {
        $this->getOptionsResolver()->setDefaults(array(
            'em' => null,
        ));
    }

    /**
     * @return array
     */
    public function getViewAttributes()
    {
        return $this->viewAttributes;
    }

    /**
     * @param Doctrine\Common\Persistence\ObjectManager $om
     * @param Gedmo\Tree\TreeListener $listener
     * @param string $class
     * @return string|null
     */
    private function getClassStrategy(ObjectManager $om, TreeListener $listener, $class)
    {
        if (array_key_exists($class, $this->classStrategies)) {
            return $this->classStrategies[$class];
        }

        $this->classStrategies[$class] = null;
        $classParents = array_merge(
            array($class),
            class_parents($class)
        );

        foreach ($classParents as $parent) {
            try {
                $this->classStrategies[$class] = $listener->getStrategy($om, $parent);
                break;
            } catch (\Exception $e) {
            }
        }

        return $this->classStrategies[$class];
    }

    /**
     * @param ObjectManager $om
     * @return Gedmo\Tree\TreeListener|null
     */
    private function getTreeListener(ObjectManager $om)
    {
        $treeListener = null;

        foreach ($om->getEventManager()->getListeners() as $listeners) {
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

        return $treeListener;
    }
}
