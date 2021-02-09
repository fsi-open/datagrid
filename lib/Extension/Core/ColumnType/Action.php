<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Extension\Core\ColumnType;

use FSi\Component\DataGrid\Column\ColumnAbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;

class Action extends ColumnAbstractType
{
    /**
     * @var OptionsResolver
     */
    protected $actionOptionsResolver;

    public function __construct()
    {
        $this->actionOptionsResolver = new OptionsResolver();
    }

    public function getId(): string
    {
        return 'action';
    }

    public function filterValue($value)
    {
        $return = [];
        $actions = $this->getOption('actions');

        foreach ($actions as $name => $options) {
            $options = $this->actionOptionsResolver->resolve((array) $options);
            $return[$name] = [];

            $url = (isset($options['protocol'], $options['domain'])) ? $options['protocol'] . $options['domain'] : '';
            $url .= vsprintf($options['uri_scheme'], $value);

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

    public function initOptions(): void
    {
        $this->getOptionsResolver()->setDefaults([
            'actions' => [],
        ]);

        $this->getOptionsResolver()->setAllowedTypes('actions', 'array');

        $this->actionOptionsResolver->setDefaults([
            'redirect_uri' => null,
            'domain' => null,
            'protocol' => 'http://'
        ]);

        $this->actionOptionsResolver->setRequired([
            'uri_scheme'
        ]);

        $this->actionOptionsResolver->setAllowedTypes('redirect_uri', ['string', 'null']);
        $this->actionOptionsResolver->setAllowedTypes('uri_scheme', 'string');
        $this->actionOptionsResolver->setAllowedValues('protocol', ['http://', 'https://']);
    }

    public function getActionOptionsResolver(): OptionsResolver
    {
        return $this->actionOptionsResolver;
    }
}
