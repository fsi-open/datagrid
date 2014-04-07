# FSi DataGrid Component #

List component that displays the items from collection (data source) by using
special column objects.
Displaying objects list is one of the most common tasks in web applications and
probably the easiest one, so you could ask how this component can help you?

FSi DataGrid Component allows you to create one action that handle
display all kind of lists in your application without duplicating code.
It can be very useful in all kind of admin panel generators.

## Basic Usage ##

The sample code shows more than a thousand words so lets start!

Before you can display data you need to add columns into DataGrid component.
Columns pull data from objects using ``DataMappers`` (They will be described later).

Imagine a situation where we need to display a list of news. List must contains
such information as:

- News ID
- News Title
- Author Name, Surname and email
- Publication Date
- Some basic actions like edit and delete

So we should get a table similar to the following:

Id  | News title | Author | Publication date | Actions
--- | ---------- | ------ | ---------------- | -------
1 | First News | Norbert Orzechowicz norbert@fsi.pl | 2012.05.01 15:13:52 | *Edit Delete*
2 | Second News | Norbert Orzechowicz norbert@fsi.pl | 2012.05.06 15:13:52 | *Edit Delete*

And here is our News object.

``` php
<?php

namespace FSi\SiteBundle\Entity\News;

class News
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $author_name;

    /**
     * @var string
     */
    protected $author_surname;

    /**
     * @var string
     */
    protected $author_email;

    /**
     * @var DateTime
     */
    protected $publication_date;

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getAuthorName()
    {
        return $this->author_name;
    }

    public function getAuthorSurname()
    {
        return $this->author_surname;
    }

    public function getAuthorEmail()
    {
        return $this->author_email;
    }

    public function getPublicationDate()
    {
        return $this->publication_date;
    }

    /*
    Here we should have setters
    public function setTitle($title)
    public function setAuthorName($name)
    ...
    */
}
```

Suppose that we use Doctrine ORM and we can get a list of objects as follows:

``` php
<?php

$data = $this->getEntityManager()->getRepository('FSi\SiteBundle\Entity\News')->findAll();
```

So if we have data we need to build grid and pass data into it.

We assume that datagrid.factory is a service with DataGridFactory object instance.
More about creating DataGridFactory and loading columns into it will be described later.

``` php
<?php

$grid = $this->get('datagrid.factory')->createDataGrid();
```

DataGrid::addColumn($name, $type = 'text', $options = array());

``` php
<?php

$grid->addColumn('id', 'number', array('label' => 'Id'))
     ->addColumn('title', 'text', array('label' => 'News Title'))
     ->addColumn('author', 'text', array(
            'field_mapping' => array(
                'author_name',
                'author_surname',
                'author_email'
            ),
            'value_glue' => '<br/>',
            'label' => 'Author'
        )
    )
    ->addColumn('publication', 'datetime', array(
            'field_mapping' => array('publication_date'),
            'value_format' => 'Y.m.d H:i:s',
            'label' => 'Author'
        )
    )
    ->addColumn('action', 'action', array(
            'label' => 'Actions',
            'field_mapping' => array('id'),
            'actions' => array(
                'edit' => array(
                    'route_name' => '_edit_news',
                    'parameters_field_mapping' => array('id' => 'id'),
                ),
                'delete' => array(
                    'route_name' => '_delete_news',
                    'parameters_field_mapping' => array('id' => 'id'),
                )
            )
        )
    );
```

Ok, so now when we have grid its time to create view. This can be done by calling ``DataGrid::CreateView()``

``` php
<?php

$view = $gird->createView();
```

$view is ``DataGridView`` object that implements ``\SeekableIterator``, ``\Countable``
and ``\ArrayAccess`` interfaces so have easy access to each row in view.

Every single row in view must be ``DataGridRowView`` object that also implements
``\SeekableIterator``, ``\Countable`` and ``\ArrayAccess`` interfaces so you have
access to each column in row.

And the last view part, column is an object with methods like ``getAttribute``,
``getValue`` and few other that will help you to build perfect view.

Here is a simple view implementation:

```

<table>
    <tr>
    <?php foreach ($view->getColumns() as $column):?>
        <td><?php echo $column->getLabel(); ?></td>
    <?php endforeach; ?>
    </tr>
    <tr>
    <?php foreach ($view as $row) :?>
    <tr>
        <?php foreach ($row as $column):?>
        <td>
            <?php if ($column->getType() == 'action'): ?>
                <?php foreach ($column->getValue() as $link): ?>
                    <a href="<?php echo $link['url']; ?>"><?php echo $link['anchor']; ?></a>
                <?php endforeach; ?>
            <?php else: ?>
                <?php echo $column->getValue(); ?>
            <?php endif;?>
        </td>
        <?php endforeach; ?>
    </tr>
    <?php endforeach; ?>
</table>

```

