# Entity Column Type #

Provided by ``DataGrid\Extension\Symfony\ColumnTypeExtension\ActionColumnExtension``

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
            <code>relation_field</code>
        </td>
        <td>
            String
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
            <code>glue_multiple</code>
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

**relation_field** Field that relates to other entity (entities).

**glue_multiple** Glue between many entities. (Similar to 'glue' option, but 'glue' is between many different fields in one colum.)

**mapping_fields** Fields that should be used when data is retrieved from the source. By default there is only one mapping 
field and its taken from name under what column was registred in grid. 
Option is useful when you need to implode few fields from object in one column. 

**label** By default label value its taken from name under what column was registred in grid. 

**glue** Useful only when you need to implode data from few source object fields into one column. By default its single space character " ". 

**editable** If enabled SymfonyForm object is automatically created and passed into view as attribute and you can easly use it to display quick edit.

## Example Usage ##

``` php

$dataGrid->addColumn('category', 'entity', array(
    'label' => 'Product category',
    'relation_field' => 'category',
    'mapping_fields' => array('id', 'name'),
    'editable' => true,
));

```