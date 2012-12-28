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
* ``glue`` - **required**, string, by default ``" "`` (space character)
* ``order`` - integer, by default ``0``
* ``editable`` - **required**, boolean, by default ``false``
* ``fields_options`` - array, by default ``array()``

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
field and its taken from name under what column was registred in grid. 
Option is useful when you need to implode few fields from object in one column. 

**label** By default label value its taken from name under what column was registred in grid. 

**glue** Useful only when you need to implode data from few source object fields into one column. By default its single space character " ". 

## Example Usage ##

``` php
<?php

//This cofiguration will show price from field 'price' and its currency from 'currency' field.
$datagrid->addColumn('productprice', 'money', array(
    'label' => 'Product price', 
    'mapping_fields' => array('price', 'currency'),
    'currency_field' => 'currency',
    'value_currency_separator' => ' - ',
    'glue' => '<br />',
));

//This cofiguration will show price from two fields ('price' and 'promo_price') with arbitrary USD currency.
$datagrid->addColumn('productprice', 'money', array(
    'label' => 'Product price', 
    'mapping_fields' => array('price', 'promo_price'),
    'currency' => 'USD',
    'value_currency_separator' => '$ ',
    'dec_point' => ',',
));

```