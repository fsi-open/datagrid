# Number Column Type #

Provided by ``DataGrid\Extension\Core\CoreExtension``

## Available Options ##

* ``round_mode`` - integer
* ``precision`` - integer, by default ``2``
* ``label`` - string, by default ``[$field->getName()]``
* ``mapping_fields`` - **required**, array, by default ``[$field->getName()]``
* ``glue`` - string
* ``order`` - integer, by default ``0``
* ``editable`` - **required**, boolean, by default ``false``
* ``empty_value`` - string|array, by default ``""`` (empty string)

## Options Description ##

**round_mode** One of ``Number::ROUND_HALF_UP``, ``Number::ROUND_HALF_DOWN``, ``Number::ROUND_HALF_EVEN`` or ``Number::ROUND_HALF_ODD``.

**precision** Number of decimal digits to round to.

**label** By default label value its taken from name under what column was registered in grid.

**mapping_fields** Fields that should be used when data is retrieved from the source. By default there is only one mapping
field and its taken from name under what column was registered in grid.
Option is useful when you need to implode few fields from object in one column.

**glue** Useful only when you need to implode data from few source object fields into one column.

**format** Useful when you need to format value before passing it to view. Value iformatteded with php ``sprintf`` function. There should be at least same count of ``mapping_fields`` option
values as placeholders count in format string. This option can be used with ``glue`` option.  
``format`` option also accept ``\Clousure`` function that should return valid formated string. 

**order** Column order.

**editable** If enabled SymfonyForm object is automatically created and passed into view as attribute and you can easly use it to display quick edit.

**empty_value** if value from mapping field is empty (null, !strlen) then it will be replaced with ``empty_value`` option value which by default is empty string. There is also possibility to pass ``empty_value`` to selected ``mapping_fields``.
To do it you just need set ``empty_value`` as array where keys are ``mapping_fields`` keys. If mapping field value is empty and its not included in ``empty_value`` option it will be replaced with empty string.


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
