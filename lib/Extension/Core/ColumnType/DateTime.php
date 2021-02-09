<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid\Extension\Core\ColumnType;

use DateTimeInterface;
use FSi\Component\DataGrid\Column\ColumnAbstractType;
use FSi\Component\DataGrid\Exception\DataGridColumnException;

class DateTime extends ColumnAbstractType
{
    public function getId(): string
    {
        return 'datetime';
    }

    public function filterValue($value)
    {
        $format = $this->getOption('datetime_format');
        $inputValues = $this->getInputData($value);

        $return = [];
        foreach ($inputValues as $field => $fieldValue) {
            if (empty($fieldValue)) {
                $return[$field]  = null;
                continue;
            }

            if (is_string($format)) {
                $return[$field] = $fieldValue->format($format);
                continue;
            }

            if (is_array($format)) {
                if (!array_key_exists($field, $format)) {
                    throw new DataGridColumnException(
                        sprintf('There is not format for field "%s" in "format" option value.', $field)
                    );
                }

                $return[$field] = $fieldValue->format($format[$field]);
            }
        }

        return $return;
    }

    public function initOptions(): void
    {
        $this->getOptionsResolver()->setDefaults([
            'datetime_format' => 'Y-m-d H:i:s',
            'input_type' => null,
            'input_field_format' => null
        ]);

        $this->getOptionsResolver()->setAllowedTypes('input_field_format', ['null', 'array', 'string']);

        $this->getOptionsResolver()->setAllowedValues('input_type', [
            null,
            'string',
            'timestamp',
            'datetime',
            'datetime_interface',
            'array'
        ]);
    }

    private function getInputData($value)
    {
        $inputType = $this->getOption('input_type');
        $mappingFormat = $this->getOption('input_field_format');

        if (null === $inputType) {
            $inputType = $this->guessInputType($value);
        }

        $mappingFields = $this->getOption('field_mapping');
        $inputData = [];
        foreach ($mappingFields as $field) {
            $inputData[$field] = null;
        }

        switch (strtolower($inputType)) {
            case 'array':
                if (!isset($mappingFormat)) {
                    throw new DataGridColumnException(
                        '"input_field_format" option is missing. Example: '
                            . '"input_field_format" => array("mapping_field_name" => array("input" => "datetime"))'
                    );
                }
                if (!is_array($mappingFormat)) {
                    throw new DataGridColumnException(
                        '"input_field_format" option value must be an array with keys that match mapping fields names.'
                    );
                }
                if (count($mappingFormat) !== count($value)) {
                    throw new DataGridColumnException(
                        '"input_field_format" option value array must have same count as "field_mapping" option'
                            . ' value array.  '
                    );
                }

                foreach ($mappingFormat as $field => $inputType) {
                    if (!array_key_exists($field, $value)) {
                        throw new DataGridColumnException(
                            sprintf('Unknown mapping field "%s".', $field)
                        );
                    }
                    if (!is_array($inputType)) {
                        throw new DataGridColumnException(
                            sprintf('"%s" should be an array.', $field)
                        );
                    }
                    $fieldInputType = array_key_exists('input_type', $inputType)
                        ? $inputType['input_type']
                        : $this->guessInputType($value[$field]);

                    switch (strtolower($fieldInputType)) {
                        case 'string':
                            $mappingFormat = array_key_exists('datetime_format', $inputType)
                                ? $inputType['datetime_format']
                                : null;
                            if (null === $mappingFormat) {
                                throw new DataGridColumnException(
                                    sprintf(
                                        '"datetime_format" option is required in "input_field_format" for field "%s".',
                                        $field
                                    )
                                );
                            }
                            if (empty($value[$field])) {
                                $inputData[$field] = null;
                            } else {
                                $inputData[$field] = $this->transformStringToDateTime($value[$field], $mappingFormat);
                            }

                            break;
                        case 'timestamp':
                            if (empty($value[$field])) {
                                $inputData[$field] = null;
                            } else {
                                $inputData[$field] = $this->transformTimestampToDateTime($value[$field]);
                            }
                            break;
                        case 'datetime':
                            if (!empty($value[$field]) && !($value[$field] instanceof \DateTime)) {
                                throw new DataGridColumnException(
                                    sprintf(
                                        'Value in field "%s" is "%s" type instead of "\DateTime" instance.',
                                        $field,
                                        gettype($value[$field])
                                    )
                                );
                            }

                            $inputData[$field] = $value[$field];
                            break;
                        case 'datetime_interface':
                            if (!interface_exists(\DateTimeInterface::class)) {
                                throw new DataGridColumnException(
                                    sprintf(
                                        'Input type option has value "datetime_interface" but %s is not defined',
                                        DateTimeInterface::class
                                    )
                                );
                            }

                            if (!empty($value[$field]) && !($value[$field] instanceof \DateTimeInterface)) {
                                throw new DataGridColumnException(
                                    sprintf(
                                        'Value in field "%s" is "%s" type instead of "\DateTimeInterface" instance.',
                                        $field,
                                        gettype($value[$field])
                                    )
                                );
                            }

                            $inputData[$field] = $value[$field];
                            break;
                        default:
                            throw new DataGridColumnException(sprintf(
                                '"%s" is not valid input option value for field "%s". '
                                    . 'You should consider using one of "array", "string", "datetime" or "timestamp" '
                                    . ' input option values. ',
                                $fieldInputType,
                                $field
                            ));
                    }
                }
                break;

            case 'string':
                $field = key($value);
                $value = current($value);

                if (!empty($value) && !is_string($value)) {
                    throw new DataGridColumnException(
                        sprintf('Value in field "%s" is not a valid string.', $field)
                    );
                }

                if (empty($value)) {
                    $inputData[$field] = null;
                } else {
                    $inputData[$field] = $this->transformStringToDateTime($value, $mappingFormat);
                }

                break;

            case 'datetime':
                $field = key($value);
                $value = current($value);

                if (!empty($value) && !($value instanceof \DateTime)) {
                    throw new DataGridColumnException(
                        sprintf('Value in field "%s" is not instance of "\DateTime"', $field)
                    );
                }

                $inputData[$field] = $value;
                break;

            case 'datetime_interface':
                if (!interface_exists(\DateTimeInterface::class)) {
                    throw new DataGridColumnException(
                        'Input type option has value "datetime_interface" but \DateTimeInterface is not defined'
                    );
                }

                $field = key($value);
                $value = current($value);

                if (!empty($value) && !($value instanceof \DateTimeInterface)) {
                    throw new DataGridColumnException(
                        sprintf('Value in field "%s" is not instance of "\DateTimeInterface"', $field)
                    );
                }

                $inputData[$field] = $value;
                break;

            case 'timestamp':
                $field = key($value);
                $value = current($value);

                if (empty($value)) {
                    $inputData[$field] = null;
                } else {
                    $inputData[$field] = $this->transformTimestampToDateTime($value);
                }
                break;

            default:
                throw new DataGridColumnException(
                    sprintf(
                        '"%s" is not valid input option value. '
                            . 'You should consider using one of "array", "string", "datetime" or "timestamp" input '
                            . 'option values. ',
                        $inputType
                    )
                );
        }

        return $inputData;
    }

