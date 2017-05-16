# DateTime Column Type #

Provided by ``DataGrid\Extension\Core\CoreExtension``

## Available Options ##

* ``datetime_format`` - **required**, string, by default ``Y-m-d H:i:s``
* ``input_type`` - string
* ``input_field_format`` - array
* ``label`` - string, by default ``[$field->getName()]``
* ``field_mapping`` - **required**, array, by default ``[$field->getName()]``
* ``value_glue`` - string
* ``value_format`` - string
* ``display_order`` - integer
* ``editable`` - **required**, boolean, by default ``false``
* ``form_options`` - array, by default ``array()``
* ``form_type`` - array, by default ``array()``
* ``empty_value`` - string|array, by default ``""`` (empty string)

## Options Description ##

**datetime_format** Format of showed date and/or time.

**input_type** Kind of data you are giving to column (``array``, ``datetime``, ``datetime_interface``, ``string``, ``timestamp``) - if no specified, column will try to guess it.

**input_field_format** Array of formats used if you specify more than one field in field_mapping option (that keys match 'field_mapping` option keys), otherwise its equal to 'datetime_format' option.

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

## Example Usage ##

``` php
<?php

//Shows column with date in 'Y-m-d H:i:s' format.
$datagrid->addColumn('purchase_date', 'datetime', array(
    'label' => 'Purchase date',
    'datetime_format' => 'Y-m-d H:i:s',
    'editable' => true,
));

//Shows column with date that is combination of create_date and create_time fields.
$datagrid->addColumn('create_datetime', 'datetime', array(
    'label' => 'Create datetime',
    'field_mapping' => array('create_date', 'create_time'),
    'value_glue' => '&nbsp;',
    'editable' => true,
    'input_type' => 'array',
    'datetime_format' => array('create_date' => 'Y-m-d', 'create_time' => "H:i:s"),
    'input_field_format' => array('create_date' => array('input_type' => 'datetime'), 'create_time' => array('input_type' => 'datetime')),
));

//Shows column that value is combination formatted fields create_date and timestamp.
$datagrid->addColumn('create_date_timestamp', 'datetime', array(
    'label' => 'Crate date from timestamp',
    'field_mapping' => array('create_date', 'timestamp'),
    'value_glue' => '<br/>',
    'editable' => true,
    'input_type' => 'array',
    'datetime_format' => array('timestamp' => 'Y-m-d h:i:s', 'create_date' => 'Y-m-d'),
    'input_field_format' => array('create_date' => array('input_type' => 'datetime'), 'timestamp' => array('input_type' => 'timestamp'))
));

//Shows date, that is combination of three integer fields.
$datagrid->addColumn('join_date', 'datetime', array(
    'label' => 'Join date',
    'field_mapping' => array('int_year', 'int_month', 'int_day'),
    'glue' => '-',
    'input_type' => 'array',
    'datetime_format' => array('int_year' => 'Y', 'int_month' => 'm', 'int_day' => 'd'),
    'input_field_format' => array(
        'int_year' => array('input_type' => 'string', 'datetime_format' => 'Y'),
        'int_month' => array('input_type' => 'string', 'datetime_format' => 'm'),
        'int_day' => array('input_type' => 'string', 'datetime_format' => 'd')
    )
));

```
