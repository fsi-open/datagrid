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
use FSi\Component\DataGrid\Column\ColumnInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Entity extends ColumnAbstractType
{
    public function getId(): string
    {
        return 'entity';
    }

    public function initOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults([
            'relation_field' => function (Options $options, $previousValue) {
                if (null !== $previousValue) {
                    return $previousValue;
                }

                return $options['name'];
            },
        ]);

        $optionsResolver->setAllowedTypes('relation_field', 'string');
    }

    public function getValue(ColumnInterface $column, $object)
    {
        return $this->propertyAccessor->getValue($object, $column->getOption('relation_field'));
    }

    public function filterValue(ColumnInterface $column, $value)
    {
        if ($value instanceof Collection) {
            $value = $value->toArray();
        }

        $values = [];
        $objectValues = [];
        $mappingFields = $column->getOption('field_mapping');

        if (is_array($value)) {
            foreach ($value as $object) {
                foreach ($mappingFields as $field) {
                    $objectValues[$field] = $this->propertyAccessor->getValue($object, $field);
                }

                $values[] = $objectValues;
            }
        } else {
            foreach ($mappingFields as $field) {
                $objectValues[$field] = isset($value)
                    ? $this->propertyAccessor->getValue($value, $field)
                    : null;
            }

            $values[] = $objectValues;
        }

        return $values;
    }
}
