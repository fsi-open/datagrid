# Text Column Type #

Provided by ``DataGrid\Extension\Core\CoreExtension``

## Available Options ##

* ``true_value`` - what should be stetted as cell view value if mapping_fields give true values.
* ``false_value`` - what should be stetted as cell view value if mapping_fields give false values.
* ``label`` - string, by default ``[$field->getName()]``
* ``mapping_fields`` - **required**, array, by default ``[$field->getName()]``
* ``glue`` - string
* ``order`` - integer, by default ``0``
* ``editable`` - **required**, boolean, by default ``false``
* ``fields_options`` - array, by default ``array()``
* ``empty_value`` - string|array, by default ``""`` (empty string)

## Options Description ##

**label** Label for column.

**mapping_fields** Fields that should be used when data is retrieved from the source. By default there is only one mapping
field and its taken from name under what column was registered in grid.
Option is useful when you need to implode few fields from object in one column.

**label** By default label value its taken from name under what column was registered in grid.

**glue** Useful only when you need to implode data from few source object fields into one column.

**format** Useful when you need to format value before passing it to view. Value iformatteded with php ``sprintf`` function. There should be at least same count of ``mapping_fields`` option
values as placeholders count in format string. This option can be used with ``glue`` option.  
``format`` option also accept ``\Clousure`` function that should return valid formated string. 

**order** Column order.

**editable** If enabled SymfonyForm object is automatically created and passed into view as attribute and you can easily use it to display quick edit.

**fields_options** Array of options for forms, where key is name of field (one of mapping fields) and value is options passed to form
(given as array('name' => '...', 'type' => '...', 'options' => array('...'))).

**empty_value** if value from mapping field is empty (null, !strlen) then it will be replaced with ``empty_value`` option value which by default is empty string. There is also possibility to pass ``empty_value`` to selected ``mapping_fields``.
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
    'fields_options' => array(
        'available' => array(
            'type' => 'checkbox',
            'options' => array(
                'required' => false
            )
        )
    )
));
//Output1: "<i class="icon-off"></i>"
//Output2: "<i class="icon-ok"></i>'"
//Output3: "<i class="icon-off"></i>"

//Input Data1: Object ('accessible' => true, 'visible' => true)
//Input Data2: Object ('accessible' => true, 'visible' => false)
//Input Data3: Object ('accessible' => false, 'visible' => false)
$datagrid->addColumn('available', 'boolean', array(
    'mapping_fields' => array('accessible', 'visible')
    'editable' => false,
    'true_value' => '<i class="icon-ok"></i>',
    'false_value' => '<i class="icon-off"></i>',
    'fields_options' => array(
        'available' => array(
            'type' => 'checkbox',
            'options' => array(
                'required' => false
            )
        )
    )
));
//Output1: "<i class="icon-ok"></i>'"
//Output2: "<i class="icon-off"></i>"
//Output3: "<i class="icon-off"></i>"