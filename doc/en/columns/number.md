# Number Column Type #

Provided by ``DataGrid\Extension\Core\CoreExtension``

## Available Options ##

* ``round_mode`` - integer
* ``precision`` - integer, by default ``2`` 
* ``label`` - string, by default ``[$field->getName()]``
* ``mapping_fields`` - **required**, array, by default ``[$field->getName()]``
* ``glue`` - **required**, string, by default ``" "`` (space character)
* ``order`` - integer, by default ``0``
* ``editable`` - **required**, boolean, by default ``false``

## Options Description ##

**round_mode** One of ``Number::ROUND_HALF_UP``, ``Number::ROUND_HALF_DOWN``, ``Number::ROUND_HALF_EVEN`` or ``Number::ROUND_HALF_ODD``.

**precision** Number of decimal digits to round to. 

**label** By default label value its taken from name under what column was registred in grid. 

**mapping_fields** Fields that should be used when data is retrieved from the source. By default there is only one mapping 
field and its taken from name under what column was registred in grid. 
Option is useful when you need to implode few fields from object in one column. 

**glue** Useful only when you need to implode data from few source object fields into one column. By default its single space character " ".

**order** Column order.

**editable** If enabled SymfonyForm object is automatically created and passed into view as attribute and you can easly use it to display quick edit.
 
## Example Usage ##

``` php
<?php

//Input Data: Object ('value' => 10.123)
$grid->addColumn('price', 'number', array(
    'mapping_fields' => array(
        'value'
    ),
    'round_mode' => Number::ROUND_HALF_UP
    'precision' => 2
));
//Output: 10.12

//Input Data: Object ('value' => 10.126)
$grid->addColumn('price', 'number', array(
    'mapping_fields' => array(
        'value'
    ),
    'round_mode' => Number::ROUND_HALF_UP
    'precision' => 2
));

//Output: 10.13
```
