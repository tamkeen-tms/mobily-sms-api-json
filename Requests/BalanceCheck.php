<?php namespace MobilyAPI\Requests;

	use MobilyAPI\Client;
	use MobilyAPI\Request;

	/**
	 * Checks your account balance
	 *
	 * @package MobilyAPI\Requests
	 */
	class BalanceCheck extends Request{
		/**
		 * @var string
		 */
		public $function = 'balance';
		/**
		 * @var array
		 */
		public $responseCodes = [
			0 => 'connection_failed',
			1 => 'invalid_username',
			2 => 'invalid_password'
		];

		/**
		 * @param Client $client
		 */
		public function __construct(Client $client){
			parent::create($client);
		}

		/**
		 * Returns the available credit
		 * @return mixed
		 */
		public function getAvailable(){
			return $this->error ?false :$this->getResponse()->Data->balance->current;
		}

		/**
		 * Returns the total number of points you have/had
		 * @return mixed
		 */
		public function getTotal(){
			return $this->error ?false :$this->getResponse()->Data->balance->total;
		}
	}