
# Asynchronous Template Data #



## Introduction ##

> Important information of the WordPress plugin:
> 1. Plugin is in development process, do not use it in production, breaking changes may occur at any time.
> 2. Plugin needs to be installed using PHP Composer or there might conflicting libraries.

A WordPress plugin that provides a programmatic interface to register data providers and display (inject) the data in templates asynchronously. The main idea behind the plugin is to provide a generic solution of how to keep using page caching mechanism ( e.g. Varnish Cache ) and inject a certain portion of DOM content asynchronously and hence avoiding cached content.

A real world example where plugin's provided solution can become beneficial could be a product page of any WordPress e-commerce solution that is cached by a HTTP accelerator solution. Plugin's provided solution injects an up to date information of a delivery information of an e-commerce product avoiding cache.

Please note that this plugin does not do anything by itself. There must be a data provider(s) registered and data injection initialized somewhere.


## Minimum Requirements ##

*  **PHP:** 8.1
*  **WordPress:** Tested up to WP 6.0

## Usage ##

### Data Provider ###
Concrete data providers must extend `martinsluters\AsynchronousTemplateData\ProvidersAbstractDataProvider` class implementing `getConcreteData` method. An example of a concrete implementation can be seen in  [YITH WooCommerce Delivery Date Provider](https://github.com/martinsluters/asynchronous-template-data/blob/develop/src/Providers/YithProvider.php) or a very simple [Dummy Provider](https://github.com/martinsluters/asynchronous-template-data/blob/develop/tests/DummyDeliveryInformationProvider.php) .

### Provider Manager ###
There can be created and registered as many data providers as needed. Here comes Provider Manager that allows to register data providers.

 An example of how to register data providers:
```
$plugin = martinsluters\AsynchronousTemplateData\Bootstrap::getInstance();
$provider_manager = $plugin->getProviderManager();

$provider_manager->addProvider( 'provider-unique-key-1', new DummyProviderOne() );
$provider_manager->addProvider( 'provider-unique-key-2', new DummyProviderTwo() );
```

### Data Lookup Argument ###
There can be potentially many different ways of how to get the right data from a data provider. Some providers will require just WordPress Post ID and some might require many other arguments e.g. language, location, color etc. etc. therefore here comes lookup argument to help.

Concrete lookup arguments must extend `martinsluters\AsynchronousTemplateData\Arguments\AbstractLookupArgument` class.

By default the plugin comes with a lookup argument [LookupArgument](https://github.com/martinsluters/asynchronous-template-data/blob/develop/src/Arguments/LookupArgument.php)  that requires two constructor arguments - registered provider key and WordPress post ID.

Registered provider key - a string based key that was used when registering a data provider in provider manager.
WordPress post ID -  WordPress post identification integer number.

Example of instantiating a new LookupArgument:
```
new LookupArgument( 'provider-unique-key-1', get_the_ID() )
```

### Initialize asynchronous data injection ###
Once a provider is registered and the right lookup argument is chosen for a provider it is possible to initialize data injection. One way of initializing data asynchronous injection is by rendering initial/bootstrap html in a desired place of a web page. The html will initialize javascript to make an asynchronous call to a web server to get the right data and display it.

To do so a method `showWrapperTemplate` of content controller needs to be called with an instance of `martinsluters\AsynchronousTemplateData\Arguments\AbstractLookupArgument` implementation as an argument.

Example:
```
$plugin = martinsluters\AsynchronousTemplateData\Bootstrap::getInstance();
$content_controller = $plugin->getContentController();

$content_controller->showWrapperTemplate(
	new LookupArgument( 'provider-unique-key-1', get_the_ID() )
)
```

**A full example usage of the plugin can be seen in a test code [here](https://github.com/martinsluters/asynchronous-template-data/blob/develop/tests/client-mu-plugin/client-mu-plugin.php) .**

### WordPress actions/filters ###
The plugin has several filters and actions both PHP and JS to extend the functionality in WordPress event-driven way.

## License

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.



This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
