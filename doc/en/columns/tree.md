# Tree Column Type #

Provided by ``DataGrid\Extension\Gedmo\GedmoDoctrineExtension``

## Available Options ##

* ``em`` - string
* ``label`` - string, by default ``[$field->getName()]``
* ``field_mapping`` - **required**, array, by default ``[$field->getName()]``
* ``value_glue`` - string
* ``display_order`` - integer
* ``editable`` - **required**, boolean, by default ``false``
* ``empty_value`` - string|array, by default ``""`` (empty string)
* ``form_options`` - array, by default ``[]``
* ``form_type`` - array, by default ``[]``

## Options Description ##

**em** Name of entity manager, if null column takes default one.

**label** By default label value its taken from name under what column was registered in grid.

**field_mapping** Fields that should be used when data is retrieved from the source. By default there is only one
field and its taken from the name under what column was registered in grid.
Option is useful when you need to implode few fields from object in one column.

**value_glue** Useful only when you need to implode data from few source object fields into one column.

**value_format** Useful when you need to format value before passing it to view. Value formatted with php ``sprintf`` function. There should be at least same count of ``mapping_fields`` option
values as placeholders count in format string. This option can be used with ``value_glue`` option.
``format`` option also accept ``\Closure`` function that should return valid formatted string.

**display_order** Optional integer value specifying order of column in grid. Columns in grid are sorted according
  to ascending value of this option. Columns without this option will stay in their natural order (between columns with
  positive and negative values of this option)

**editable** If enabled SymfonyForm object is automatically created and passed into view as attribute and you can easily use it to display quick edit.

**form_options** Array of options for forms, where key is name of field (one of field_mapping) and value is
options passed to form.

**form_type** Array of types for forms, where key is name of field (one of field_mapping) and value is form type.

## Example Usage ##

``` php
<?php

$dataGrid->addColumn('item', 'gedmo_tree', [
    'label' => 'Item',
    'editable' => true,
]);

```

Difference between ``tree`` and ``text`` column is that cellView of ``tree`` column type has few additional
attributes.
List of attributes and example of usage below:

**Attributes**
* **id** - element id
* **parent** - (optional) parent id
* **root** - (optional) root id of element
* **left** - element left position
* **right** - element right position
* **level** - element nesting level
* **children** - element children count

**Usage** (in Twig)

```
{# datagrid_column_type_{column_type}_cell #}
{% block datagrid_column_type_gedmo_tree_cell %}
    <td>
        <div>
            {% spaceless %}
            <div
                {% if cell.getAttribute('parent') is not null %} data-parent="{{ cell.getAttribute('parent') }}"{% endif %}
                {% if cell.getAttribute('root') is not null %} data-root="{{ cell.getAttribute('root') }}"{% endif %}
                {% if cell.getAttribute('level') is not null %} data-level="{{ cell.getAttribute('level') }}" class="tree-level-{{ cell.getAttribute('level') }}"{% endif %}
                data-children="{{ cell.getAttribute('children') }}"
            >
            {% endspaceless %}
            {{ cell.value|raw }}
            </div>
            {{ datagrid_column_cell_form_widget(cell) }}
        </div>
    </td>
{% endblock %}
```
