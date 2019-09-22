<?php 
	namespace App;
	
	class Dispatch {
		use \Src\Traits\TraitUrlParser;

		public function run() {

			$url = $this -> parseUrl();
			$params = array();

			if (!empty($url[0]) && $url[0] != '/') {
				$currentController = $url[0].'Controller';
				array_shift($url);

				if (isset($url[0]) && !empty($url[0])) {
					$currentAction = $url[0];
					array_shift($url);
				} else {
					$currentAction = 'index';
				}

				if (count($url) > 0) {
					foreach ($url as $param) {
						if (!empty($param) && $param != '/') {
							$params[] .= $param;
						}
					}
				}
			} else {
				$currentController = 'HomeController';
				$currentAction = 'index';
			}

			$currentController = ucfirst($currentController);
			$prefix = '\App\Controllers\\';

			if (!file_exists(DIRREQ.'app/Controllers/'.$currentController.'.php') || 
					!method_exists($prefix.$currentController, $currentAction)) 
			{
				$currentController = 'NotfoundController';
				$currentAction = 'index';
			}

			$newController = $prefix.$currentController;

			$c = new $newController();
			call_user_func_array(array($c, $currentAction), $params);
		}
	}