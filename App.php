<?php

namespace Flyer;

use Flyer\Foundation\ServiceProvider;
use Flyer\Foundation\Events\Events;

class App
{

	/**
	 * Holds all of the service providers
	 */

	protected $providers = array();

	/**
	 * Holds all of the booted service providers
	 */

	protected $bootedProviders = array();

	/**
	 * Holds the current booting status of the application
	 */

	protected $booted = false;

	/**
	 * Holds the registry handler that is used the application
	 */

	protected $registryHandler;


	/**
	 * Sets the registry handler that the application has to use
	 *
	 * @var  object The registry handler
	 * @return  void
	 */

	public function setRegistryHandler($handler)
	{
		$this->registryHandler = $handler;
	}
	
	/**
	 * Attach a component to the application
	 *
	 * @var object The Service Provider of the component, extending the ServiceProvider class
	 * @return  void 
	 */

	public function register(ServiceProvider $provider)
	{
		$provider->register();

		$this->providers[] = $provider;
	}

	/**
	 * Boot the application by booting all service providers
	 *
	 * @return  void
	 */

	public function boot() 
	{
		foreach ($this->providers as $provider)
		{
			$provider->boot();
		}

		Events::trigger('application.boot');
		$this->booted = true;
	}

	public function shutdown()
	{
		if (Events::exists('application.route'))
		{
			echo Events::trigger('application.route');
		} else {
			echo $this->triggerErrorPage('404');
		}
	}

	public function triggerErrorPage($error)
	{
		return Events::trigger('application.error.' . $error);
	}

	/**
	 * Removes all providers out of the pending boot payload
	 *
	 * @return  void 
	 */

	public function resetProviders()
	{
		unset($this->providers);
	}

	/**
	 * Returns the application booting status
	 *
	 * @return bool The booting status
	 */

	public function booted()
	{
		return $this->booted;
	}
}