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

class Number extends ColumnAbstractType
{
    public const ROUND_HALF_UP = PHP_ROUND_HALF_UP;
    public const ROUND_HALF_DOWN = PHP_ROUND_HALF_DOWN;
    public const ROUND_HALF_EVEN = PHP_ROUND_HALF_EVEN;
    public const ROUND_HALF_ODD = PHP_ROUND_HALF_ODD;

    public function getId(): string
    {
        return 'number';
    }

    public function filterValue($value)
    {
        $precision = (int) $this->getOption('precision');
        $roundmode = $this->getOption('round_mode');

        $format = $this->getOption('format');
        $format_decimals = $this->getOption('format_decimals');
        $format_dec_point = $this->getOption('format_dec_point');
        $format_thousands_sep = $this->getOption('format_thousands_sep');

        foreach ($value as &$val) {
            if (empty($val)) {
                continue;
            }

            if (isset($roundmode)) {
                $val = round($val, $precision, $roundmode);
            }

            if ($format) {
                $val = number_format((float) $val, $format_decimals, $format_dec_point, $format_thousands_sep);
            }
        }

        return $value;
    }

    public function initOptions(): void
    {
        $this->options = [
            'round_mode' => null,
            'precision' => 2,
            'format' => false,
            'format_decimals' => 2,
            'format_dec_point' => '.',
            'format_thousands_sep' => ',',
        ];

        $this->getOptionsResolver()->setDefaults($this->options);

        $this->getOptionsResolver()->setAllowedTypes('precision', 'integer');
        $this->getOptionsResolver()->setAllowedTypes('format', 'bool');
        $this->getOptionsResolver()->setAllowedTypes('format_decimals', 'integer');

        $this->getOptionsResolver()->setAllowedValues('round_mode', [
            null,
            self::ROUND_HALF_UP,
            self::ROUND_HALF_DOWN,
            self::ROUND_HALF_EVEN,
            self::ROUND_HALF_ODD,
        ]);
    }
}
