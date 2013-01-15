<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Extension\Core\ColumnType;

use FSi\Component\DataGrid\Column\ColumnAbstractType;

class Action extends ColumnAbstractType
{
    protected $actionOptionsDefault = array(
        'protocole' => 'http://',
    );

    protected $actionOptionsAvailable = array(
        'uri_scheme',
        'anchor',
        'protocole',
        'domain',
        'name',
        'redirect_uri'
    );

    protected $actionOptionsRequired = array(
        'uri_scheme',
        'anchor'
    );

    public function getId()
    {
        return 'action';
    }

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
        }

        return $return;
    }

    protected function getRequiredOptions()
    {
        return array('actions');
    }

    protected function getAvailableOptions()
    {
        return array('actions');
    }

    private function validateOptions()
    {
        $actions = $this->getOption('actions');
        if (!is_array($actions)) {
            throw new \InvalidArgumentException('Option actions must be an array.');
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
        }

        $this->setOption('actions', $actions);
    }
}