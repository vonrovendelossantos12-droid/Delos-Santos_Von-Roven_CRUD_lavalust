<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Welcome extends Controller {
	public function index() {
		$target = (defined('BASE_URL') && BASE_URL !== '') ? rtrim(BASE_URL, '/') . '/login' : '/login';
		header('Location: ' . $target);
		exit;
	}
}
?>