<?php


class Database{

	/**
	 * @var string
	 */
	public $file = 'file/search_at_this_moment.db';

	private static $instance;

	public static function getInstance()
	{
		if (null === static::$instance) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	private function __construct()
	{
		$this->db = $this->coonnect();
	}

	private function coonnect(){
		return new SQLite3($this->file);
	}

}
