# Entity Column Type #

Provided by ``FSi\Component\DataGrid\Extension\Doctrine\DoctrineExtension``

## Available Options ##

* ``label`` - string, by default ``[$field->getName()]``
* ``field_mapping`` - **required**, array, by default ``[$field->getName()]``
* ``value_glue`` - string
* ``empty_value`` - string|array|null, by default ``null``
* ``glue_multiple`` - string, by default ``" "`` (space character)
* ``relation_field`` - **required**, string
* ``display_order`` - integer
* ``editable`` - **required**, boolean, by default ``false``
* ``form_options`` - array, by default ``array()``
* ``form_type`` - array, by default ``array()``

## Options Description ##

**label** By default label value its taken from name under what column was registered in grid.

**field_mapping** Fields that should be used when data is retrieved from the source. By default there is only one 
field and its taken from the name under what column was registered in grid.
Option is useful when you need to implode few fields from object in one column.

**value_format** Useful when you need to format value before passing it to view. Value iformatteded with php ``sprintf`` function. There should be at least same count of ``mapping_fields`` option
values as placeholders count in format string. This option can be used with ``value_glue`` option.  
``format`` option also accept ``\Clousure`` function that should return valid formated string. 

**empty_value** Useful when value is empty and you want override this value.

**display_order** Optional integer value specifying order of column in grid. Columns in grid are sorted according
  to ascending value of this option. Columns without this option will stay in their natural order (between columns with
  positive and negative values of this option)  

**editable** If enabled SymfonyForm object is automatically created and passed into view as attribute and you can easily use it to display quick edit.

**glue_multiple** Glue between objects from relation. Should be used if you want to display relation with many objects and add some separator between them.

**relation_field** Field that relates to other entity (entities).

**editable** If enabled SymfonyForm object is automatically created and passed into view as attribute and you can easily use it to display quick edit.

**form_options** Array of options for forms, where key is name of field (one of field_mapping) and value is 
options passed to form.

**form_type** Array of types for forms, where key is name of field (one of field_mapping) and value is form type.

## Example Usage ##

``` php
<?php

//Input Data: Object (category => Object('id' => 1, 'name' => 'Foo'))

$dataGrid->addColumn('category', 'entity', array(
    'label' => 'News category',
    'relation_field' => 'category',
    'value_format' => '%s %s',
    'field_mapping' => array('id', 'name')
));

//Output: "1 Foo"

//Input Data: Object (category => Object('id' => null, 'name' => 'Foo'))

$dataGrid->addColumn('category', 'entity', array(
    'label' => 'News category',
    'relation_field' => 'category',
    'value_glue' => ' ',
    'empty_value' => 'no',
    'field_mapping' => array('id', 'name')
));

//Output: "no Foo"

//Input Data: Object (category => Object('id' => null, 'name' => null))

$dataGrid->addColumn('category', 'entity', array(
    'label' => 'News category',
    'relation_field' => 'category',
    'value_glue' => ' ',
    'empty_value' => array('id' => 'no', 'name' => 'no'),
    'field_mapping' => array('id', 'name')
));

//Output: "no no"

//Input Data: Object (newses => array(0 => Object('id' => 1, 'name' => 'Foo'), 1 => Object('id' => 2, 'name' => 'Bar')))

$dataGrid->addColumn('newses', 'entity', array(
    'label' => 'Category Newses',
    'relation_field' => 'newses',
    'value_format' => '(%s: %s)',
    'glue_multiple' => ' - ',
    'field_mapping' => array('id', 'name')
));

//Output: "(1: Foo) - (2: Bar)"
```