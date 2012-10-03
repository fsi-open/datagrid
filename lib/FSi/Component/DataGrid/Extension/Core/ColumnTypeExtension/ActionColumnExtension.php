<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension;

use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\Column\ColumnAbstractTypeExtension;
use FSi\Component\DataGrid\Exception\UnexpectedTypeException;

class ActionColumnExtension extends ColumnAbstractTypeExtension 
{
    protected $actionOptionsDefault = array(
        'protocole' => 'http://'
    );

    protected $actionOptionsAvailable = array(
        'uri_scheme',
        'anchor',
        'protocole',
        'domain',
        'name',
    );

    protected $actionOptionsRequired = array(
        'uri_scheme',
        'anchor'
    );

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

            $url = (isset($options['protocole'], $options['domain'])) ? $options['protocole'] . $options['domain'] : ''; 
            $url .= vsprintf ($options['uri_scheme'], $value);

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
            throw new UnexpectedTypeException('Option actions must be an array.');
        }

        foreach ($actions as $actionName => &$options) {
            
            if (!is_array($options)) {
                throw new UnexpectedTypeException(sprinf('Options for action "%s" must be an array.', $actionName));    
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
        
        $column->setOption('actions', $actions);

    }
}