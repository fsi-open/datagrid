# Data Grid Component - Symfony 2 installation #

Add ``fsi/datagrid`` into composer.json and run ``composer.phar update`` or ``composer.phar install``

``` json
{
    "require": {
        "fsi/datagrid": "0.9.*"
    }
}
``` 

The hardest thing is to create environment. Main part can be done by services registration.
Example of datagrid.xml configuration file (filepath = FSi/Bundle/SiteBundle/Resources/config/datagrid.xml)

``` xml

<?xml version="1.0" ?>

<container xmlns="http://symfony.com/schema/dic/services"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

    <parameters>
        <parameter key="datagrid.factory.class">FSi\Component\DataGrid\DataGridFactory</parameter>
        <parameter key="datagrid.indexingstrategy.chain.class">FSi\Component\DataGrid\Data\ChainIndexingStrategy</parameter>
        <parameter key="datagrid.indexingstrategy.entity.class">FSi\Component\DataGrid\Data\EntityIndexingStrategy</parameter>
        <parameter key="datagrid.datamapper.chain.class">FSi\Component\DataGrid\DataMapper\ChainMapper</parameter>
        <parameter key="datagrid.datamapper.reflection.class">FSi\Component\DataGrid\DataMapper\ReflectionMapper</parameter>
        <parameter key="datagrid.extension.class">FSi\Component\DataGrid\Extension\Symfony\DependencyInjectionExtension</parameter>
    </parameters>

    <services>        
        <service id="datagrid.extension" class="%datagrid.extension.class%">
            <argument type="service" id="service_container" />
            <!-- All services with tag "datagrid.column" are inserted here by FormPass -->
            <argument type="collection" />
            <!-- All services with tag "datagrid.column_extension" are inserted here by FormPass -->
            <argument type="collection" />
            <!-- All services with tag "datagrid.subscriber" are inserted here by FormPass -->
            <argument type="collection" />
        </service>
        
        <!-- DataMapper\Reflection -->
        <service id="datagrid.datamapper.reflection" class="%datagrid.datamapper.reflection.class%" />

        <!-- DataMapper\Chain -->
        <service id="datagrid.datamapper.chain" class="%datagrid.datamapper.chain.class%">
            <argument type="collection">
                <argument type="service" id="datagrid.datamapper.reflection" />
            </argument>
        </service>

        <!-- IndexingStrategy\Entity -->
        <service id="datagrid.indexingstrategy.entity" class="%datagrid.indexingstrategy.entity.class%">
            <argument type="service" id="doctrine.orm.entity_manager"/>
        </service>

        <!-- IndexingStrategy\Chain -->
        <service id="datagrid.indexingstrategy.chain" class="%datagrid.indexingstrategy.chain.class%">
            <argument type="collection">
                <argument type="service" id="datagrid.indexingstrategy.entity" />
            </argument>
        </service>

        <!-- DataGridFactory -->
        <service id="datagrid.factory" class="%datagrid.factory.class%">
            <argument type="collection">
                <!--
                We don't need to be able to add more extensions.
                 * more columns can be registered with the datagrid.column tag
                 * more column extensions can be registered with the datagrid.column_extension tag
                 * more listeners can be registered with the datagrid.listener tag
                -->
                <argument type="service" id="datagrid.extension" />
            </argument>
            <argument type="service" id="datagrid.datamapper.chain" />
            <argument type="service" id="datagrid.indexingstrategy.chain" />
        </service>

        <!-- CoreExtension -->
        <service id="datagrid.column.number" class="FSi\Component\DataGrid\Extension\Core\ColumnType\Number">
            <tag name="datagrid.column" alias="number" />
        </service>
        <service id="datagrid.column.text" class="FSi\Component\DataGrid\Extension\Core\ColumnType\Text">
            <tag name="datagrid.column" alias="text" />
        </service>
        <service id="datagrid.column.datetime" class="FSi\Component\DataGrid\Extension\Core\ColumnType\DateTime">
            <tag name="datagrid.column" alias="datetime" />
        </service>
        <service id="datagrid.column.action" class="FSi\Component\DataGrid\Extension\Core\ColumnType\Action">
            <tag name="datagrid.column" alias="action" />
        </service>
        <service id="datagrid.column.grid" class="FSi\Component\DataGrid\Extension\Core\ColumnType\Grid">
            <tag name="datagrid.column" alias="grid" />
            <argument type="service" id="datagrid.factory"/>
        </service>
        <service id="datagrid.column.money" class="FSi\Component\DataGrid\Extension\Core\ColumnType\Money">
            <tag name="datagrid.column" alias="money" />
        </service>
        <service id="datagrid.column.entity" class="FSi\Component\DataGrid\Extension\Doctrine\ColumnType\Entity">
            <tag name="datagrid.column" alias="entity" />
        </service>
        <service id="datagrid.column.gedmo.tree" class="FSi\Component\DataGrid\Extension\Gedmo\ColumnType\Tree">
            <tag name="datagrid.column" alias="gedmo.tree" />
            <argument type="service" id="doctrine"/>
        </service>
        <service id="datagrid.column_extension.default" class="FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension\DefaultColumnOptionsExtension">
            <tag name="datagrid.column_extension" aliast="default"/>
        </service>   

        <!-- SymfonyFormExtension -->
        <service id="datagrid.column_extension.symfony.form" class="FSi\Component\DataGrid\Extension\Symfony\ColumnTypeExtension\FormExtension">
            <tag name="datagrid.column_extension" aliast="symfony.form"/>
            <argument type="service" id="form.factory" />
        </service>  
        <service id="datagrid.subscriber.symfony.bindrequest" class="FSi\Component\DataGrid\Extension\Symfony\EventSubscriber\BindRequest">
            <tag name="datagrid.subscriber" aliast="symfony.form.bindrequest"/>
        </service>  
        <service id="datagrid.column_extension.symfony.action" class="FSi\Component\DataGrid\Extension\Symfony\ColumnTypeExtension\ActionColumnExtension">
            <tag name="datagrid.column_extension" aliast="symfony.action"/>
            <argument type="service" id="router" />
        </service>  
        
    </services>
</container>

```

