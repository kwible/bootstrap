<?php
namespace Flyer\Components;

class ClassLoader
{
	public function register($function)
	{
		spl_autoload_register($function);
	}

	public function unregister($function) 
	{
		spl_autoload_unregister($function);
	}

	public function restore()
	{
		spl_autoload_register('__autoload');
	}
}