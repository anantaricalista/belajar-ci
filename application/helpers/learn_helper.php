<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function learn_site_url($uri = '')
{
	return parse_url(site_url($uri), PHP_URL_PATH);
}

function learn_asset_url($uri)
{
	return parse_url(base_url($uri), PHP_URL_PATH);
}
