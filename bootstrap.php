<?php

use Flyer\Foundation\Events\Events;
use Flyer\Components\ClassLoader;
use Flyer\Foundation\AliasLoader;
use Flyer\Foundation\Registry;
use Flyer\App;

/**
 * Create a new application
 */

$app = new App();

$app->setRegistryHandler(new Registry);

/**
 * Set up the Exception handler for the application
 */

$whoops = new Whoops\Run();
$whoops->pushHandler(new Whoops\Handler\PrettyPageHandler());

$whoops->register();

/**
 * Setting up the current request method
 */

Registry::set('application.request.method', $_SERVER['REQUEST_METHOD']);

/**
 * Require the config files and add those results to the Registry
 */

Registry::set('config', require(APP . 'config' . DS . 'config.php'));


/**
 * Setting up the events manager
 */

Registry::set('foundation.events', new Events);

Events::create(array(
	'title' => 'application.boot',
	'event' => function () {
		
	}
));

/**
 * Setting the current HTTP request to the events manager
 */

Events::create(array(
	'title' => 'request.get',
	'event' => function () {
		return SymfonyRequest::createFromGlobals();
	}
));

/**
 * Creating all aliases for the original classes, they are specified in the config array
 */

foreach (Registry::get('config')['classAliases'] as $originalClass => $alias)
{
	AliasLoader::create($originalClass, $alias);
}

/**
 * Attach all of the service providers (specified the config file) to the application
 */

foreach (Registry::get('config')['serviceProviders'] as $serviceProvider)
{
	$app->register(new $serviceProvider);
}


/**
 * Boot the application
 */

$app->boot();

Registry::get('dfdf');