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
use Symfony\Component\OptionsResolver\Options;

class Action extends ColumnAbstractType
{
    /**
     * @var array
     */
    protected $actionOptionsDefault = [
        'protocol' => 'http://',
        'protocole' => 'http://'
    ];

    /**
     * @var array
     */
    protected $actionOptionsAvailable = [
        'uri_scheme',
        'anchor',
        'protocole',
        'protocol',
        'domain',
        'name',
        'redirect_uri',
    ];

    /**
     * @var array
     */
    protected $actionOptionsRequired = [
        'uri_scheme',
        'anchor',
    ];

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
        $return = [];
        $actions = $this->getOption('actions');

        foreach ($actions as $name => $options) {
            $options = $this->actionOptionsResolver->resolve((array) $options);
            $return[$name] = [];

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
        $this->getOptionsResolver()->setDefaults([
            'actions' => [],
        ]);

        $this->getOptionsResolver()->setAllowedTypes('actions', 'array');

        $this->actionOptionsResolver->setDefaults([
            'redirect_uri' => null,
            'domain' => null,
            'protocole' => 'http://',
            'protocol' => 'http://'
        ]);

        $this->actionOptionsResolver->setRequired([
            'uri_scheme'
        ]);

        $this->actionOptionsResolver->setAllowedTypes('redirect_uri', ['string', 'null']);
        $this->actionOptionsResolver->setAllowedTypes('uri_scheme', 'string');

        $this->actionOptionsResolver->setAllowedValues('protocol', ['http://', 'https://']);

        $this->actionOptionsResolver->setDefaults([
            'protocol' => function (Options $options, $value) {
                if (isset($options['protocole'])) {
                    $value = $options['protocole'];
                }
                return $value;
            }
        ]);
    }

    /**
     * @return \Symfony\Component\OptionsResolver\OptionsResolver
     */
    public function getActionOptionsResolver()
    {
        return $this->actionOptionsResolver;
    }
}
