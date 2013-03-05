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
* ``mapping_fields`` - **required**, array, by default ``[$field->getName()]``
* ``glue`` - string
* ``order`` - integer
* ``editable`` - **required**, boolean, by default ``false``
* ``fields_options`` - array, by default ``array()``
* ``empty_value`` - string|array, by default ``""`` (empty string)

## Options Description ##

**round_mode** One of ``Number::ROUND_HALF_UP``, ``Number::ROUND_HALF_DOWN``, ``Number::ROUND_HALF_EVEN`` or ``Number::ROUND_HALF_ODD``.

**precision** Number of decimal digits to round to.

**decimals** The number of decimal points.

**dec_point** Decimal point.

**thousands_sep** Thousands separator.

**value_currency_separator** Separator between currency and value.

**currency_field** Field to take actual currency from. Mandatory if currency_value not specified.

**currency** Currency. Mandatory if currency_field not specified.

**mapping_fields** Fields that should be used when data is retrieved from the source. By default there is only one mapping
field and its taken from name under what column was registered in grid.
Option is useful when you need to implode few fields from object in one column.

**label** By default label value its taken from name under what column was registered in grid.

**glue** Useful only when you need to implode data from few source object fields into one column.

**format** Useful when you need to format value before passing it to view. Value iformatteded with php ``sprintf`` function. There should be at least same count of ``mapping_fields`` option
values as placeholders count in format string. This option can be used with ``glue`` option.  
``format`` option also accept ``\Clousure`` function that should return valid formated string. 

**empty_value** if value from mapping field is empty (null, !strlen) then it will be replaced with ``empty_value`` option value which by default is empty string. There is also possibility to pass ``empty_value`` to selected ``mapping_fields``.
To do it you just need set ``empty_value`` as array where keys are ``mapping_fields`` keys. If mapping field value is empty and its not included in ``empty_value`` option it will be replaced with empty string.

**order** Optional integer value specifying order of column in grid. Columns in grid are sorted according
  to ascending value of this option. Columns without this option will stay in their natural order (between columns with
  positive and negative values of this option)  

## Example Usage ##

``` php
<?php

//This configuration will show price from field 'price' and its currency from 'currency' field.
$datagrid->addColumn('productprice', 'money', array(
    'label' => 'Product price',
    'mapping_fields' => array('price', 'currency'),
    'currency_field' => 'currency',
    'value_currency_separator' => ' - ',
    'glue' => '<br />',
));

//This configuration will show price from two fields ('price' and 'promo_price') with arbitrary USD currency.
$datagrid->addColumn('productprice', 'money', array(
    'label' => 'Product price',
    'mapping_fields' => array('price', 'promo_price'),
    'currency' => 'USD',
    'value_currency_separator' => '$ ',
    'dec_point' => ',',
));

```