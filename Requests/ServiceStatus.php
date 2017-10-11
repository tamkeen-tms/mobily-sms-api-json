<?php namespace MobilyAPI\Requests;

	use MobilyAPI\Client;
	use MobilyAPI\Request;

	/**
	 * @package MobilyAPI\Requests
	 */
	class ServiceStatus extends Request{
		/**
		 * The API function
		 * @var string
		 */
		public $function = 'sendStatus';

		/**
		 * @param Client $client
		 */
		public function __construct(Client $client){
			parent::create($client);
		}

		/**
		 * Tells if the service is available
		 * @return bool
		 */
		public function available(){
			return $this->error ?false :$this->getResponse()->Data->result == '1';
		}
	}