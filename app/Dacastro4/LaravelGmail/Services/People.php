<?php

namespace App\Dacastro4\LaravelGmail\Services;

use App\Dacastro4\LaravelGmail\LaravelGmailClass;
use Google_Service_PeopleService_Resource_People;
use Google_Service_Gmail;
use Google_Service_Gmail_BatchModifyMessagesRequest;
use Google_Service_Gmail_BatchDeleteMessagesRequest;
use Google_Service_Gmail_Label;


class People
{

	public $service;

	public $client;

	/**
	 * Optional parameter for getting single and multiple emails
	 *
	 * @var array
	 */
	protected $params = [];

	/**
	 * Message constructor.
	 *
	 * @param  LaravelGmailClass  $client
	 */
	public function __construct(LaravelGmailClass $client)
	{
		$this->client = $client;
		$this->service = new Google_Service_PeopleService_Resource_People($client);
	}

}
