<?php defined('SYSPATH') OR die('No direct script access.');

abstract class Controller extends Kohana_Controller {
	/**
	 * Executes the given action and calls the [Controller::before] and [Controller::after] methods.
	 * 
	 * Can also be used to catch exceptions from actions in a single place.
	 * 
	 * 1. Before the controller action is called, the [Controller::before] method
	 * will be called.
	 * 2. Next the controller action will be called.
	 * 3. After the controller action is called, the [Controller::after] method
	 * will be called.
	 * 
	 * @throws  HTTP_Exception_404
	 * @return  Response
	 */
	public function execute()
	{
		// Execute the "before action" method
		$this->before();

		// Determine the action to use
		$action = 'action_'.$this->request->action();

		// If the action doesn't exist, try to fallback
		if ( ! method_exists($this, $action))
		{
			if (method_exists($this, 'action_fallback'))
			{
				//fallback action
				$action = 'action_fallback';
			}
			else
			{
				 //otherwise 404
				throw HTTP_Exception::factory(404,
					'The requested URL :uri was not found on this server.',
					array(':uri' => $this->request->uri())
				)->request($this->request);
			}
		}

		// Execute the action itself
		$this->{$action}();

		// Execute the "after action" method
		$this->after();

		// Return the response
		return $this->response;
	}
}
