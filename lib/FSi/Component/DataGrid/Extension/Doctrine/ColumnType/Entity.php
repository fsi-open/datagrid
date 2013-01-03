<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Extension\Doctrine\ColumnType;

use Doctrine\Common\Collections\Collection;
use FSi\Component\DataGrid\Column\ColumnAbstractType;

class Entity extends ColumnAbstractType
{
    public function getId()
    {
        return 'entity';
    }

    public function getValue($object)
    {
        $value = null;
        $value = $this->getDataMapper()->getData($this->getOption('relation_field'), $object);

        return $value;
    }

    public function filterValue($value)
    {
        if ($value instanceof Collection) {
            $value = $value->toArray();
        }

        $values = array();
        $objectValues = array();
        $mappingFields = $this->getOption('mapping_fields');

        if (is_array($value)) {
            foreach ($value as $object) {
                foreach ($mappingFields as $field) {
                    $objectValues[$field] = $this->getDataMapper()->getData($field, $object);
                }

                $values[] = $objectValues;
            }
        } else {
            foreach ($mappingFields as $field) {
                $objectValues[$field] = isset($value)
                    ? $this->getDataMapper()->getData($field, $value)
                    : null;
            }

            $values[] = $objectValues;
        }

        return $values;
    }

    public function getDefaultOptionsValues()
    {
        return array(
            'label' => $this->getName(),
            'relation_field' => $this->getName()
        );
    }

    public function getRequiredOptions()
    {
        return array('mapping_fields', 'relation_field');
    }

    public function getAvailableOptions()
    {
        return array('label', 'mapping_fields', 'relation_field');
    }
}