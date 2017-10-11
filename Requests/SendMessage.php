<?php namespace MobilyAPI\Requests;

	use Carbon\Carbon;
	use MobilyAPI\Client;
	use MobilyAPI\Request;

	/**
	 * Send a new SMS
	 *
	 * @package MobilyAPI\Requests
	 */
	class SendMessage extends Request{
		/**
		 * @var string
		 */
		public $function = 'msgSend';
		/**
		 * The message id
		 * @var
		 */
		public $id;
		/**
		 * The deletion key
		 * @var
		 */
		public $deletionKey;
		/**
		 * The time in which the message was scheduled for sending
		 * @var Carbon
		 */
		public $time;
		/**
		 *
		 * @var array
		 */
		public $responseCodes = [
			1 => 'message_sent',
			2 => 'no_credit',
			3 => 'no_credit',
			4 => 'invalid_account_credentials',
			5 => 'invalid_account_credentials',
			6 => 'service_down',
			10 => 'invalid_variable_sets_count',
			13 => 'invalid_sender_name',
			14 => 'inactive_sender_name',
			15 => 'incorrect_phone_numbers',
			16 => 'missing_sender_name',
			17 => 'invalid_message_or_bad_encoding',
			18 => 'sending_denied_by_support',
			19 => 'missing_application_type'
		];
		/**
		 * @param Client $client
		 */
		public function __construct(Client $client, array $numbers = [], $body, $time = false, $senderName = false,
			$messageId = false, $deletionKey = false){

			// Create the request
			parent::create($client);

			// Ready the numbers list
			$numbers = array_unique(array_map('trim', $numbers));

			$this->id = $messageId ?: uniqid();
			$this->deletionKey = $deletionKey ?: $this->id;

			// Scheduled ?
			if($time){
				$this->time = Carbon::parse($time);
			}

			$this->params = [
				'sender'            => $senderName ?: $this->client->setup['senderName'],
				'msgId'             => $this->id,
				'numbers'           => implode(',', $numbers),
				'msg'               => $body,
				'timeSend'          => $this->time ?$this->time->format('h:m:s') :0,
				'dateSend'          => $this->time ?$this->time->format('m/d/y') :0,
				'deleteKey'         => $this->deletionKey,
				'lang'              => 3,
				'applicationType'   => 65,
				'domainName'        => $this->client->setup['appUrl']
			];
		}

		/**
		 * Get the message id
		 * @return string
		 */
		public function getId(){
			return $this->id;
		}

		/**
		 * The message deletion key
		 * @return string
		 */
		public function getDeletionKey(){
			return $this->deletionKey;
		}

		/**
		 * Tells if the message was successfully sent
		 * @return bool
		 */
		public function sent(){
			return $this->error ?false :$this->getResponse()->Data->result == '1';
		}

		/**
		 * Returns the rejected numbers or false if not available
		 * @return array
		 */
		public function getRejectedNumbers(){
			if($this->error) return [];

			$response = $this->getResponse();

			return isset($response->Data->rejectedNumber)
				?$response->Data->rejectedNumber :[];
		}

		/**
		 * Returns the time remaining for the message to be sent (that if it's scheduled)
		 * @return bool|\DateInterval|null
		 */
		public function getTimeUntilSending(){
			if(!$this->time) return null;

			return $this->time->diff(Carbon::now());
		}

		/**
		 * Deletes the message, that if it's scheduled
		 * @return bool
		 * @throws \Exception
		 */
		public function cancel(){
			if($this->error || !$this->time || !$this->time->isFuture()) return false;

			$deletionRequest = new DeleteMessage($this->client, $this->getDeletionKey());
			$deletionRequest->send();

			return $deletionRequest->deleted();
		}
	}