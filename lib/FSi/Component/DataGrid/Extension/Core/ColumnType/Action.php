<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Extension\Core\ColumnType;

use FSi\Component\DataGrid\Column\ColumnAbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Action extends ColumnAbstractType
{
    /**
     * @var array
     */
    protected $actionOptionsDefault = array(
        'protocol' => 'http://',
    );

    /**
     * @var array
     */
    protected $actionOptionsAvailable = array(
        'uri_scheme',
        'anchor',
        'protocol',
        'domain',
        'name',
        'redirect_uri',
    );

    /**
     * @var array
     */
    protected $actionOptionsRequired = array(
        'uri_scheme',
        'anchor',
    );

    /**
     * @var \Symfony\Component\OptionsResolver\OptionsResolver
     */
    protected $actionOptionsResolver;

    public function __construct()
    {
        $this->actionOptionsResolver = new OptionsResolver();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'action';
    }

    /**
     * {@inheritdoc}
     */
    public function filterValue($value)
    {
        $return = array();
        $actions = $this->getOption('actions');

        foreach ($actions as $name => $options) {
            $options = $this->actionOptionsResolver->resolve((array) $options);
            $return[$name] = array();

            $url = (isset($options['protocol'], $options['domain'])) ? $options['protocol'] . $options['domain'] : '';
            $url .= vsprintf ($options['uri_scheme'], $value);

            if (isset($options['redirect_uri']) && is_string($options['redirect_uri'])) {
                if (strpos($url, '?') !== false) {
                    $url .= '&redirect_uri=' . urlencode($options['redirect_uri']);
                } else {
                    $url .= '?redirect_uri=' . urlencode($options['redirect_uri']);
                }
            }

            $return[$name]['url'] = $url;
            $return[$name]['field_mapping_values'] = $value;
        }

        return $return;
    }

    /**
     * {@inheritdoc}
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
            'redirect_uri' => null,
            'domain' => null,
            'protocol' => 'http://'
        ));

        $this->actionOptionsResolver->setRequired(array(
            'uri_scheme'
        ));

        $this->actionOptionsResolver->setAllowedTypes(array(
            'redirect_uri' => array('string', 'null'),
            'uri_scheme' => 'string',
        ));

        $this->actionOptionsResolver->addAllowedValues(array(
            'protocol' => array(
                'http://',
                'https://'
            )
        ));
    }
}