    private function guessInputType($value): ?string
    {
        if (is_array($value)) {
            if (count($value) > 1) {
                throw new DataGridColumnException(
                    'If you want to use more that one mapping fields you need to set "input" option value "array".'
                );
            }
            $value = current($value);
        }

        if ($value instanceof \DateTime) {
            return 'datetime';
        }

        if (interface_exists(\DateTimeInterface::class) && ($value instanceof \DateTimeInterface)) {
            return 'datetime_interface';
        }

        if (is_numeric($value)) {
            return 'timestamp';
        }

        if (is_string($value) || empty($value)) {
            return 'string';
        }

        return null;
    }

    private function transformStringToDateTime(?string $value, $mappingFormat): \DateTime
    {
        if (!isset($mappingFormat)) {
            throw new DataGridColumnException(
                '"mapping_fields_format" option is missing. Example: "mapping_fields_format" => "Y-m-d H:i:s"'
            );
        }

        if (!is_string($mappingFormat)) {
            throw new DataGridColumnException(
                'When using input type "string", "mapping_fields_format" option must be an string that contains '
                    . 'valid data format'
            );
        }

        $dateTime = \DateTime::createFromFormat($mappingFormat, $value);

        if (!$dateTime instanceof \DateTime) {
            throw new DataGridColumnException(
                sprintf('value "%s" does not fit into format "%s" ', $value, $mappingFormat)
            );
        }

        return $dateTime;
    }

    private function transformTimestampToDateTime($value): \DateTime
    {
        if (!is_numeric($value)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Value in column "%s" should be timestamp but "%s" type was detected.'
                        . ' Maybe you should consider using different "input" option value?',
                    $this->getName(),
                    gettype($value)
                )
            );
        }

        $dateTime = new \DateTime();
        $dateTime->setTimestamp($value);

        if (!$dateTime instanceof \DateTime) {
            throw new DataGridColumnException(
                sprintf('value "%s" is not a valid timestamp', $value)
            );
        }

        return $dateTime;
    }
}
