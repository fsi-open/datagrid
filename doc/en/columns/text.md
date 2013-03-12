# Text Column Type #

Provided by ``DataGrid\Extension\Core\CoreExtension``

## Available Options ##

* ``trim`` - boolean, by default ``false``
* ``label`` - string, by default ``[$field->getName()]``
* ``field_mapping`` - **required**, array, by default ``[$field->getName()]``
* ``value_glue`` - string
* ``display_order`` - integer
* ``editable`` - **required**, boolean, by default ``false``
* ``empty_value`` - string|array, by default ``""`` (empty string)
* ``form_otpions`` - array, by default ``array()``
* ``form_type`` - array, by default ``array()``

## Options Description ##

**trim** By default option is disabled. If enabled value from every single mapping_filed is trimmed before ``buildView`` method will pass it into view object.

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

**editable** If enabled SymfonyForm object is automatically created and passed into view as attribute and you can easily use it to display quick edit.

**form_options** Array of options for forms, where key is name of field (one of field_mapping) and value is 
options passed to form.

**form_type** Array of types for forms, where key is name of field (one of field_mapping) and value is form type.

## Example Usage ##

``` php
<?php

//Input Data: Object ('name' => 'Norbert', 'surname' => 'Orzechowicz')
$grid->addColumn('name_surname', 'text', array(
    'field_mapping' => array(
        'name',
        'surname'
    )
));
//Output: "Norbert Orzechowicz"

//Input Data: Object ('name' => 'Norbert', 'surname' => 'Orzechowicz')
$grid->addColumn('name_surname', 'text', array(
    'field_mapping' => array(
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
    'field_mapping' => array(
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