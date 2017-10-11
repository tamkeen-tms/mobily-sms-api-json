<?php namespace MobilyAPI;

	use GuzzleHttp\Client as Guzzle;
	use GuzzleHttp\Psr7\Response;

	/**
	 * @package MobilyAPI
	 */
	class Request{
		/**
		 * The client through which the request will be made
		 * @var Client
		 */
		public $client;
		/**
		 * The request params
		 * @var array
		 */
		public $params = [];
		/**
		 * The Guzzle request object
		 * @var Guzzle
		 */
		public $request;
		/**
		 * The response for the sent request
		 * @var object
		 */
		public $response;
		/**
		 * The resulting error, if any
		 * @var string
		 */
		public $error;

		const ERROR_SERVICE_NOT_AVAILABLE = 'service_not_available';
		const ERROR_REQUEST_FAILED = 'request_failed';

		/**
		 * Create a new API request
		 * @param Client $client
		 */
		public function create(Client $client){
			// Pass the Client to this class
			$this->client = $client;

			// Create the request
			$this->request = new Guzzle([
				'base_uri'          => Client::API_BASE_URI,
				'connect_timeout'   => $this->client->setup['request_time_out'],
				'verify'            => $this->client->setup['verify_ssl_certificate'],
				'debug'             => $this->client->setup['debug']
			]);
		}

		/**
		 * Send the request
		 * @return mixed
		 */
		public function send(){
			// The request data
			$requestData = [
				'Data' => [
					'Method' => $this->function,
					'Params' => $this->params,
					'Auth' => [
						'mobile' => $this->client->setup['username'],
						'password' => $this->client->setup['password']
					]
				]
			];

			try{
				// Send the request
				$response = $this->request->request('POST', '', ['json' => $requestData]);

				// Decode the response
				$this->response = \GuzzleHttp\json_decode((string) $response->getBody());

				// Check for errors
				if(
					$this->response->status != '1' ||
					$this->response->ResponseStatus == 'fail' ||
					!empty($this->response->Error)
				){
					// If the error code was specified
					if(isset($this->response->Error->ErrorCode)){
						$this->error = $this->responseCodes[$this->response->Error->ErrorCode];

					}else{
						// Else the service isn't available!
						$this->error = static::ERROR_SERVICE_NOT_AVAILABLE;
					}
				}

			}catch (\Exception $exception){
				// Couldn't make the request
				$this->error = static::ERROR_REQUEST_FAILED;
			}
		}

		/**
		 * The response
		 * @return mixed
		 */
		public function getResponse(){
			// If the request wasn't sent
			if(!is_object($this->response)){
				$this->send();
			}

			// Decode
			return $this->response;
		}
	}