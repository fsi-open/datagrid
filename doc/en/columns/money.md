# Money Column Type #

Provided by ``DataGrid\Extension\Core\CoreExtension``

## Available Options ##

* ``round_mode`` - integer
* ``precision`` - integer, by default ``2``
* ``decimals`` - integer, by default ``2``
* ``dec_point`` - string, by default ``.``
* ``thousands_sep`` - string, by default ``,``
* ``value_currency_separator`` - string, by default `` `` (space character)
* ``currency_field`` - string
* ``currency`` - string
* ``label`` - string, by default ``[$field->getName()]``
* ``field_mapping`` - **required**, array, by default ``[$field->getName()]``
* ``value_glue`` - string
* ``display_order`` - integer
* ``editable`` - **required**, boolean, by default ``false``
* ``empty_value`` - string|array, by default ``""`` (empty string)
* ``form_options`` - array, by default ``[]``
* ``form_type`` - array, by default ``[]``

## Options Description ##

**round_mode** One of ``Number::ROUND_HALF_UP``, ``Number::ROUND_HALF_DOWN``, ``Number::ROUND_HALF_EVEN`` or ``Number::ROUND_HALF_ODD``.

**precision** Number of decimal digits to round to.

**decimals** The number of decimal points.

**dec_point** Decimal point.

**thousands_sep** Thousands separator.

**value_currency_separator** Separator between currency and value.

**currency_field** Field to take actual currency from. Mandatory if currency_value not specified.

**currency** Currency. Mandatory if currency_field not specified.

**field_mapping** Fields that should be used when data is retrieved from the source. By default there is only one
field and its taken from the name under what column was registered in grid.
Option is useful when you need to implode few fields from object in one column.

**value_glue** Useful only when you need to implode data from few source object fields into one column.

**value_format** Useful when you need to format value before passing it to view. Value formatted with php ``sprintf`` function. There should be at least same count of ``mapping_fields`` option
values as placeholders count in format string. This option can be used with ``value_glue`` option.
``format`` option also accept ``\Closure`` function that should return valid formatted string.

**display_order** Optional integer value specifying order of column in grid. Columns in grid are sorted according
  to ascending value of this option. Columns without this option will stay in their natural order (between columns with
  positive and negative values of this option)

**editable** If enabled SymfonyForm object is automatically created and passed into view as attribute and you can easily use it to display quick edit.

**form_options** Array of options for forms, where key is name of field (one of field_mapping) and value is
options passed to form.

**form_type** Array of types for forms, where key is name of field (one of field_mapping) and value is form type.

## Example Usage ##

``` php
<?php

//This configuration will show price from field 'price' and its currency from 'currency' field.
$datagrid->addColumn('productprice', 'money', [
    'label' => 'Product price',
    'field_mapping' => ['price', 'currency'],
    'currency_field' => 'currency',
    'value_currency_separator' => ' - ',
    'value_glue' => '<br />',
]);

//This configuration will show price from two fields ('price' and 'promo_price') with arbitrary USD currency.
$datagrid->addColumn('productprice', 'money', [
    'label' => 'Product price',
    'field_mapping' => ['price', 'promo_price'],
    'currency' => 'USD',
    'value_currency_separator' => '$ ',
    'dec_point' => ',',
]);

```
