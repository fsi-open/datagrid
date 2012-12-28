<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Extension\Symfony;

use FSi\Component\DataGrid\DataGridAbstractExtension;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DependencyInjectionExtension extends DataGridAbstractExtension
{
    protected $container;

    protected $columnServiceIds;

    protected $columnExtensionServiceIds;

    protected $gridSubscriberServiceIds;

    public function __construct(ContainerInterface $container, array $columnServiceIds,
            array $columnExtensionServiceIds, array $gridSubscriberServiceIds)
    {
        $this->container = $container;
        $this->columnServiceIds = $columnServiceIds;
        $this->columnExtensionServiceIds = $columnExtensionServiceIds;
        $this->gridSubscriberServiceIds = $gridSubscriberServiceIds;
    }

    public function hasColumnTypeExtensions($type)
    {
        foreach ($this->columnExtensionServiceIds as $alias => $extensionName) {
            $extension = $this->container->get($this->columnExtensionServiceIds[$alias]);
            $types = $extension->getExtendedColumnTypes();
            if (in_array($type, $types)) {
                return true;
            }
        }
        return false;
    }

    public function hasColumnType($type)
    {
        return isset($this->columnServiceIds[$type]);
    }

    public function getColumnType($type)
    {
        if (!isset($this->columnServiceIds[$type])) {
            throw new \InvalidArgumentException(sprintf('The column type "%s" is not registered with the service container.', $type));
        }

        $type = $this->container->get($this->columnServiceIds[$type]);

        return $type;
    }

    public function getColumnTypeExtensions($type)
    {
        $columnExtension = array();

        foreach ($this->columnExtensionServiceIds as $alias => $extensionName) {
            $extension = $this->container->get($this->columnExtensionServiceIds[$alias]);
            $types = $extension->getExtendedColumnTypes();
            if (in_array($type, $types)) {
                $columnExtension[] = $extension;;
            }
        }

        return $columnExtension;
    }

    public function loadSubscribers()
    {
        $subscribers = array();

        foreach ($this->gridSubscriberServiceIds as $alias => $subscriberName) {
            $subscriber = $this->container->get($this->gridSubscriberServiceIds[$alias]);
            $subscribers[] = $subscriber;
        }

        return $subscribers;
    }
}
