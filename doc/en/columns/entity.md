# Entity Column Type #

Provided by ``DataGrid\Extension\Symfony\ColumnTypeExtension\ActionColumnExtension``

## Available Options ##

* ``label`` - string, by default ``[$field->getName()]``
* ``mapping_fields`` - **required**, array, by default ``[$field->getName()]``
* ``glue`` - **required**, string, by default ``" "`` (space character)
* ``glue_multiple`` - string, by default ``" "`` (space character)
* ``relation_field`` - **required**, string
* ``order`` - integer, by default ``0``
* ``editable`` - **required**, boolean, by default ``false``
* ``fields_options`` - array, by default ``array()``

## Options Description ##

**label** By default label value its taken from name under what column was registred in grid. 

**mapping_fields** Fields that should be used when data is retrieved from the source. By default there is only one mapping 
field and its taken from name under what column was registred in grid. 
Option is useful when you need to implode few fields from object in one column. 

**glue** Useful only when you need to implode data from few source object fields into one column. By default its single space character " ". 

**glue_multiple** Glue between many entities. (Similar to 'glue' option, but 'glue' is between many different fields in one colum.)

**relation_field** Field that relates to other entity (entities).

**order** Column order.

**editable** If enabled SymfonyForm object is automatically created and passed into view as attribute and you can easly use it to display quick edit.

**fields_options** Array of options for forms, where key is name of field (one of mapping fields) and value is options passed to form
(given as array('name' => '...', 'type' => '...', 'options' => array('...'))).

## Example Usage ##

``` php
<?php

$dataGrid->addColumn('category', 'entity', array(
    'label' => 'Product category',
    'relation_field' => 'category',
    'mapping_fields' => array('id', 'name'),
    'editable' => true,
));

```