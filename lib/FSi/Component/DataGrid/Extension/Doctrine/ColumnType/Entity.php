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
use Doctrine\Common\Collections\ArrayCollection;
use FSi\Component\DataGrid\Column\ColumnViewInterface;
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
        $mappingFields = $this->getOption('mapping_fields');
        $glueMultiple = $this->getOption('glue_multiple');

        if (is_array($value)) {
            foreach ($value as $object) {
                $objectValues = array();
                foreach ($mappingFields as $field) {
                    $objectValues[] = $this->getDataMapper()->getData($field, $object);
                }
                $values[] = implode($glueMultiple, $objectValues);
            }
        } else {
            foreach ($mappingFields as $field) {
                if (isset($value)) {
                    $values[] = $this->getDataMapper()->getData($field, $value);
                }
            }
        }

        return $values;
    }

    public function getDefaultOptionsValues()
    {
        return array(
            'label' => $this->getName(),
            'glue' => ' ',
            'glue_multiple' => ' '
        );
    }

    public function getRequiredOptions()
    {
        return array('mapping_fields', 'glue', 'relation_field');
    }

    public function getAvailableOptions()
    {
        return array('label', 'mapping_fields', 'glue', 'relation_field', 'glue_multiple');
    }
}