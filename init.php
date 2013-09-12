<?php defined('SYSPATH') or die('No direct script access.');

/*
 * Default to static plus controller. Static plus calls static if the action isn't found
*/

Route::set('default', '(<action>)',
		array(
			'action' => '[^admin]',
		)
	)
	->defaults(array(
		'controller' => 'Staticplus',
		'action'     => 'index',
	));
