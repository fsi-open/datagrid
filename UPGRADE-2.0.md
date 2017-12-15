# This is a guide on how to update from version 1.x to 2.0

## Use datagrid-bundle to utilize Symfony extension

Since it was moved to [datagrid-bundle](https://github.com/fsi-open/datagrid-bundle),
you will need to install it in order to be able to use the extension.

## Use null in boolean column only when you mean it

If any of your boolean columns can contain `null` then an empty string will be displayed
instead of value passed as `false_value` option. You should either ensure the column does
not contain `null` or that it adheres to the new behaviour.

## Upgrade to PHP 7.1 or higher

In order to use this library, you will need PHP 7.1 or higher.

## Add argument and return typehints

There is quite a big chance that your custom column type or column type extension
need to have added typehints to most of its methods accordingly to extended
class or implemented interface.
