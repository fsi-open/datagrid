UPGRADE FROM 0.9.x to 1.0.0
============================

## Options

### mapping_fields

Before: ``mapping_fields``  
After: ``field_mapping`` 

### order

Before: ``order``  
After: ``display_order`` 


### fields_options

Before: ``fields_options``  
Example code:  
```php
$datagrid->addColumn('active', 'boolean', array(
    'label' => 'Active',
    'editable' => true,
    'true_value' => 'YES',
    'false_value' => 'NO',
    'form_options' => array(
        'active' => array(
            'type' => 'choice',
            'options' => array(
                'choices' => array(
                    0 => 'NO',
                    1 => 'YES'
                )
            )
        )
    )
));

```

After: ``form_options`` and ``form_type``  
Example code:  
```php
$datagrid->addColumn('active', 'boolean', array(
    'label' => 'Active',
    'editable' => true,
    'true_value' => 'YES',
    'false_value' => 'NO',
    'form_options' => array(
        'active' => array(
            'choices' => array(
                0 => 'NO',
                1 => 'YES'
            )
        )
    ),
    'form_type' => array(
        'active' => 'choice'
    )
));
```

### glue

Before: ``glue``  
After: ``value_glue``

### format

Before: ``format``  
After: ``value_format``


### input

Before: ``input``  
After: ``input_format``

### mapping_fields_format 

Before: ``mapping_fields_format``  
After: ``input_field_format``


### actions (action)

Before: ``anchor``  
After: **removed** 

Before: ``parameters``  
After: ``parameters_field_mapping``

Before: ``parameters_values``  
After: ``additional_parameters``  

```php
$datagrid->addColumn('action', 'action', array(
    'label' => 'Actions',
    'field_mapping' => array('id'),
    'actions' => array(
        'edit' => array(
            'anchor' => 'Edit',
            'absolute' => fasle,
            'redirect_uri' => true,
            'route_name' => '_news_edit',
            'parameters' => array('id' => 'id'),
            'parameters_values' => array('constant_param' => '1'),
        ),
        'delete' => array(
            'anchor' => 'Delete',
            'absolute' => fasle,
            'redirect_uri' => true,
            'route_name' => '_news_delete',
            'parameters' => array('id' => 'id'),
            'parameters_values' => array('constant_param' => '1'),
        )
    )
));
```

After: 
```php
$datagrid->addColumn('action', 'action', array(
    'label' => 'Actions',
    'field_mapping' => array('id', 'title'),
    'actions' => array(
        'edit' => array(
            'route_name' => '_news_edit',
            'parameters_field_mapping' => array('id' => 'id'),
            'additional_parameters' => array('constant_param' => '1'),
        ),
        'delete' => array(
            'route_name' => '_news_delete',
            'parameters_field_mapping' => array('id' => 'id'),
            'additional_parameters' => array('constant_param' => '1'),
        )
    )
));
```

*Important! Option anchor has been removed. From now url content is build from 
action name translated with translation_domain.*
