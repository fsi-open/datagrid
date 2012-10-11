# Text Column Type #

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
            <code>trim</code>
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

**trim** By default option is disabled. If eneabled value from every single mapping_filed is trimmed before ``buildView`` method will pass it into view object. 

**mapping_fields** Fields that should be used when data is retrieved from the source. By default there is only one mapping 
field and its taken from name under what column was registred in grid. 
Option is useful when you need to implode few fields from object in one column. 

**label** By default label value its taken from name under what column was registred in grid. 

**glue** Useful only when you need to implode data from few source object fields into one column. By default its single space character " ". 

**editable** If eneabled SymfonyForm object is automatically created and passed into view as attribute and you can easly use it to display quick edit. 

## Example Usage ##

``` php
//Input Data: Object ('name' => 'Norbert', 'surname' => 'Orzechowicz')
$grid->addColumn('name_surname', 'text', array(
    'mapping_fields' => array(
        'name',
        'surname'
    )
));
//Output: "Norbert Orzechowicz"

//Input Data: Object ('name' => 'Norbert', 'surname' => 'Orzechowicz')
$grid->addColumn('name_surname', 'text', array(
    'mapping_fields' => array(
        'name',
        'surname'
    ),
    'glue' => '-'
));
//Output: "Norbert-Orzechowicz"

//Input Data: Object ('name' => ' Norbert ')
$grid->addColumn('name', 'text', array('trim' => true));
//Output: "Norbert"

//Input Data: Object ('name' => 'Norbert')
$grid->addColumn('name_column', 'text', array(
    'mapping_fields' => array(
        'name'
    )
));
//Output: "Norbert"

//Input Data: Object ('name' => 'Norbert')
$grid->addColumn('name', 'text', array(
    'editable' => true
));
// $form = $column->getAttribute('form') - Symfony Form Object
```