# Number Column Type #

Provided by ``DataGrid\Extension\Core\CoreExtension``

## Available Options ##

* ``round_mode`` - integer
* ``precision`` - integer, by default ``2``
* ``label`` - string, by default ``[$field->getName()]``
* ``field_mapping`` - **required**, array, by default ``[$field->getName()]``
* ``value_glue`` - string
* ``display_order`` - integer
* ``editable`` - **required**, boolean, by default ``false``
* ``empty_value`` - string|array, by default ``""`` (empty string)
* ``form_options`` - array, by default ``[]``
* ``form_type`` - array, by default ``[]``
* ``format`` - boolean, by default ``false``
* ``format_decimals`` - integer, by default ``0``
* ``format_dec_point`` - string, by default ``.``
* ``format_thousands_sep`` - string, by default ``,``

## Options Description ##

**round_mode** One of ``Number::ROUND_HALF_UP``, ``Number::ROUND_HALF_DOWN``, ``Number::ROUND_HALF_EVEN`` or ``Number::ROUND_HALF_ODD``.

**precision** Number of decimal digits to round to.

**label** By default label value its taken from name under what column was registered in grid.

**field_mapping** Fields that should be used when data is retrieved from the source. By default there is only one
field and its taken from the name under what column was registered in grid.
Option is useful when you need to implode few fields from object in one column.

**value_glue** Useful only when you need to implode data from few source object fields into one column.

**value_format** Useful when you need to format value before passing it to view. Value iformatteded with php ``sprintf`` function. There should be at least same count of ``mapping_fields`` option
values as placeholders count in format string. This option can be used with ``value_glue`` option.
``format`` option also accept ``\Clousure`` function that should return valid formated string.

**display_order** Optional integer value specifying order of column in grid. Columns in grid are sorted according
  to ascending value of this option. Columns without this option will stay in their natural order (between columns with
  positive and negative values of this option)

**editable** If enabled SymfonyForm object is automatically created and passed into view as attribute and you can easily use it to display quick edit.

**form_options** Array of options for forms, where key is name of field (one of field_mapping) and value is
options passed to form.

**form_type** Array of types for forms, where key is name of field (one of field_mapping) and value is form type.

**format** If set to true, number will be formatted using options ``format_decimals``, ``format_dec_point`` and ``format_thousands_sep`` according to http://php.net/manual/en/function.number-format.php By default ``format`` option is disabled, so value will not be formatted in case it is phone number or some id number.

**format_decimals** By default ``2``. See **format** option for description.

**format_dec_point** By default ``.``. See **format** option for description.

**format_thousands_sep** By default ``,``. See **format** option for description.

## Example Usage ##

``` php
<?php

//Input Data: Object ('value' => 10.123)
$grid->addColumn('price', 'number', [
    'field_mapping' => [
        'value'
    ],
    'round_mode' => Number::ROUND_HALF_UP
    'precision' => 2
]);
//Output: 10.12

//Input Data: Object ('value' => 10.126)
$grid->addColumn('price', 'number', [
    'field_mapping' => [
        'value'
    ],
    'round_mode' => Number::ROUND_HALF_UP
    'precision' => 2
]);

//Output: 10.13
```
