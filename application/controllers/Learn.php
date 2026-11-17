<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Learn extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('url', 'learn'));
		$this->load->model('Curriculum_model');
	}

	public function index()
	{
		$this->load->view('learn/index', array(
			'modules' => $this->Curriculum_model->all()
		));
	}

	public function module($slug = '')
	{
		$modules = $this->Curriculum_model->all();
		$slugs = array_keys($modules);
		$position = array_search($slug, $slugs, TRUE);

		if ($position === FALSE)
		{
			show_404();
			return;
		}

		$this->load->view('learn/module', array(
			'module' => $modules[$slug],
			'slug' => $slug,
			'number' => $position,
			'total' => count($modules),
			'previous' => $position > 0 ? $slugs[$position - 1] : NULL,
			'next' => isset($slugs[$position + 1]) ? $slugs[$position + 1] : NULL,
			'modules' => $modules
		));
	}
}
