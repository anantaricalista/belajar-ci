<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Curriculum_model extends CI_Model {

	public function all()
	{
		return require APPPATH.'data/curriculum.php';
	}
}
