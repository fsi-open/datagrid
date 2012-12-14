# DateTime Column Type #

Provided by ``DataGrid\Extension\Core\CoreExtension``

## Available Options ##

* ``format`` - **required**, string, by default ``Y-m-d H:i:s``
* ``input`` - string
* ``mapping_fields_format`` - array
* ``label`` - string, by default ``[$field->getName()]``
* ``mapping_fields`` - **required**, array, by default ``[$field->getName()]``
* ``glue`` - **required**, string, by default ``" "`` (space character)
* ``order`` - integer, by default ``0``
* ``editable`` - **required**, boolean, by default ``false``
* ``fields_options`` - array, by default ``array()``

## Options Description ##

**format** Format of showed date and/or time.

**input** Kind of data you are giving to column (``array``, ``datetime``, ``string``, ``timestamp``) - if no specified column try to guess it.

**mapping_fields_format** Array of formats if you specify more than one field in mapping_fields option (that keys match 'mapping_fields` option keys), otherwise its equal to 'format' option.

**label** By default label value its taken from name under what column was registred in grid. 

**mapping_fields** Fields that should be used when data is retrieved from the source. By default there is only one mapping 
field and its taken from name under what column was registred in grid. 
Option is useful when you need to implode few fields from object in one column. 

**glue** Useful only when you need to implode data from few source object fields into one column. By default its single space character " ".

**order** Column order. 

**editable** If enabled SymfonyForm object is automatically created and passed into view as attribute and you can easly use it to display quick edit.

**fields_options** Array of options for forms, where key is name of field (one of mapping fields) and value is options passed to form
(given as array('name' => '...', 'type' => '...', 'options' => array('...'))).

## Example Usage ##

``` php
<?php

//Shows column with date in 'Y-m-d H:i:s' format.
$datagrid->addColumn('purchuase_date', 'datetime', array(
    'label' => 'Purchuase date', 
    'format' => 'Y-m-d H:i:s',
    'editable' => true,
));

//Shows column with date that is combination of create_date and create_time fields.
$datagrid->addColumn('create_datetime', 'datetime', array(
    'label' => 'Create datetime',
    'mapping_fields' => array('create_date', 'create_time'),
    'glue' => '&nbsp;',
    'editable' => true,
    'input' => 'array',
    'format' => array('create_date' => 'Y-m-d', 'create_time' => "H:i:s"),
    'mapping_fields_format' => array('create_date' => array('input' => 'datetime'), 'create_time' => array('input' => 'datetime')),
));

//Shows column that value is combination formatted fields create_date and timestamp.
$datagrid->addColumn('create_date_timestamp', 'datetime', array(
    'label' => 'Crate date from timestamp',
    'mapping_fields' => array('create_date', 'timestamp'),
    'glue' => '<br/>',
    'editable' => true,
    'input' => 'array',
    'format' => array('timestamp' => 'Y-m-d h:i:s', 'create_date' => 'Y-m-d'),
    'mapping_fields_format' => array('create_date' => array('input' => 'datetime'), 'timestamp' => array('input' => 'timestamp'))
));

//Shows date, that is combination of three integer fields.
$datagrid->addColumn('join_date', 'datetime', array(
    'label' => 'Join date',
    'mapping_fields' => array('int_year', 'int_month', 'int_day'),
    'glue' => '-',
    'input' => 'array',
    'format' => array('int_year' => 'Y', 'int_month' => 'm', 'int_day' => 'd'),
    'mapping_fields_format' => array(
        'int_year' => array('input' => 'string', 'format' => 'Y'),
        'int_month' => array('input' => 'string', 'format' => 'm'),
        'int_day' => array('input' => 'string', 'format' => 'd')
    )
));

```
