<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
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
        'protocole' => 'http://',
    );

    /**
     * @var array
     */
    protected $actionOptionsAvailable = array(
        'uri_scheme',
        'anchor',
        'protocole',
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
     * @var OptionsResolver
     */
    protected $actionOptionsResolver;

    public function __construct()
    {
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
        $return = array();
        $actions = $this->getOption('actions');

        foreach ($actions as $name => $options) {
            $options = $this->actionOptionsResolver->resolve((array) $options);
            $return[$name] = array();

            $url = (isset($options['protocole'], $options['domain'])) ? $options['protocole'] . $options['domain'] : '';
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
            'redirect_uri' => null,
            'domain' => null,
            'protocole' => 'http://'
        ));

        $this->actionOptionsResolver->setRequired(array(
            'uri_scheme'
        ));

        $this->actionOptionsResolver->setAllowedTypes(array(
            'redirect_uri' => array('string', 'null'),
            'uri_scheme' => 'string',
        ));

        $this->actionOptionsResolver->addAllowedValues(array(
            'protocole' => array(
                'http://',
                'https://'
            )
        ));
    }
}
