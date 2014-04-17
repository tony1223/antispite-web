<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @author TonyQ
 *
 */
class UrlModel extends MONGO_MODEL {

	/**
	 * @var Mongo_db
	 */
	var $mongo_db ;

	function __construct()
	{
		parent::__construct();

	}

}
