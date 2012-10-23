# Action Column Type #

Provided by ``DataGrid\Extension\Core\CoreExtension``

## Available Options ##

<table>
    <tr>
        <td>
            <b>Option Name</b>
        </td>
        <td>
            <b>Type</b>
        </td>
        <td>
            <b>Default value</b>
        </td>
        <td>
            <b>Required</b>
        </td>
        <td>
            <b>Provided by (extension name)</b>
        </td>
    </tr>
    <tr>
        <td>
            <code>actions</code>
        </td>
        <td>
            Array
        </td>
        <td>
            -
        </td>
        <td>
            yes
        </td>
        <td>
            -
        </td>
    </tr>
    <tr>
        <td>
            <code>mapping_fields</code>
        </td>
        <td>
            Array
        </td>
        <td>
            <code>[$column->getName()]</code>
        </td>
        <td>
            no
        </td>
        <td>
            Core
        </td>
    </tr>
    <tr>
        <td>
            <code>label</code>
        </td>
        <td>
            String
        </td>
        <td>
            <code>$column->getName()</code>
        </td>
        <td>
            no
        </td>
        <td>
            Core
        </td>
    </tr>
    <tr>
        <td>
            <code>glue</code>
        </td>
        <td>
            String
        </td>
        <td>
            <code>" "</code> <i>(space character)</i>
        </td>
        <td>
            no
        </td>
        <td>
            Core
        </td>
    </tr>
    <tr>
        <td>
            <code>editable</code>
        </td>
        <td>
            Boolean
        </td>
        <td>
            <code>false</code>
        </td>
        <td>
            no
        </td>
        <td>
            Symfony
        </td>
    </tr>
</table>

## Options Description ##

**actions** Array of actions. Each key of this array is name of action, which is array with keys:
- **uri_scheme** Scheme of an uri.
- **anchor** Name of anchor.
- **protocole** Protocol for anchor.
- **domain** Domain for anchor.
- **name** Name for anchor.
- **route_name** Name of route, that anchor will point to.
- **parameters** Parameters for route.

**mapping_fields** Fields that should be used when data is retrieved from the source. By default there is only one mapping 
field and its taken from name under what column was registred in grid. 
Option is useful when you need to implode few fields from object in one column. 

**label** By default label value its taken from name under what column was registred in grid. 

**glue** Useful only when you need to implode data from few source object fields into one column. By default its single space character " ". 

**editable** If enabled SymfonyForm object is automatically created and passed into view as attribute and you can easly use it to display quick edit.

## Example Usage ##

``` php

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