<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Extension\Symfony\ColumnTypeExtension;

use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\Column\ColumnAbstractTypeExtension;
use Symfony\Component\Routing\RouterInterface;

class ActionColumnExtension extends ColumnAbstractTypeExtension
{
    /**
     * Symfony Router to generate urls.
     *
     * @var Symfony\Component\Routing\Router;
     */
    protected $router;

    /**
     * Default values for action options if not passed in column configuration.
     *
     * @var array
     */
    protected $actionOptionsDefault = array(
        'absolute' => false
    );

    /**
     * Available action options
     *
     * @var unknown_type
     */
    protected $actionOptionsAvailable = array(
        'parameters',
        'parameters_values',
        'anchor',
        'route_name',
        'absolute',
    );

    /**
     * Options required in action.
     *
     * @var unknown_type
     */
    protected $actionOptionsRequired = array(
        'anchor',
        'route_name'
    );

    /**
     * @param Router $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function filterValue(ColumnTypeInterface $column, $value)
    {
        $this->validateOptions($column);

        $return = array();
        $actions = $column->getOption('actions');

        foreach ($actions as $name => $options) {
            $return[$name] = array(
                'name' => $name,
                'anchor' => $options['anchor'],
            );

            $parameters = array();
            if (isset($options['parameters'])) {
                foreach ($options['parameters'] as $mappingField => $parameterName) {
                    $parameters[$parameterName] = $value[$mappingField];
                }
            }

            if (isset($options['parameters_values'])) {
                foreach ($options['parameters_values'] as $parameterValueName => $parameterValue) {
                    $parameters[$parameterValueName] = $parameterValue;
                }
            }

            $url = $this->router->generate($options['route_name'], $parameters, $options['absolute']);

            $return[$name]['url'] = $url;
        }

        return $return;
    }

    public function getExtendedColumnTypes()
    {
        return array('action');
    }

    public function getRequiredOptions(ColumnTypeInterface $column)
    {
        return array('actions');
    }

    public function getAvailableOptions(ColumnTypeInterface $column)
    {
        return array('actions');
    }

    private function validateOptions(ColumnTypeInterface $column)
    {
        $actions = $column->getOption('actions');
        if (!is_array($actions)) {
            throw new \InvalidArgumentException('Option "actions" must be an array.');
        }

        if (!count($actions)) {
            throw new \InvalidArgumentException('Option actions can\'t be empty.');
        }

        foreach ($actions as $actionName => &$options) {
            if (!is_array($options)) {
                throw new \InvalidArgumentException(sprinf('Options for action "%s" must be an array.', $actionName));
            }

            foreach ($options as $optionName => $value) {
                if (!in_array($optionName, $this->actionOptionsAvailable)) {
                    throw new \InvalidArgumentException(sprintf('Unknown option "%s" in action "%s".', $optionName, $actionName));
                }
            }

            foreach ($this->actionOptionsRequired as $optionName) {
                if (!array_key_exists($optionName, $options)) {
                    throw new \InvalidArgumentException(sprintf('Action "%s" require option "%s".', $actionName, $optionName));
                }
            }

            foreach ($this->actionOptionsDefault as $optionName => $value) {
                if (!array_key_exists($optionName, $options)) {
                    $options[$optionName] = $value;
                }
            }

            if (isset($options['parameters_values'])) {
                if (!is_array($options['parameters_values'])) {
                    throw new \InvalidArgumentException(sprintf('Action "%s" require option "parameters_values" as array.', $actionName, $optionName));
                }
            }

            if (isset($options['parameters'])) {
                if (!is_array($options['parameters'])) {
                    throw new \InvalidArgumentException(sprintf('Action "%s" require option "parameters" as array.', $actionName, $optionName));
                }

                $mappingFields = $column->getOption('mapping_fields');

                foreach ($options['parameters'] as $mappingField => $routerParameter) {
                    if (!in_array($mappingField, $mappingFields, true)) {
                        throw new \InvalidArgumentException(sprintf('Unknown mapping_field "%s". Maybe you should consider using option "parameters_values"?.', $mappingField));
                    }
                }
            }
        }

        $column->setOption('actions', $actions);
    }
}