<?php

namespace App\Dacastro4\LaravelGmail\Services;

use App\Dacastro4\LaravelGmail\LaravelGmailClass;
use App\Dacastro4\LaravelGmail\Services\Message\Mail;
use App\Dacastro4\LaravelGmail\Traits\Filterable;
use App\Dacastro4\LaravelGmail\Traits\SendsParameters;
use Google_Service_Gmail;
use Google_Service_Gmail_BatchModifyMessagesRequest;
use Google_Service_Gmail_BatchDeleteMessagesRequest;
use Google_Service_Gmail_Label;
use Exception;


class Message
{

	use SendsParameters,
		Filterable;

	public $service;

	public $preload = false;

	public $pageToken;

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
		$this->service = new Google_Service_Gmail($client);
    $this->batchModifyRequest = new Google_Service_Gmail_BatchModifyMessagesRequest();
    $this->batchDeleteRequest = new Google_Service_Gmail_BatchDeleteMessagesRequest();
    $this->labelRequest = new Google_Service_Gmail_Label;
	}

	/**
	 * Returns next page if available of messages or an empty collection
	 *
	 * @return \Illuminate\Support\Collection
	 * @throws \Google_Exception
	 */
	public function next()
	{
		if ($this->pageToken) {
			return $this->all($this->pageToken);
		} else {
			return new MessageCollection([], $this);
		}
	}

	/**
	 * Returns a collection of Mail instances
	 *
	 * @param null|string $pageToken
	 *
	 * @return \Illuminate\Support\Collection
	 * @throws \Google_Exception
	 */
	public function all($pageToken = null)
	{
		if (!is_null($pageToken)) {
			$this->add($pageToken, 'pageToken');
		}

		$messages = [];
		$response = $this->getMessagesResponse();
		$this->pageToken = method_exists( $response, 'getNextPageToken' ) ? $response->getNextPageToken() : null;

		$allMessages = $response->getMessages();

		if (!$this->preload) {
			foreach ($allMessages as $message) {
				$messages[] = new Mail($message, $this->preload);
			}
		} else {
			$messages = $this->batchRequest($allMessages);
		}

		$all = new MessageCollection($messages, $this);

		return $all;
	}

	/**
	 * Returns boolean if the page token variable is null or not
	 *
	 * @return bool
	 */
	public function hasNextPage()
	{
		return !!$this->pageToken;
	}

	/**
	 * Limit the messages coming from the queryxw
	 *
	 * @param  int  $number
	 *
	 * @return Message
	 */
	public function take($number)
	{
		$this->params['maxResults'] = abs((int) $number);

		return $this;
	}

	/**
	 * @param $id
	 *
	 * @return Mail
	 */
	public function get($id)
	{
		$message = $this->getRequest($id);

		return new Mail($message);
	}

	/**
	 * Creates a batch request to get all emails in a single call
	 *
	 * @param $allMessages
	 *
	 * @return array|null
	 */
	public function batchRequest($allMessages)
	{
		$this->client->setUseBatch(true);

		$batch = $this->service->createBatch();

		foreach ($allMessages as $key => $message) {
			$batch->add($this->getRequest($message->getId()), $key);
		}

		$messagesBatch = $batch->execute();

		$this->client->setUseBatch(false);

		$messages = [];

		foreach ($messagesBatch as $message) {
			$messages[] = new Mail($message);
		}

		return $messages;
	}

	/**
	 * Preload the information on each Mail objects.
	 * If is not preload you will have to call the load method from the Mail class
	 * @return $this
	 * @see Mail::load()
	 *
	 */
	public function preload()
	{
		$this->preload = true;

		return $this;
	}

	public function getUser()
	{
		return $this->client->user();
	}

	/**
	 * @param $id
	 *
	 * @return \Google_Service_Gmail_Message
	 */
	private function getRequest($id)
	{
		return $this->service->users_messages->get('me', $id);
	}

	/**
	 * @return \Google_Service_Gmail_ListMessagesResponse|object
	 * @throws \Google_Exception
	 */
	private function getMessagesResponse()
	{
		$responseOrRequest = $this->service->users_messages->listUsersMessages( 'me', $this->params );

		if ( get_class( $responseOrRequest ) === "GuzzleHttp\Psr7\Request" ) {
			$response = $this->service->getClient()->execute( $responseOrRequest, 'Google_Service_Gmail_ListMessagesResponse' );

			return $response;
		}

		return $responseOrRequest;
	}

  //==================ADDED CODES==================

  public function getProfile()
  {
    return $this->service->users->getProfile('me');
  }

    /**
	 * Returns object list of user labels
	 *
	 * @return object
	 */
  public function listLabels()
  {
    return $this->service->users_labels->listUsersLabels('me');
  }

  /**
	 * Returns object info on passed label
	 *
   * @param  string  $label
   * 
	 * @return array|null
	 */
  public function getLabel($label)
  {
    return $this->service->users_labels->get('me', $label);
  }


  /**
	 * Archives emails by removing INBOX label
	 *
   * @param  array  $ids
   * 
	 * @return null
	 */
  public function batchArchive($ids)
  {
    $this->batchModifyRequest->setRemoveLabelIds('INBOX');
    $this->batchModifyRequest->setIds($ids);

    return $this->service->users_messages->batchModify('me', $this->batchModifyRequest);
  }

  public function batchRead($ids)
  {
    $this->batchModifyRequest->setRemoveLabelIds('UNREAD');
    $this->batchModifyRequest->setIds($ids);

    return $this->service->users_messages->batchModify('me', $this->batchModifyRequest);
  }

  public function batchUnread($ids)
  {
    $this->batchModifyRequest->setAddLabelIds('UNREAD');
    $this->batchModifyRequest->setIds($ids);

    return $this->service->users_messages->batchModify('me', $this->batchModifyRequest);
  }

	public function batchImportant($ids)
  {
    $this->batchModifyRequest->setAddLabelIds('IMPORTANT');
    $this->batchModifyRequest->setIds($ids);

    return $this->service->users_messages->batchModify('me', $this->batchModifyRequest);
  }

	public function batchRemoveImportant($ids)
  {
    $this->batchModifyRequest->setRemoveLabelIds('IMPORTANT');
    $this->batchModifyRequest->setIds($ids);

    return $this->service->users_messages->batchModify('me', $this->batchModifyRequest);
  }

	public function batchStar($ids)
  {
    $this->batchModifyRequest->setAddLabelIds('STARRED');
    $this->batchModifyRequest->setIds($ids);

    return $this->service->users_messages->batchModify('me', $this->batchModifyRequest);
  }

	public function batchRemoveStar($ids)
  {
    $this->batchModifyRequest->setRemoveLabelIds('STARRED');
    $this->batchModifyRequest->setIds($ids);

    return $this->service->users_messages->batchModify('me', $this->batchModifyRequest);
  }

  //function still need tests
  public function batchModifyLabels($ids, $addLabelIds = null, $removeLabelIds = null)
  {
    $this->batchModifyRequest->setAddLabelIds($addLabelIds);
    $this->batchModifyRequest->setRemoveLabelIds($removeLabelIds);
    $this->batchModifyRequest->setIds($ids);

    return $this->service->users_messages->batchModify('me', $this->batchModifyRequest);
  }

  public function batchMoveToLabel($ids, $labelIds, $currentInbox)
  {
    $this->batchModifyRequest->setAddLabelIds($labelIds);
    $this->batchModifyRequest->setRemoveLabelIds($currentInbox);
    $this->batchModifyRequest->setIds($ids);

    return $this->service->users_messages->batchModify('me', $this->batchModifyRequest);
  }

	public function batchAddLabel($ids, $labelIds)
  {
    $this->batchModifyRequest->setAddLabelIds($labelIds);
    $this->batchModifyRequest->setIds($ids);

    return $this->service->users_messages->batchModify('me', $this->batchModifyRequest);
  }

	public function batchRemoveLabel($ids, $labelIds)
  {
    $this->batchModifyRequest->setRemoveLabelIds($labelIds);
    $this->batchModifyRequest->setIds($ids);

    return $this->service->users_messages->batchModify('me', $this->batchModifyRequest);
  }

  //WARINING: permantly delete emails
  public function batchDelete($ids)
  {
    $this->batchDeleteRequest->setIds($ids);

    return $this->service->users_messages->batchDelete('me', $this->batchDeleteRequest);
  }

  public function deleteLabel($label_id)
  {

    return $this->service->users_labels->delete('me', $label_id);
  }

  public function createLabel($label_name)
  {
    $this->labelRequest->setName($label_name);

    return $this->service->users_labels->create('me', $this->labelRequest);
  }

  public function updateLabel($label_id, $new_label_name)
  {
    $this->labelRequest->setName($new_label_name);

    return $this->service->users_labels->update('me', $label_id, $this->labelRequest);
  }
  

  public function setLabelListVisibility($label_id, $visibility_op)
  {
    $this->labelRequest->setLabelListVisibility($visibility_op);

    return $this->service->users_labels->update('me', $label_id, $this->labelRequest);
  }

  public function setMessageListVisibility($label_id, $visibility_op)
  {
    $this->labelRequest->setMessageListVisibility($visibility_op);

    return $this->service->users_labels->update('me', $label_id, $this->labelRequest);
  }

  public function label(String $operation, String $label_id, String $label_name = '', String $visibility_op = '')
  {
    switch ($operation) {
      //create delete
      case 'create':
        if($label_name != ''){
          $this->labelRequest->setName($label_name);
          return $this->service->users_labels->create('me', $this->labelRequest);
        }else{
          throw new Exception('label name empty!');
        }
      break;
      
      //update delete
      case 'delete':
        return $this->service->users_labels->delete('me', $label_id);
      break;
      
      //update label
      case 'update':
        if($label_name != ''){
          $this->labelRequest->setName($label_name);
          return $this->service->users_labels->update('me', $label_id, $this->labelRequest);
        }else{
          throw new Exception('label name empty!');
        }
      break;

      //set label visibility in list
      case 'setLblListVis':
        if($visibility_op!= ''){
          $this->labelRequest->setLabelListVisibility($visibility_op);
          return $this->service->users_labels->update('me', $label_id, $this->labelRequest);
        }else{
          throw new Exception('visibility operation empty!');
        }
      break;
      
      //set label visibility in messages list
      case 'setMsgListVis':
        if($visibility_op != ''){
          $this->labelRequest->setMessageListVisibility($visibility_op);
          return $this->service->users_labels->update('me', $label_id, $this->labelRequest);
        }else{
          throw new Exception('visibility operation empty!');
        }
      break;

      default:
        throw new Exception('operation query error or empty!');
      break;
    }
  }
}
