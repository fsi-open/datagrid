# Action Column Type #

Provided by ``DataGrid\Extension\Core\CoreExtension``

## Available Options ##

* ``label`` - string, by default ``[$field->getName()]``
* ``mapping_fields`` - **required**, array, by default ``[$field->getName()]``
* ``order`` - integer, by default ``0``
* ``actions`` - **required**, array

## Options Description ##

**mapping_fields** Fields that should be used when data is retrieved from the source. By default there is only one mapping 
field and its taken from name under what column was registred in grid. 
Option is useful when you need to implode few fields from object in one column. 

**label** By default label value its taken from name under what column was registred in grid. 

**order** Column order. 

**actions** Array of actions. This option is provided by two extensions and it's up to you to decide which one should be loaded. Each of extensions has defined own keys that are available/required/default inside of **actions** array (see example usage):

For standalone datagrid use ``FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension\ActionColumnExtension``, it provides: 

 * ``uri_scheme`` - **required**, string, scheme of an uri
 * ``anchor`` - **required**, string, name of an anchor
 * ``protocole`` - string, by default ``'http://``
 * ``domain`` - string, domain for an anchor
 * ``name`` - string, name for an anchor

If used with **Symfony**, you should load ``FSi\Component\DataGrid\Extension\Symfony\ColumnTypeExtension\ActionColumnExtension``, it provides:

 * ``parameters`` - array, parameters for route
 * ``parameters_values`` - array, parameters values for route
 * ``anchor`` - **required**, string, anchor name
 * ``route_name`` - **required**, string, route name
 * ``absolute`` - boolean, whether to generate an absolute URL

## Example Usage ##

``` php
<?php

//Shows column with date in 'Y-m-d H:i:s' format.
$datagrid->addColumn('actions', 'action', array(
    'label' => 'Actions',
    'mapping_fields' => array('id'),
    'actions' => array(
        'edit' => array(
            'anchor' => 'Edit',
            'route_name' => '_edit_news',
            'parameters' => array('id' => 'id'),
        ),
        'delete' => array(
            'anchor' => 'Delete',
            'route_name' => '_delete_news',
            'parameters' => array('id' => 'id'),
        )
    )
));

```