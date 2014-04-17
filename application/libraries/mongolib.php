<?php

include_once('mongo/Builder.php');

class MongoLib {
	/**
	 * Config file.
	 *
	 * @var string
	 * @access private
	 */
	private $_config_file = 'mongodb';
	
	public function __construct(){
		$ci =& get_instance();
		
		if ( ! class_exists('Mongo'))
		{
			$this->_show_error('The MongoDB PECL extension has not been installed or enabled', 500);
		}
		
		if (function_exists('get_instance'))
		{
			$this->_ci = get_instance();
		}
		
		else
		{
			$this->_ci = NULL;
		}
		
		
		$this->load();
		$this->_connection_string();
		
		$ci->mongo = new MongoQB\Builder(Array('dsn' =>  $this->_connection_string));
	}
	
	public function _show_error($error){
		die($error);
	}	
	public function load($config = 'default')
	{
		// Try and load a config file if CodeIgniter
		if ($this->_ci)
		{
			$this->_config_data = $this->_ci->config->load($this->_config_file);
		}
	
		if (is_array($config))
		{
			$this->_config_data = $config;
		}
	
		elseif (is_string($config) && $this->_ci)
		{
			$this->_config_data = $this->_ci->config->item($config);
		}
	
		else
		{
			$this->_show_error('No config name passed or config variables', 500);
		}
	
	}
	/**
	 * Build connectiong string.
	 *
	 * @access private
	 * @return void
	 */
	private function _connection_string()
	{
		$this->_host = trim($this->_config_data['mongo_hostbase']);
		$this->_user = trim($this->_config_data['mongo_username']);
		$this->_pass = trim($this->_config_data['mongo_password']);
		$this->_dbname = trim($this->_config_data['mongo_database']);
		$this->_persist = $this->_config_data['mongo_persist'];
		$this->_persist_key = trim($this->_config_data['mongo_persist_key']);
		$this->_replica_set = $this->_config_data['mongo_replica_set'];
		$this->_query_safety = trim($this->_config_data['mongo_query_safety']);
	
		$connection_string = 'mongodb://';
	
		if (empty($this->_host))
		{
			$this->_show_error('The Host must be set to connect to MongoDB', 500);
		}
	
		if (empty($this->_dbname))
		{
			$this->_show_error('The database name must be set to connect to MongoDB', 500);
		}
	
		if ( ! empty($this->_user) AND ! empty($this->_pass))
		{
			$connection_string .= $this->_user . ':' . $this->_pass . '@';
		}
	
		$connection_string .= $this->_host;
	
		$this->_connection_string = trim($connection_string) . '/' . $this->_dbname;
	
	}
}
