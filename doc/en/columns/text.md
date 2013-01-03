# Text Column Type #

Provided by ``DataGrid\Extension\Core\CoreExtension``

## Available Options ##

* ``trim`` - boolean, by default ``false``
* ``label`` - string, by default ``[$field->getName()]``
* ``mapping_fields`` - **required**, array, by default ``[$field->getName()]``
* ``glue`` - string
* ``order`` - integer, by default ``0``
* ``editable`` - **required**, boolean, by default ``false``
* ``fields_options`` - array, by default ``array()``

## Options Description ##

**trim** By default option is disabled. If enabled value from every single mapping_filed is trimmed before ``buildView`` method will pass it into view object.

**label** Label for column. 

**mapping_fields** Fields that should be used when data is retrieved from the source. By default there is only one mapping 
field and its taken from name under what column was registred in grid. 
Option is useful when you need to implode few fields from object in one column. 

**label** By default label value its taken from name under what column was registred in grid. 

**glue** Useful only when you need to implode data from few source object fields into one column.

**format** Useful when you need to format value before passing it to view. Value is formated with php ``sprintf`` function. There should be at least same count of ``mapping_fields`` option 
values as placeholders count in format string. This option can be used with ``glue`` option.

**order** Column order.

**editable** If enabled SymfonyForm object is automatically created and passed into view as attribute and you can easly use it to display quick edit.

**fields_options** Array of options for forms, where key is name of field (one of mapping fields) and value is options passed to form
(given as array('name' => '...', 'type' => '...', 'options' => array('...'))).

## Example Usage ##

``` php
<?php

//Input Data: Object ('name' => 'Norbert', 'surname' => 'Orzechowicz')
$grid->addColumn('name_surname', 'text', array(
    'mapping_fields' => array(
        'name',
        'surname'
    )
));
//Output: "Norbert Orzechowicz"

//Input Data: Object ('name' => 'Norbert', 'surname' => 'Orzechowicz')
$grid->addColumn('name_surname', 'text', array(
    'mapping_fields' => array(
        'name',
        'surname'
    ),
    'glue' => '-'
));
//Output: "Norbert-Orzechowicz"

//Input Data: Object ('name' => ' Norbert ')
$grid->addColumn('name', 'text', array('trim' => true));
//Output: "Norbert"

//Input Data: Object ('name' => 'Norbert')
$grid->addColumn('name_column', 'text', array(
    'mapping_fields' => array(
        'name'
    )
));
//Output: "Norbert"

//Input Data: Object ('name' => 'Norbert')
$grid->addColumn('name', 'text', array(
    'editable' => true
));
// $form = $column->getAttribute('form') - Symfony Form Object
```