But if we want to use ``DataGrid\Extension\Symfony\DependencyInjectionExtension`` we need to 
add few more things into our bundle. 


**DataGridPass**

``` php

<?php

//  FSi/Bundle/SiteBundle/DependencyInjection/Compiler/DataGridPass.php

namespace FSi\Bundle\SiteBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class DataGridPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('datagrid.extension')) {
            return;
        }

        $columns = array();

        foreach ($container->findTaggedServiceIds('datagrid.column') as $serviceId => $tag) {
            $alias = isset($tag[0]['alias'])
                ? $tag[0]['alias']
                : $serviceId;

            $columns[$alias] = $serviceId;
        }

        $container->getDefinition('datagrid.extension')->replaceArgument(1, $columns);

        $columnExtensions = array();

        foreach ($container->findTaggedServiceIds('datagrid.column_extension') as $serviceId => $tag) {
            $alias = isset($tag[0]['alias'])
                ? $tag[0]['alias']
                : $serviceId;

            $columnExtensions[$alias] = $serviceId;
        }
 
        $container->getDefinition('datagrid.extension')->replaceArgument(2, $columnExtensions);

        $subscribers = array();

        foreach ($container->findTaggedServiceIds('datagrid.subscriber') as $serviceId => $tag) {
            $alias = isset($tag[0]['alias'])
                ? $tag[0]['alias']
                : $serviceId;

            $subscribers[$alias] = $serviceId;
        }
 
        $container->getDefinition('datagrid.extension')->replaceArgument(3, $subscribers);
    }
}

```

**Bundle**

``` php 

<?php
// FSi/Bundle/SiteBundle/FSiSiteBundle.php

namespace FSi\Bundle\SiteBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\Scope;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use FSi\Bundle\SiteBundle\DependencyInjection\Compiler\DataGridPass;

class FSiSiteBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new DataGridPass());
    }
}

```