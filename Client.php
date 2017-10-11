<?php namespace MobilyAPI;

	/**
	 * @package MobilyAPI
	 */
	class Client{
		/**
		 * The client setup
		 * @var array
		 */
		public $setup = [
			'username'                  => null,
			'password'                  => null,
			'senderName'                => null,
			'appUrl'                    => null,
			'request_time_out'          => 5,
			'verify_ssl_certificate'    => false,
			'debug'                     => false
		];

		/**
		 * The API base uri
		 */
		const API_BASE_URI = 'https://mobily.ws/api/json/';

		/**
		 * @param $username
		 * @param $password
		 * @param $senderName
		 * @param array $setup
		 */
		public function __construct($username, $password, $senderName, $setup = []){
			// The client setup
			$this->setup = array_merge($this->setup, $setup);

			$this->setup['username'] = $username;
			$this->setup['password'] = $password;
			$this->setup['senderName'] = $senderName;

			if(!$this->setup['appUrl']){
				$this->setup['appUrl'] = $_SERVER['SERVER_NAME'];
			}
		}

		/**
		 * Change the sender name
		 * @param $name
		 */
		public function changeSender($name){
			$this->setup['senderName'] = $name;
		}
	}