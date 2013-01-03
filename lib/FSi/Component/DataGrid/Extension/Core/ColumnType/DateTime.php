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
use FSi\Component\DataGrid\Exception\DataGridColumnException;

class DateTime extends ColumnAbstractType
{
    public function getId()
    {
        return 'datetime';
    }

    public function filterValue($value)
    {
        $format = $this->getOption('datetime_format');
        $inputValues = $this->getInputData($value);

        $return = array();
        foreach ($inputValues as $field => $value) {
            if (is_string($format)) {
                $return[$field] = $value->format($format);
                continue;
            }
            if (is_array($format)) {
                if (!array_key_exists($field, $format)) {
                    throw new DataGridColumnException(
                        sprintf('There is not format for field "%s" in "format" option value.', $field)
                    );
                }
                $return[$field] = $value->format($format[$field]);
            }
        }

        return $return;
    }

    public function getDefaultOptionsValues()
    {
        return array(
            'datetime_format' => 'Y-m-d H:i:s',
            'mapping_fields' => array($this->getName()),
        );
    }

    public function getRequiredOptions()
    {
        return array('datetime_format');
    }

    public function getAvailableOptions()
    {
        return array('datetime_format', 'input', 'mapping_fields', 'mapping_fields_format');
    }

    private function getInputData($value)
    {
        $input = $this->getOption('input');
        $mappingFormat = $this->getOption('mapping_fields_format');

        if (!isset($input)) $input = $this->guessInput($value);

        if (!is_string($input)) {
            throw new DataGridColumnException('"input" option must be a string.');
        }

        $mappingFields = $this->getOption('mapping_fields');
        $inputData = array();
        foreach ($mappingFields as $field) {
            $inputData[$field] = null;
        }

        switch (strtolower($input)) {
            case 'array':
                if (!isset($mappingFormat)) {
                    throw new DataGridColumnException(
                        '"mapping_fields_format" option is missing. Example: "mapping_fields_format" => array("mapping_field_name" => array("input" => "datetime"))'
                    );
                }
                if (!is_array($mappingFormat)) {
                    throw new DataGridColumnException(
                        '"mapping_fields_format" option value must be an array with keys that match mapping fields names.'
                    );
                }
                if (count($mappingFormat) != count($value)) {
                    throw new DataGridColumnException(
                        '"mapping_fields_format" option value array must have same count as "mapping_fields" option value array.  '
                    );
                }

                foreach ($mappingFormat as $field => $input) {
                    if (!array_key_exists($field, $value)) {
                        throw new DataGridColumnException(
                            sprintf('Unknown mapping_field "%s".', $field)
                        );
                    }
                    if (!is_array($input)) {
                        throw new DataGridColumnException(
                            sprintf('"%s" should be an array.', $field)
                        );
                    }
                    $fieldInput = (array_key_exists('input', $input)) ? $input['input'] : $this->guessInput($value[$field]);

                    switch (strtolower($fieldInput)) {
                        case 'string':
                            $mappingFormat = (array_key_exists('datetime_format', $input)) ? $input['datetime_format'] : null;
                            if (!isset($mappingFormat)) {
                                throw new DataGridColumnException(
                                    sprintf('"datetime_format" option is required in "mapping_fields_datetime_format" for field "%s".', $field)
                                );
                            }
                            $inputData[$field] = $this->transformStringToDateTime($value[$field], $mappingFormat);
                            break;
                        case 'timestamp':
                            $inputData[$field] = $this->transformTimestampToDateTime($value[$field]);
                            break;
                        case 'datetime':
                            if (!($value[$field] instanceof \DateTime)) {
                                throw new DataGridColumnException(
                                    sprintf('Value in field "%s" is "%s" type instead of "\DateTime" instance.', $field, gettype($value[$field]))
                                );
                            }
                            $inputData[$field] = $value[$field];
                            break;
                        default:
                            throw new DataGridColumnException(
                                sprintf('"%s" is not valid input option value for field "%s". '.
                                'You should consider using one of "array", "string", "datetime" or "timestamp" input option values. ', $fieldInput, $field)
                            );
                    }
                }
                break;
            case 'string':
                $field = key($value);
                $value = current($value);

                if (!is_string($value)) {
                    throw new DataGridColumnException(
                        sprintf('Value in field "%s" is not a valid string.', $field)
                    );
                }

                $inputData[$field] = $this->transformStringToDateTime($value, $mappingFormat);
                break;
            case 'datetime':
                $field = key($value);
                $value = current($value);

                if (!($value instanceof \DateTime)) {
                    throw new DataGridColumnException(
                        sprintf('Value in field "%s" is not instance of "\DateTime"', $field)
                    );
                }

                $inputData[$field] = $value;
                break;
            case 'timestamp':
                $field = key($value);
                $value = current($value);

                $inputData[$field] = $this->transformTimestampToDateTime($value);
                break;
            default:
                throw new DataGridColumnException(
                    sprintf('"%s" is not valid input option value. '.
                    'You should consider using one of "array", "string", "datetime" or "timestamp" input option values. ', $input)
                );
        }

        return $inputData;
    }

    /**
     * If input option value is not passed into column this method should
     * be called to guess input type from column $value
     *
     * @param array $value
     */
    private function guessInput($value)
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

        if (is_numeric($value)) {
            return 'timestamp';
        }

        if (is_string($value)) {
            return 'string';
        }

        return null;
    }

    private function transformStringToDateTime($value, $mappingFormat)
    {
        if (!isset($mappingFormat)) {
            throw new DataGridColumnException(
                '"mapping_fields_format" option is missing. Example: "mapping_fields_format" => "Y-m-d H:i:s"'
            );
        }

        if (!is_string($mappingFormat)) {
            throw new DataGridColumnException(
                'When using input type "string", "mapping_fields_format" option must be an string that contains valid data format'
            );
        }

        $dateTime = \DateTime::CreateFromFormat($mappingFormat, $value);

        if (!($dateTime instanceof \DateTime)) {
            throw new DataGridColumnException(
                sprintf('value "%s" does not fit into format "%s" ', $value, $mappingFormat)
            );
        }

        return $dateTime;
    }

    private function transformTimestampToDateTime($value)
    {
        if (!filter_var($value, FILTER_VALIDATE_INT)) {
            throw new \InvalidArgumentException(
                sprintf('Value in column "%s" should be timestamp but "%s" type was detected. Maybe you should consider using different "input" opition value?', $this->getName(), gettype($value))
            );
        }

        $dateTime = new \DateTime('@' . $value);

        if (!($dateTime instanceof \DateTime)) {
            throw new DataGridColumnException(
                sprintf('value "%s" is not a valid timestamp', $value)
            );
        }

        return $dateTime;
    }
}