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
use Symfony\Component\OptionsResolver\OptionsResolver;

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
     * @var OptionsResolver
     */
    protected $actionOptionsResolver;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->router = $container->get('router');
        $this->actionOptionsResolver = new OptionsResolver();
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
        //$this->validateOptions();

        $return = array();
        $actions = $this->getOption('actions');

        foreach ($actions as $name => $options) {
            $options = $this->actionOptionsResolver->resolve((array) $options);

            $return[$name] = array();

            $parameters = array();
            if (isset($options['parameters_field_mapping'])) {
                foreach ($options['parameters_field_mapping'] as $mappingField => $parameterName) {
                    $parameters[$parameterName] = $value[$mappingField];
                }
            }

            if (isset($options['additional_parameters'])) {
                foreach ($options['additional_parameters'] as $parameterValueName => $parameterValue) {
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
            $return[$name]['field_mapping_values'] = $value;
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

        $this->getOptionsResolver()->setAllowedTypes(array(
            'actions' => 'array',
        ));

        $this->actionOptionsResolver->setDefaults(array(
            'redirect_uri' => true,
            'absolute' => false,
            'parameters_field_mapping' => array(),
            'additional_parameters' => array(),
        ));

        $this->actionOptionsResolver->setRequired(array(
            'route_name'
        ));
    }
}
