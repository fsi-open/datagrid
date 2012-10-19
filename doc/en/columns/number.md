# Number Column Type #

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
            <code>round_mode</code>
        </td>
        <td>
            Integer (from allowed list)
        </td>
        <td>
            -
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
            <code>precision</code>
        </td>
        <td>
            Integer
        </td>
        <td>
            <code>2</code>
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

**round_mode** One of ``Number::ROUND_HALF_UP``, ``Number::ROUND_HALF_DOWN``, ``Number::ROUND_HALF_EVEN``, or ``Number::ROUND_HALF_ODD``.

**precision** Number of decimal digits to round to. 

**mapping_fields** Fields that should be used when data is retrieved from the source. By default there is only one mapping 
field and its taken from name under what column was registred in grid. 
Option is useful when you need to implode few fields from object in one column. 

**label** By default label value its taken from name under what column was registred in grid. 

**glue** Useful only when you need to implode data from few source object fields into one column. By default its single space character " ". 

**editable** If enabled SymfonyForm object is automatically created and passed into view as attribute and you can easly use it to display quick edit. 
## Example Usage ##

``` php

//Input Data: Object ('value' => 10.123)
$grid->addColumn('price', 'number', array(
    'mapping_fields' => array(
        'value'
    ),
    'round_mode' => Number::ROUND_HALF_UP
    'precision' => 2
));
//Output: 10.12

//Input Data: Object ('value' => 10.126)
$grid->addColumn('price', 'number', array(
    'mapping_fields' => array(
        'value'
    ),
    'round_mode' => Number::ROUND_HALF_UP
    'precision' => 2
));

//Output: 10.13