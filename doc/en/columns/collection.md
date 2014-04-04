# Text Column Type #

Provided by ``DataGrid\Extension\Core\CoreExtension``

## Available Options ##

* ``collection_glue`` - string, by default `` ``
* ``label`` - string, by default ``[$field->getName()]``
* ``field_mapping`` - **required**, array, by default ``[$field->getName()]``
* ``value_glue`` - string
* ``display_order`` - integer
* ``empty_value`` - string|array, by default ``""`` (empty string)

## Options Description ##

**collection_glue** option used to implode array elements, by default empty string " ".

**label** Label for column.

**mapping_fields** Fields that should be used when data is retrieved from the source. By default there is only one mapping
field and its taken from name under what column was registered in grid.
Option is useful when you need to implode few fields from object in one column.

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

## Example Usage ##

``` php
<?php

//Input Data: Object ('roles' => array('ROLE_ADMIN, 'ROLE_USER'))
$grid->addColumn('roles', 'collection', array('collection_glue' => ' | '));
//Output: "ROLE_ADMIN | ROLE_USER"

```
