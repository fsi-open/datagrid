<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Extension\Gedmo\ColumnType;

use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use FSi\Component\DataGrid\Column\CellViewInterface;
use FSi\Component\DataGrid\Column\ColumnAbstractType;
use FSi\Component\DataGrid\Exception\DataGridColumnException;
use FSi\Component\DataIndexer\DoctrineDataIndexer;
use Gedmo\Tree\RepositoryInterface as TreeRepositoryInterface;
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
     * @var array
     */
    protected $allowedStrategies;

    /**
     * @var array
     */
    private $viewAttributes;

    /**
     * @var array
     */
    private $classStrategies;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
        $this->viewAttributes = [];
        $this->classStrategies = [];
        $this->allowedStrategies = ['nested'];
    }

    public function getId(): string
    {
        return 'gedmo_tree';
    }

    public function getValue($object)
    {
        if (!is_object($object)) {
            throw new \InvalidArgumentException('Column "gedmo_tree" must read value from object.');
        }

        $value = parent::getValue($object);
        $objectManager = $this->registry->getManager($this->getOption('em'));

        // Check if tree listener is registred.
        $treeListener = $this->getTreeListener($objectManager);

        // Get Tree strategy.
        $strategy = $this->getClassStrategy($objectManager, $treeListener, get_class($object));
        $this->validateStrategy($object, $strategy);

        $config = $treeListener->getConfiguration($objectManager, get_class($object));
        $doctrineDataIndexer = new DoctrineDataIndexer($this->registry, get_class($object));
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $this->viewAttributes = [
            'id' => $doctrineDataIndexer->getIndex($object),
            'root' => isset($config['root']) ? $propertyAccessor->getValue($object, $config['root']) : null,
            'left' => isset($config['left']) ? $propertyAccessor->getValue($object, $config['left']) : null,
            'right' => isset($config['right']) ? $propertyAccessor->getValue($object, $config['right']) : null,
            'level' => (isset($config['level'])) ? $propertyAccessor->getValue($object, $config['level']) : null,
            'children' => $this->getTreeRepository(get_class($object), $objectManager)->childCount($object),
        ];

        $parent = (isset($config['parent'])) ? $propertyAccessor->getValue($object, $config['parent']) : null;
        if (isset($parent)) {
            $this->viewAttributes['parent'] = $doctrineDataIndexer->getIndex($parent);
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function filterValue($value)
    {
        return $value;
    }

    public function buildCellView(CellViewInterface $view): void
    {
        foreach ($this->getViewAttributes() as $attrName => $attrValue) {
            $view->setAttribute($attrName, $attrValue);
        }
    }

    public function initOptions(): void
    {
        $this->getOptionsResolver()->setDefaults([
            'em' => null,
        ]);
    }

    private function getViewAttributes(): array
    {
        return $this->viewAttributes;
    }

    private function getClassStrategy(ObjectManager $om, TreeListener $listener, string $class): Strategy
    {
        if (array_key_exists($class, $this->classStrategies)) {
            return $this->classStrategies[$class];
        }

        $this->classStrategies[$class] = null;
        $classParents = array_merge(
            [$class],
            class_parents($class)
        );

        foreach ($classParents as $parent) {
            try {
                $this->classStrategies[$class] = $listener->getStrategy($om, $parent);
                break;
            } catch (\Exception $e) {
                // we don't like to throw exception because there might be a strategy for class parents
            }
        }

        return $this->classStrategies[$class];
    }

    /**
     * @param ObjectManager $om
     * @throws \FSi\Component\DataGrid\Exception\DataGridColumnException
     * @return TreeListener
     */
    private function getTreeListener(ObjectManager $om)
    {
        $treeListener = null;

        if ($om instanceof EntityManager) {
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
        }

        if (!isset($treeListener)) {
            throw new DataGridColumnException('Gedmo TreeListener was not found in your entity manager.');
        }

        return $treeListener;
    }

    /**
     * @param string $class
     * @param ObjectManager $em
     * @return TreeRepositoryInterface
     * @throws \RuntimeException
     */
    private function getTreeRepository($class, ObjectManager $em)
    {
        $repository = $em->getRepository($class);
        if (!$repository instanceof TreeRepositoryInterface) {
            throw new \RuntimeException(
                sprintf("%s must be an instance of Gedmo tree repository", get_class($repository))
            );
        }

        return $repository;
    }

    /**
     * @param mixed $object
     * @param mixed $strategy
     * @throws \FSi\Component\DataGrid\Exception\DataGridColumnException
     */
    private function validateStrategy($object, $strategy)
    {
        if (!isset($strategy) || !$strategy instanceof Strategy) {
            throw new DataGridColumnException(sprintf(
                '"%s" is not implementing Gedmo tree strategy. Consider using a different column type?',
                get_class($object)
            ));
        }

        if (!in_array($strategy->getName(), $this->allowedStrategies)) {
            throw new DataGridColumnException(
                sprintf('Strategy "%s" is not supported by "%s" column.', $strategy->getName(), $this->getId())
            );
        }
    }
}
