<?php

namespace App\Dacastro4\LaravelGmail\Services;

use App\Dacastro4\LaravelGmail\LaravelGmailClass;
use Google_Service_PeopleService;



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
    $this->service = new Google_Service_PeopleService($client);
	}

  public function list(Array $optParams)
  {
    return $this->service->otherContacts->listOtherContacts($optParams);
  }

  public function search(Array $optParams)
  {
    return $this->service->otherContacts->search($optParams);
  }

}
