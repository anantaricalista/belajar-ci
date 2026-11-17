<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Curriculum_model extends CI_Model {

	public function all()
	{
		$modules = require APPPATH.'data/curriculum.php';
		$project_modules = require APPPATH.'data/curriculum_project.php';

		$before_crud = array_slice($modules, 0, array_search('crud', array_keys($modules), TRUE), TRUE);
		$from_crud = array_slice($modules, count($before_crud), NULL, TRUE);

		return $before_crud
			+ array('jquery-datatables' => $project_modules['jquery-datatables'])
			+ $from_crud
			+ array('proyek-akhir-crud-siswa' => $project_modules['proyek-akhir-crud-siswa']);
	}
}
