# DataGrid Extension SymfonyForm #

## Column Types provided by extension ##

none

## Column Type Extensions provided by extension ##

### Form Extension ###

This extensions is loaded into almost all column types. It allows you to set 
``editable`` otpion in column type. <br>
If ``editable`` option value is ``true`` the SymfonyForm is being created from
column ``mapping_fields``.

**Usage example**

```php
$grid->addColumn('email', 'text', array(
            'editable' => true  
        )
    )
```

Symfony Form View object is available as ColumnView attribute. 

```html
<div>
    <div id="hidden_form">
        <?php 
            $formView = column->GetAttribute('form'); 
            // here you should render form view. 
        ?>
    </div>
    <div id="column_value">
        <?php echo $column->getValue(); ?>
    </div>
</div>
```

**Handling Requests in DataGrid in Symfony2**

```php

if ($request->getMethod() == 'POST') {
    $grid->bindData($request);
    $this->getDoctrine()->getEntityManager()->flush();
}

```

In most cases this is enough to create grid with some editable fields. 
But sometimes there are situations when you need to pass additional options to form elements. 
This can be achieved with ``fields_options`` option that pass options into 
form elements. 

**Example**
```php
$grid->addColumn('user_email', 'text', array(
            'mapping_fields' => array('user_email'), //in this case this parameter is optional because column name is same as mapping_field
            'editable' => true,
            'fields_options' => array(
                'user_email' => array( //each array key must exist in mapping_fields
                    'type' => 'email',
                    'options => array(
                        'required' => true
                    )
                )
            )
        )
    )
```

This will add column into form with ``type`` email and addition option ``required``

```php
$form->add('user_email', 'email', array('required' => true));
```