<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Static extends Controller_Template {

	public $template = 'template/main';
	public $content = '<p>Page needs content</p>';
	
	public function action_fallback()
	{
		// Pure static, get the matching view from the static folder
		$this->content = View::factory('pages/'.$this->pagename);
	}

	public function before()
	{
		parent::before();

		// Get the page name
		$this->pagename = $this->request->action();

		if ( ! Kohana::find_file('views', 'pages/'.$this->pagename))
		{
			// Page Not Found
			throw HTTP_Exception::factory(404, 'The requested URL :uri was not found on this server.', array(
				':uri' => $this->request->uri(),
			));
		}
		else
		{
			// Bind it to a variable for use in views
			View::bind_global('pagename', $this->pagename);

			// The page might be static plus, set content to the action for now
			$this->content = View::factory('pages/'.$this->request->action());
			
			$query = DB::select()->from('pages')->where('pagename', '=', $this->pagename);
			$seo = $query->execute()->as_array();

			if($seo)
			{
				$this->title = $seo[0]['title'];
				$this->description = $seo[0]['description'];
			}
		}
	}

	public function after()
	{
		// Make $page_title available to all views
		$this->template
			->bind('title', $this->title)
			->bind('description', $this->description);
		
		$this->template->content = $this->content;

		parent::after();
	}
}
