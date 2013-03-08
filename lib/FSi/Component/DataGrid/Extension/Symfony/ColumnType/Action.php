<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Extension\Symfony\ColumnType;

use FSi\Component\DataGrid\Column\ColumnAbstractType;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\Column\ColumnAbstractTypeExtension;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Action extends ColumnAbstractType
{
    /**
     * Symfony Router to generate urls.
     *
     * @var Symfony\Component\Routing\Router;
     */
    protected $router;

    /**
     * Service container used to access current request.
     *
     * @var Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * Default values for action options if not passed in column configuration.
     *
     * @var array
     */
    protected $actionOptionsDefault = array(
        'absolute' => false,
        'redirect_uri' => true,
    );

    /**
     * Available action options
     *
     * @var array
     */
    protected $actionOptionsAvailable = array(
        'parameters',
        'parameters_values',
        'anchor',
        'route_name',
        'absolute',
        'redirect_uri',
    );

    /**
     * Options required in action.
     *
     * @var array
     */
    protected $actionOptionsRequired = array(
        'anchor',
        'route_name',
    );

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->router = $container->get('router');
    }

    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        return 'action';
    }

    /**
     * {@inheritDoc}
     */
    public function filterValue($value)
    {
        $this->validateOptions();

        $return = array();
        $actions = $this->getOption('actions');

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

            if ($options['redirect_uri'] !== false) {
                if (is_string($options['redirect_uri'])) {
                    $parameters['redirect_uri'] = $options['redirect_uri'];
                }

                if ($options['redirect_uri'] === true) {
                    $parameters['redirect_uri'] = $this->container->get('request')->getUri();
                }
            }

            $url = $this->router->generate($options['route_name'], $parameters, $options['absolute']);
            $return[$name]['url'] = $url;
        }

        return $return;
    }

    /**
     * {@inheritDoc}
     */
    public function initOptions()
    {
        $this->getOptionsResolver()->setDefaults(array(
            'actions' => array(),
        ));
    }

    /**
     * Validate options for each action.
     *
     * @throws \InvalidArgumentException
     */
    private function validateOptions()
    {
        $actions = $this->getOption('actions');
        if (!is_array($actions)) {
            throw new \InvalidArgumentException('Option "actions" must be an array.');
        }

        if (!count($actions)) {
            throw new \InvalidArgumentException('Option actions can\'t be empty.');
        }

        foreach ($actions as $actionName => &$options) {
            if (!is_array($options)) {
                throw new \InvalidArgumentException(sprintf('Options for action "%s" must be an array.', $actionName));
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

                $mappingFields = $this->getOption('mapping_fields');

                foreach ($options['parameters'] as $mappingField => $routerParameter) {
                    if (!in_array($mappingField, $mappingFields, true)) {
                        throw new \InvalidArgumentException(sprintf('Unknown mapping_field "%s". Maybe you should consider using option "parameters_values"?.', $mappingField));
                    }
                }
            }
        }

        $this->setOption('actions', $actions);
    }
}
