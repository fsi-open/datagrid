<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Extension\Doctrine\ColumnType;

use Doctrine\Common\Collections\Collection;
use FSi\Component\DataGrid\Column\ColumnAbstractType;

class Entity extends ColumnAbstractType
{
    public function getId(): string
    {
        return 'entity';
    }

    public function getValue($object)
    {
        return $this->getDataMapper()->getData($this->getOption('relation_field'), $object);
    }

    public function filterValue($value)
    {
        if ($value instanceof Collection) {
            $value = $value->toArray();
        }

        $values = [];
        $objectValues = [];
        $mappingFields = $this->getOption('field_mapping');

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

    public function initOptions(): void
    {
        $this->getOptionsResolver()->setDefaults([
            'relation_field' => $this->getName(),
        ]);

        $this->getOptionsResolver()->setAllowedTypes('relation_field', 'string');
    }
}
