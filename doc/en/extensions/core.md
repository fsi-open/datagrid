# DataGrid Extension Core #

## Column Types provided by extension ##

- [text](https://github.com/fsi-open/datagrid/blob/master/doc/en/columns/text.md)
- [number](https://github.com/fsi-open/datagrid/blob/master/doc/en/columns/number.md)
- money
- datatime
- action

## Column Type Extensions provided by extension ##

### Action Column Extension ###

Column Type Action by defualt is empty, this extensions add basic column behavior 
used to create urls. 

### Default Column Options Extension ###

This extension add options like ``mapping_fields``, ``glue`` or ``label`` to almost
every single column type. It also helps in column view building by imploding 
array values with ``glue`` option value.