**Heads up!**
There is a difference between using getColumns method at view and direct array access.

```
<?php

$columns = $view->getColumns();
foreach ($columns as $column) {
    // this will give you access to ``HeaderView`` object.
    echo $column->getLabel();
}

// this will give you access to ``CellView`` object.
$cell = $view['name'];
echo $cell->getValue();
```


## Installation ##

This section will describe how to add datagrid into your project.
First thing is to get DataGrid sources, we recommend you to use [composer](https://github.com/composer/composer#composer---package-management-for-php)
After that you need to do a couple of things to create ``DataGridFactory`` that
finally allows you to create ``DataGrid`` objects.

Here are sample scenarios of component usage:

- [standalone](https://github.com/norzechowicz/datagrid-standalone)
- [symfony](https://github.com/fsi-open/datagrid-bundle)

You can also add ``fsi/datagrid`` into composer.json and run ``composer.phar update`` or ``composer.phar install``

``` json
{
    "require": {
        "fsi/datagrid": "1.0.*"
    }
}
```

## Tests ##

Install PHPUnit 3.5.11.
To run tests for DataGrid you should install dev packages and run tests with commands:

```
    $ php composer.phar update
    $ phpunit
```

## Extensions ##

Extensions are something that makes DataGrid component extremely flexible, they
are highly inspired by SymfonyForm extensions.

There are two kinds of extensions.

- **DataGrid Extension**
- **DataGrid Column Extensions**

DataGrid Extension are used to provide new column types, event subscribers even
additional options for existing column types.

DataGrid Column Extensions purpose is to add new functionality to existing column
types.

Each DataGrid Column Extension must be loaded by DataGrid Extension. Loading extension
into DataGrid is nothing more than registering it in DataGridFactory.

## Built-in extension types ##

(You can find documentation for some of these extensions in ``doc/en/extensions`` folder.)

- Core
- SymfonyForm
- SymfonyDependencyInjection
- Docrtine
- Gedmo

## Built-in column types ##

(You can find documentation for types in ``doc/en/columns`` folder.)

- [text](doc/en/columns/text.md)
- [boolean](doc/en/columns/boolean.md)
- [number](doc/en/columns/number.md)
- [money](doc/en/columns/money.md)
- [datetime](doc/en/columns/datetime.md)
- [action](doc/en/columns/action.md)
- [entity](doc/en/columns/entity.md)
- [tree](doc/en/columns/tree.md)

## Built-in available columns options ##

Action          | Action Symfony |Boolean        | Collection      | Text                  |
--------------- | -------------- |-------------- | --------------- | --------------------- |
label           | label          |label          | label           | label                 |
field_mapping   | field_mapping  |field_mapping  | field_mapping   | field_mapping         |
display_order   | display_order  |value_glue     | value_glue      | value_glue            |
actions         | actions        |display_order  | display_order   | trim                  |
                |                |value_format   | value_format    | value_format          |
                |                |editable       | collection_glue | editable              |
                |                |form_options   |                 | form_options          |
                |                |form_type      |                 | form_type             |
                |                |empty_value    |                 | empty_value           |
                |                |true_value     |                 | datetime_format       |
                |                |false_value    |                 | input_type            |
                |                |               |                 | field_mapping_format  |
                |                |               |                 | display_order         |


 Number                   | DateTime       | Money         | Entity         | Tree          |
 ------------------------ |--------------- | ------------- | -------------- | ------------- |
 label                    | label          | label         | label          | label         |
 field_mapping            | field_mapping  | field_mapping | field_mapping  | field_mapping |
 value_glue               | value_glue     | value_glue    | value_glue     | value_glue    |
 display_order            | display_order  | display_order | display_order  | display_order |
 value_format             | value_format   | value_format  | value_format   | value_format  |
 editable                 | editable       | editable      | editable       | editable      |
 form_options             | form_options   | form_options  | form_options   | form_options  |
 form_type                | form_type      | form_type     | empty_value    | empty_value   |
 empty_value              | empty_value    | empty_value   | relation_field | em            |
 value_glue_multiple      | round_mode     | round_mode    | glue_multiple  |               |
 relation_field           | precision      | precision     |                |               |
 currency                 |                |               |                |               |
 currency_field           |                |               |                |               |
 value_currency_separator |                |               |                |               |
 thousands_sep            |                |               |                |               |
 dec_point                |                |               |                |               |
 decimals                 |                |               |                |               |
