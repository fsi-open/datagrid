# Money Column Type #

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
            <code>PHP_ROUND_HALF_UP</code>
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
            <code>decimals</code>
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
            <code>dec_point</code>
        </td>
        <td>
            String
        </td>
        <td>
            <code>.</code>
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
            <code>thousands_sep</code>
        </td>
        <td>
            String
        </td>
        <td>
            <code>,</code>
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
            <code>value_currency_separator</code>
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
            <code>currency_field</code>
        </td>
        <td>
            String
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
            <code>currency</code>
        </td>
        <td>
            Float
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

**decimals** The number of decimal points.

**dec_point** Decimal point. 

**thousands_sep** Thousands separator.

**value_currency_separator** Separator between currency and value.

**currency_field** Field to take actual currency from. Mandatory if currency_value not specified.

**currency** Currency. Mandatory if currency_field not specified.

**mapping_fields** Fields that should be used when data is retrieved from the source. By default there is only one mapping 
field and its taken from name under what column was registred in grid. 
Option is useful when you need to implode few fields from object in one column. 

**label** By default label value its taken from name under what column was registred in grid. 

**glue** Useful only when you need to implode data from few source object fields into one column. By default its single space character " ". 

**editable** If enabled SymfonyForm object is automatically created and passed into view as attribute and you can easly use it to display quick edit.

## Example Usage ##

``` php

//This cofiguration will show price from field 'price' and its currency from 'currency' field.
$datagrid->addColumn('productprice', 'money', array(
    'label' => 'Product price', 
    'mapping_fields' => array('price', 'currency'),
    'currency_field' => 'currency',
    'value_currency_separator' => ' - ',
    'glue' => '<br />',
));

//This cofiguration will show price from two fields ('price' and 'promo_price') with arbitrary USD currency.
$datagrid->addColumn('productprice', 'money', array(
    'label' => 'Product price', 
    'mapping_fields' => array('price', 'promo_price'),
    'currency' => 'USD',
    'value_currency_separator' => '$ ',
    'dec_point' => ',',
));

```