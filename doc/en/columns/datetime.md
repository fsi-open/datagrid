# DateTime Column Type #

Provided by ``DataGrid\Extension\Core\CoreExtension``

## Available Options ##

* ``datetime_format`` - **required**, string, by default ``Y-m-d H:i:s``
* ``input`` - string
* ``mapping_fields_format`` - array
* ``label`` - string, by default ``[$field->getName()]``
* ``mapping_fields`` - **required**, array, by default ``[$field->getName()]``
* ``glue`` - string
* ``format`` - string
* ``order`` - integer, by default ``0``
* ``editable`` - **required**, boolean, by default ``false``
* ``fields_options`` - array, by default ``array()``
* ``empty_value`` - string|array, by default ``""`` (empty string)

## Options Description ##

**datetime_format** Format of showed date and/or time.

**input** Kind of data you are giving to column (``array``, ``datetime``, ``string``, ``timestamp``) - if no specified column try to guess it.

**mapping_fields_format** Array of formats if you specify more than one field in mapping_fields option (that keys match 'mapping_fields` option keys), otherwise its equal to 'datetime_format' option.

**label** By default label value its taken from name under what column was registred in grid.

**mapping_fields** Fields that should be used when data is retrieved from the source. By default there is only one mapping
field and its taken from name under what column was registred in grid.
Option is useful when you need to implode few fields from object in one column.

**glue** Useful only when you need to implode data from few source object fields into one column.

**format** Useful when you need to format value before passing it to view. Value is formated with php ``sprintf`` function. There should be at least same count of ``mapping_fields`` option
values as placeholders count in format string. This option can be used with ``glue`` option.

**order** Column order.

**editable** If enabled SymfonyForm object is automatically created and passed into view as attribute and you can easly use it to display quick edit.

**fields_options** Array of options for forms, where key is name of field (one of mapping fields) and value is options passed to form
(given as array('name' => '...', 'type' => '...', 'options' => array('...'))).

**empty_value** if value from mapping field is empty (null, !strlen) then it will be replaced with ``empty_value`` option value which by default is empty string. There is also possibility to pass ``empty_value`` to selected ``mapping_fields``.
To do it you just need set ``empty_value`` as array where keys are ``mapping_fields`` keys. If mapping field value is empty and its not included in ``empty_value`` option it will be replaced with empty string.

## Example Usage ##

``` php
<?php

//Shows column with date in 'Y-m-d H:i:s' format.
$datagrid->addColumn('purchuase_date', 'datetime', array(
    'label' => 'Purchuase date',
    'datetime_format' => 'Y-m-d H:i:s',
    'editable' => true,
));

//Shows column with date that is combination of create_date and create_time fields.
$datagrid->addColumn('create_datetime', 'datetime', array(
    'label' => 'Create datetime',
    'mapping_fields' => array('create_date', 'create_time'),
    'glue' => '&nbsp;',
    'editable' => true,
    'input' => 'array',
    'datetime_format' => array('create_date' => 'Y-m-d', 'create_time' => "H:i:s"),
    'mapping_fields_format' => array('create_date' => array('input' => 'datetime'), 'create_time' => array('input' => 'datetime')),
));

//Shows column that value is combination formatted fields create_date and timestamp.
$datagrid->addColumn('create_date_timestamp', 'datetime', array(
    'label' => 'Crate date from timestamp',
    'mapping_fields' => array('create_date', 'timestamp'),
    'glue' => '<br/>',
    'editable' => true,
    'input' => 'array',
    'datetime_format' => array('timestamp' => 'Y-m-d h:i:s', 'create_date' => 'Y-m-d'),
    'mapping_fields_format' => array('create_date' => array('input' => 'datetime'), 'timestamp' => array('input' => 'timestamp'))
));

//Shows date, that is combination of three integer fields.
$datagrid->addColumn('join_date', 'datetime', array(
    'label' => 'Join date',
    'mapping_fields' => array('int_year', 'int_month', 'int_day'),
    'glue' => '-',
    'input' => 'array',
    'datetime_format' => array('int_year' => 'Y', 'int_month' => 'm', 'int_day' => 'd'),
    'mapping_fields_format' => array(
        'int_year' => array('input' => 'string', 'datetime_format' => 'Y'),
        'int_month' => array('input' => 'string', 'datetime_format' => 'm'),
        'int_day' => array('input' => 'string', 'datetime_format' => 'd')
    )
));

```
