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

**actions** Array of actions. Each action has following options:

 * ``uri_scheme`` - **required**, string, scheme of an uri
 * ``protocol`` - string, by default ``'http://``
 * ``domain`` - string, domain for an anchor
 * ``redirect_uri`` - string, optional parameter in uri, might be useful to create return from actions.

## Example Usage ##

``` php
<?php

$datagrid->addColumn('actions', 'action', [
    'label' => 'Actions',
    'mapping_fields' => ['identity'],
    'actions' => [
        edit' => [
            'uri_scheme' => '/test/%s',
            'domain' => 'fsi.pl',
            'protocol' => 'https://',
            'redirect_uri' => 'http://onet.pl/'
        ],
    ]
]);

```

**Important** - ``parameters_field_mapping``, ``url_attr`` and ``content`` alows \Closure function as value. It can be used to format
option value depending on the field_mapping values. Closure function will be called with 2 arguments:

``function($fieldMappingValues, $rowIndex)``
