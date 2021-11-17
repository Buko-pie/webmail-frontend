<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Dacastro4\LaravelGmail\Facade\LaravelGmail;

class PeopleController extends Controller
{
  public function list(Request $request)
  {
    $pageSize = $request->pageSize ?? 50;
    $pageToken = $request->pageToken ?? '';
    $requestSyncToken = $request->requestSyncToken ?? false;
    $syncToken = $request->syncToken ?? '';

    $optParams = array(
      'pageSize'          => $pageSize,
      'pageToken'         => $pageToken,
      'requestSyncToken'  => $requestSyncToken,
      'syncToken'         => $syncToken,
      'readMask'          => 'names,emailAddresses,phoneNumbers,metadata',
    );

    $result =  LaravelGmail::people()->search($optParams);
    return response()->json($result, 200);
  }

  public function search(Request $request)
  {
    if(isset($request['query'])){
      $pageSize = $request->pageSize ?? 50;
      $optParams = array(
        'query'    => $request['query'],
        'pageSize' => $pageSize,
        'readMask' => 'names,emailAddresses,phoneNumbers,metadata',
      );

      $results =  LaravelGmail::people()->search($optParams);

      $emails = array();

      foreach($results as $result){
        array_push($emails, $result->person->emailAddresses[0]->value);
      }
      return response()->json($emails, 200);
    }else{
      return response()->json("Query Empty", 500);
    }
  }
}
