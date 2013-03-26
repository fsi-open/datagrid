# Action Column Type #

Provided by ``DataGrid\Extension\Core\CoreExtension``

## Available Options ##

* ``label`` - string, by default ``[$field->getName()]``
* ``field_mapping`` - **required**, array, by default ``[$field->getName()]``
* ``display_order`` - integer
* ``actions`` - **required**, array

## Options Description ##

**field_mapping** Fields that should be used when data is retrieved from the source. By default there is only one field 
and its taken from the name under what column was registered in grid.

**label** By default label value its taken from name under what column was registered in grid.

**display_order** Optional integer value specifying order of column in grid. Columns in grid are sorted according
  to ascending value of this option. Columns without this option will stay in their natural order (between columns with
  positive and negative values of this option)  

**actions** Array of actions. This option is provided by two extensions and it's up to you to decide which one should be loaded. Each of extensions has defined own keys that are available/required/default inside of **actions** array (see example usage):

For standalone datagrid use ``FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension\ActionColumnExtension``, it provides:

 * ``uri_scheme`` - **required**, string, scheme of an uri
 * ``protocole`` - string, by default ``'http://``
 * ``domain`` - string, domain for an anchor
 * ``redirect_uri`` - string, optional parameter in uri, might be usefull to create return from actions. 

If used with **Symfony**, you should load ``FSi\Component\DataGrid\Extension\Symfony\ColumnTypeExtension\ActionColumnExtension``, it provides:

 * ``route_name`` - **required**, string, route name
 * ``parameters_field_mapping`` - array, parameters for route array('parameter_name' => 'mapping_field_name');
 * ``additional_parameters`` - array, additional parameters values for route not related with field_mapping
 * ``absolute`` - boolean, generate an absolute or relative URL

## Example Usage ##

``` php
<?php

$datagrid->addColumn('actions', 'action', array(
    'label' => 'Actions',
    'mapping_fields' => array('identity'),
    'actions' => array(
        'edit' => array(
            'route_name' => '_edit_news',
            'parameters_field_mapping' => array('id' => 'identity'),
        ),
        'delete' => array(
            'route_name' => '_delete_news',
            'parameters_field_mapping' => array('id' => 'identity'),
        )
    )
));

$datagrid->addColumn('action', 'action', array(
    'label' => 'Actions',
    'field_mapping' => array('id', 'title'),
    'actions' => array(
        'edit' => array(
            'route_name' => '_news_edit',
            'parameters_field_mapping' => array('id' => 'id'),
            'additional_parameters' => array('const_param' => 1)
        ),
        'delete' => array(
            'route_name' => '_news_delete',
            'parameters_field_mapping' => array('id' => 'id'),
            'additional_parameters' => array('const_param' => 1)
        )
    )
));

```

**Important** - ``parameters_field_mapping`` alows each array value to be an Closure function. It can be used to format
router parameter value in specific way. Closure function will be called with 2 arguments:

``function($fieldMappingValues, $rowIndex)``