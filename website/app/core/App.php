<?php

class App {

	protected $controller = 'utwory';

	protected $method = 'index';

	protected $params = [];

	public function __construct() {
		$url = $this->parseUrl();
		$this->model = $this->controller;


		if(file_exists('../app/controllers/controller_'. $url[0] .'.php')) {
			$this->controller = $url[0];
			$this->model = $url[0];
			unset($url[0]);
		}

		require_once '../app/controllers/controller_'. $this->controller .'.php';

		$this->controller = new $this->controller;
		$this->controller->loadModel($this->model);

		if(isset($url[1])) {
			if(method_exists($this->controller, $url[1])) {
				$this->method = $url[1];
				unset($url[1]);
			}
		}

		$this->params = $url ? array_values($url) : [];

		call_user_func_array(array($this->controller, $this->method), $this->params);
	}

	public function parseUrl() {
		if(isset($_GET['url'])) {
			return $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
		}
	}
}