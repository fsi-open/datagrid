# DataGrid Extension Core #

## Column Types provided by extension ##

- text ``doc/en/columns/text.md``
- number ``doc/en/columns/number.md``
- money ``doc/en/columns/money.md``
- datetime ``doc/en/columns/datetime.md``
- action ``doc/en/columns/action.md``
- boolean ``doc/en/columns/boolean.md``

## Column Type Extensions provided by extension ##

### Action Column Extension ###

Column Type Action by default is empty, this extensions add basic column behavior
used to create urls.

### Default Column Options Extension ###

This extension add options like ``mapping_fields``, ``glue`` or ``label`` to almost
every single column type. It also helps in column view building by imploding
array values with ``glue`` option value.