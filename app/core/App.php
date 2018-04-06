<?php

class App{

	protected $controller = 'home';
	protected $method 	  = 'index';
	protected $publicURL  =  ['account'];
	protected $loginURL = '/MVC/home';
	protected $params     = [];

	/**
	 *
     */
	public function __construct()
	{
		//call parseUrl function and set to $url
		$url = $this->parseUrl();

		//check if $url[0] controller exists
		if(file_exists('app/controllers/'.$url[0].'.php')) {
			//set controller to $controller
			$this->controller = $url[0];
			unset($url[0]); //delete controller from array
		}

		require_once 'app/controllers/'.$this->controller.'.php';

		$this->controller = new $this->controller;

		
		if(isset($url[1])){
			if(method_exists($this->controller, $url[1])){
				$this->method = $url[1];
				unset($url[1]);
			}
		}

		//store all params in $param
		$this->params = $url ? array_values($url):[];

		//print_r($this->params);

		call_user_func_array([$this->controller, $this->method], $this->params);

	}

	//explode and sanitize url: it will give us controller, method, params
	public function parseUrl(){
		if (isset($_GET['url'])) {
			//return array of controller/method/params/..... with sanitizing '/'
			return $url = explode('/',filter_var(rtrim($_GET['url'],'/'), FILTER_SANITIZE_URL));

		}
	}

	public function isPublicURL(){
		$url = $_GET['url'];
		return in_array ($url , $this->publicURL);
	}
}

?>