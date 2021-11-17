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

    return response()->json([
      'emails'=>LaravelGmail::people()->list($optParams)
    ], 200);
  }

  public function search(Request $request)
  {
    $pageSize = $request->pageSize ?? 50;
    $optParams = array(
      'query'    => $request->query,
      'pageSize' => $pageSize,
      'readMask' => 'names,emailAddresses,phoneNumbers,metadata',
    );

    return response()->json([
      'emails'=> LaravelGmail::people()->search($optParams)
    ], 200);
  }
}
