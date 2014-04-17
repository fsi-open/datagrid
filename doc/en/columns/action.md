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
 * ``protocol`` - string, by default ``'http://``
 * ``domain`` - string, domain for an anchor
 * ``redirect_uri`` - string, optional parameter in uri, might be usefull to create return from actions. 

If used with **Symfony**, you should load ``FSi\Component\DataGrid\Extension\Symfony\ColumnTypeExtension\ActionColumnExtension``, it provides:

 * ``route_name`` - **required**, string, route name
 * ``url_attr`` - array, attributes with values passed to url tag <a %url_attr%>%content%</a>, href is also inside of url_attr and you can change it.
 * ``content`` - string, content of url <a href="#">%content%</a>
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
    'field_mapping' => array('id', 'title', 'active'),
    'actions' => array(
        'edit' => array(
            'url_attr' => array(
                'class' => 'btn btn-warning btn-small-horizontal',
                'title' => 'datagrid.action.edit'
            ),
            'content' => '<span class="icon-eject icon-white"></span>',
            'route_name' => '_news_edit',
            'parameters_field_mapping' => array('id' => 'id'),
            'additional_parameters' => array('const_param' => 1)
        ),
        'delete' => array(
            'url_attr' => array(
                'class' => 'btn btn-danger btn-small-horizontal',
                'title' => 'crud.list.datagrid.action.delete'
            ),
            'content' => '<span class="icon-trash icon-white"></span>',
            'route_name' => '_news_delete',
            'parameters_field_mapping' => array('id' => 'id'),
            'additional_parameters' => array('const_param' => 1)
        ),
        'activation' => array(
            'url_attr' => function($values, $index) {
                return array(
                    'class' => $values['active']
                        ? 'btn btn-small-horizontal'
                        : 'btn btn-success btn-small-horizontal',
                    'title' => $values['active']
                        ? 'crud.list.datagrid.action.disable'
                        : 'crud.list.datagrid.action.active'
                );
            },
            'content' => function($values, $index) {
                return $values['active']
                    ? '<span class="icon-off"></span>'
                    : '<span class="icon-ok icon-white"></span>';
            },
            'route_name' => '_news_activation',
            'parameters_field_mapping' => array(
                'id' => function($values, $index) {
                    return $index;
                }
            ),
            'additional_parameters' => array(
                'element' => $this->getId()
            )
        )
    )
));

```

**Important** - ``parameters_field_mapping``, ``url_attr`` and ``content`` alows \Closure function as value. It can be used to format
option value depending on the field_mapping values. Closure function will be called with 2 arguments:

``function($fieldMappingValues, $rowIndex)``