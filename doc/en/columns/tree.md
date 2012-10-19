# Tree Column Type #

Provided by ``DataGrid\Extension\Gedmo\GedmoDoctrineExtension``

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
            <code>em</code>
        </td>
        <td>
            Object
        </td>
        <td>
            <code>null</code>
        </td>
        <td>
            no
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

**em** Name of entity manager, if null column takes default one.

**label** By default label value its taken from name under what column was registred in grid. 

**glue** Useful only when you need to implode data from few source object fields into one column. By default its single space character " ". 

**editable** If enabled SymfonyForm object is automatically created and passed into view as attribute and you can easly use it to display quick edit. 

## Example Usage ##

``` php

$dataGrid->addColumn('item', 'gedmo.tree', array(
    'label' => 'Item', 
    'editable' => true,
));

```