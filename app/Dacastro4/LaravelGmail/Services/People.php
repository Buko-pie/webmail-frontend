<?php

namespace App\Dacastro4\LaravelGmail\Services;

use App\Dacastro4\LaravelGmail\LaravelGmailClass;
use Google_Service_PeopleService;



class People
{
  public $people;
  public $peopleService;

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

  public function list()
  {
    $optParams = array(
      'pageSize' => 50,
      'readMask' => 'names,emailAddresses',
    );

    // return $this->client->getToken();
    $result = $this->service->otherContacts->listOtherContacts($optParams);
    return response()->json($result, 200);
  }

}
