# Boolean Column Type #

Provided by ``DataGrid\Extension\Core\CoreExtension``

## Available Options ##

* ``label`` - string, by default ``[$field->getName()]``
* ``field_mapping`` - **required**, array, by default ``[$field->getName()]``
* ``value_glue`` - string
* ``value_format`` - string
* ``display_order`` - integer
* ``empty_value`` - string|array, by default ``""`` (empty string)
* ``true_value`` - what should be stetted as cell view value if mapping_fields give true values.
* ``false_value`` - what should be stetted as cell view value if mapping_fields give false values.
* ``editable`` - **required**, boolean, by default ``false``
* ``form_options`` - array, by default ``array()``
* ``form_type`` - array, by default ``array()``

## Options Description ##

**label** Label for column.

**field_mapping** Fields that should be used when data is retrieved from the source. By default there is only one
field and its taken from the name under what column was registered in grid.
Option is useful when you need to implode few fields from object in one column.

**label** By default label value its taken from name under what column was registered in grid.

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

**empty_value** if value from field_mapping is empty (null, !strlen) then it will be replaced with ``empty_value`` option value which by default is empty string. There is also possibility to pass ``empty_value`` to selected ``mapping_fields``.
To do it you just need set ``empty_value`` as array where keys are ``mapping_fields`` keys. If mapping field value is empty and its not included in ``empty_value`` option it will be replaced with empty string.


## Example Usage ##

``` php
<?php

//Input Data1: Object ('available' => false)
//Input Data1: Object ('available' => true)
//Input Data3: Object ('available' => null)
$datagrid->addColumn('available', 'boolean', array(
    'editable' => false,
    'true_value' => '<i class="icon-ok"></i>',
    'false_value' => '<i class="icon-off"></i>',
    'form_options' => array(
        'available' => array(
            'required' => false
        )
    )
    'form_type' => array(
        'available' => 'checkbox'
    )
));
//Output1: "<i class="icon-off"></i>"
//Output2: "<i class="icon-ok"></i>'"
//Output3: ""

//Input Data1: Object ('accessible' => true, 'visible' => true)
//Input Data2: Object ('accessible' => true, 'visible' => false)
//Input Data3: Object ('accessible' => false, 'visible' => false)
$datagrid->addColumn('available', 'boolean', array(
    'field_mapping' => array('accessible', 'visible')
    'editable' => false,
    'true_value' => '<i class="icon-ok"></i>',
    'false_value' => '<i class="icon-off"></i>',
    'form_options' => array(
        'available' => array(
            'required' => false
        )
    ),
    'form_type' => array(
        'available' => 'checkbox'
    )
));
//Output1: "<i class="icon-ok"></i>'"
//Output2: "<i class="icon-off"></i>"
//Output3: "<i class="icon-off"></i>"
