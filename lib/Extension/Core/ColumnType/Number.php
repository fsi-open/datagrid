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
use FSi\Component\DataGrid\Column\ColumnInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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

    public function filterValue(ColumnInterface $column, $value)
    {
        $precision = (int) $column->getOption('precision');
        $roundMode = $column->getOption('round_mode');

        $format = $column->getOption('format');
        $format_decimals = $column->getOption('format_decimals');
        $format_dec_point = $column->getOption('format_dec_point');
        $format_thousands_sep = $column->getOption('format_thousands_sep');

        foreach ($value as &$val) {
            if (empty($val)) {
                continue;
            }

            if (null !== $roundMode) {
                $val = round($val, $precision, $roundMode);
            }

            if ($format) {
                $val = number_format($val, $format_decimals, $format_dec_point, $format_thousands_sep);
            }
        }

        return $value;
    }

    public function initOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults([
            'round_mode' => null,
            'precision' => 2,
            'format' => false,
            'format_decimals' => 2,
            'format_dec_point' => '.',
            'format_thousands_sep' => ',',
        ]);

        $optionsResolver->setAllowedTypes('precision', 'integer');
        $optionsResolver->setAllowedTypes('format', 'bool');
        $optionsResolver->setAllowedTypes('format_decimals', 'integer');

        $optionsResolver->setAllowedValues('round_mode', [
            null,
            self::ROUND_HALF_UP,
            self::ROUND_HALF_DOWN,
            self::ROUND_HALF_EVEN,
            self::ROUND_HALF_ODD,
        ]);
    }
}
