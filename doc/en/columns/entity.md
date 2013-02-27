# Entity Column Type #

Provided by ``DataGrid\Extension\Symfony\ColumnTypeExtension\ActionColumnExtension``

## Available Options ##

* ``label`` - string, by default ``[$field->getName()]``
* ``mapping_fields`` - **required**, array, by default ``[$field->getName()]``
* ``glue`` - string
* ``glue_multiple`` - string, by default ``" "`` (space character)
* ``relation_field`` - **required**, string
* ``order`` - integer, by default ``0``
* ``editable`` - **required**, boolean, by default ``false``
* ``fields_options`` - array, by default ``array()``

## Options Description ##

**label** By default label value its taken from name under what column was registered in grid.

**mapping_fields** Fields that should be used when data is retrieved from the source. By default there is only one mapping
field and its taken from name under what column was registered in grid.
Option is useful when you need to implode few fields from object in one column.

**glue** Useful only when you need to implode data from few source object fields into one column.

**format** Useful when you need to format value before passing it to view. Value iformatteded with php ``sprintf`` function. There should be at least same count of ``mapping_fields`` option
values as placeholders count in format string. This option can be used with ``glue`` option.  
``format`` option also accept ``\Clousure`` function that should return valid formated string. 

**glue_multiple** Glue between objects from relation. Should be used if you want to display relation with many objects and add some separator between them.

**relation_field** Field that relates to other entity (entities).

**order** Column order.

**editable** If enabled SymfonyForm object is automatically created and passed into view as attribute and you can easily use it to display quick edit.

**fields_options** Array of options for forms, where key is name of field (one of mapping fields) and value is options passed to form
(given as array('name' => '...', 'type' => '...', 'options' => array('...'))).

## Example Usage ##

``` php
<?php

//Input Data: Object (category => Object('id' => 1, 'name' => 'Foo'))

$dataGrid->addColumn('category', 'entity', array(
    'label' => 'News category',
    'relation_field' => 'category',
    'format' => '%s %s',
    'mapping_fields' => array('id', 'name')
));

//Output: "1 Foo"


//Input Data: Object (newses => array(0 => Object('id' => 1, 'name' => 'Foo'), 1 => Object('id' => 2, 'name' => 'Bar')))

$dataGrid->addColumn('newses', 'entity', array(
    'label' => 'Category Newses',
    'relation_field' => 'newses',
    'format' => '(%s: %s)',
    'glue_multiple' => ' - ',
    'mapping_fields' => array('id', 'name')
));

//Output: "(1: Foo) - (2: Bar)"
```