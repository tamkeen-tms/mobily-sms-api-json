<?php namespace MobilyAPI\Requests;

	use MobilyAPI\Client;
	use MobilyAPI\Request;

	/**
	 * Delete a message
	 *
	 * @package MobilyAPI\Requests
	 */
	class DeleteMessage extends Request{
		/**
		 * @var string
		 */
		public $function = 'deleteMsg';

		/**
		 * @var array
		 */
		public $responseCodes = [
			1 => 'deleted',
			2 => 'invalid_account_credentials',
			3 => 'invalid_account_credentials',
			4 => 'invalid_deletion_key',
		];

		/**
		 * @param Client $client
		 */
		public function __construct(Client $client, $deletionKey){
			// Create the request
			parent::create($client);

			$this->params = [
				'deleteKey' => $deletionKey
			];
		}

		/**
		 * Tells if the message was deleted
		 * @return bool
		 */
		public function deleted(){
			return $this->error ?false :$this->getResponse()->Data->result == '1';
		}
	}