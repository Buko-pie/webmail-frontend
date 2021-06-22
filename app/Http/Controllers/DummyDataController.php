<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DummyData;
use Dacastro4\LaravelGmail\Facade\LaravelGmail;
use Dacastro4\LaravelGmail\Services\Message\Mail;

class DummyDataController extends Controller
{
  public function get_dummy_data(Request $request)
  {
    if(isset($request['option'])){
      $user = LaravelGmail::user();
     

      if($request['option'] == 'get_all'){
        $repackaged_data = [];
        $gmail_data = LaravelGmail::message()->in('inbox')->preload()->all();
        // $gmail_data = $gmail_data->next();

        $data_1 = $gmail_data[0]->hasAttachments();
        // $dummy_data = DummyData::orderBy('created_at', 'DESC')->get();
      }else if($request['option'] == 'get_next_page'){
        $gmail_data = $gmail_data->next();
      }else if($request['option'] == 'starred_only'){
        // $dummy_data = DummyData::where('starred', true)->orderBy('created_at', 'DESC')->get();
      }else if($request['option'] == 'important_only'){
        // $dummy_data = DummyData::where('important', true)->orderBy('created_at', 'DESC')->get();
      }
    
      if(isset($gmail_data)){
        foreach($gmail_data as $data){
          array_push($repackaged_data,[
            'id' => $data->id,
            'sender' => $data->getFromName(),
            'receiver' => $user,
            'message' => $data->getSubject(),
            'plain_text' => $data->getPlainTextBody(),
            'starred' => in_array('STARRED', $data->getLabels()),
            'important' => in_array('IMPORTANT', $data->getLabels()),
            'read' => !in_array('UNREAD', $data->getLabels()),
            'labels' => $data->getLabels(),
            'has_attachment' => $data->hasAttachments(),
            'attachment_link' => null,
            'created_at' => $data->getDate()
          ]);
        }

        return response()->json(['gmail_data' => $gmail_data, 'data_1' => $data_1, 'repackaged_data' => $repackaged_data], 200);
      }else{
        return response()->json(['error_msg' => 'Nothing Found'], 400);
      }
    }
  }

  public function toggle_dummy_data(Request $request)
  {
    if(isset($request['id'])){
  
      $value = ($request['value'] == 'true') ? 1 : 0;
      $email = LaravelGmail::message()->get($request['id']);

      if(isset($email)){
        if($request['column'] == 'starred'){
          if($value){
            $email->addStar();
          }else{
            $email->removeStar();
          }
          return response()->json(['email' => $email], 200);
        }else if($request['column'] == 'important'){
          if($value){
            $email->markAsImportant();
          }else{
            $email->markAsNotImportant();
          }
          return response()->json(['email' => $email], 200);
        }else if($request['column'] == 'read'){

          if($value){
            $email->markAsRead();
          }else{
            $email->markAsUnread();
          }

          if(isset($request['with']) && $request['with'] == 'bodyHtml'){
            $bodyHtml = $email->getHtmlBody();
            return response()->json(['bodyHtml' => $bodyHtml], 200);
          }else{
            return response()->json(['email' => $email], 200);
          }
          
          
        }
      }else{
        return response()->json(['message' => 'email not found'], 404);
      }
    }
  }

  public function toggle_many_dummy_data(Request $request)
  {
    switch ($request['option']) {
      case 0:
        //Mark as read

        foreach ($request['dataIDs'] as $id) {
          $email = LaravelGmail::message()->get($id)->markAsRead();
        }
      break;

      case 1:
        //Mark as unread

        foreach ($request['dataIDs'] as $id) {
          $email = LaravelGmail::message()->get($id)->markAsUnread();
        }
      break;

      case 2:
        //Mark as important

        $data_update = DummyData::whereIn('id', $request['dataIDs'])
        ->update(['important' => 1]);
      break;

      case 3:
        //Mark as not important

        $data_update = DummyData::whereIn('id', $request['dataIDs'])
        ->update(['important' => 0]);
      break;

      case 4:
        //Add star

        $data_update = DummyData::whereIn('id', $request['dataIDs'])
        ->update(['starred' => 1]);
      break;

      case 5:
        //Remove star

        $data_update = DummyData::whereIn('id', $request['dataIDs'])
        ->update(['starred' => 0]);
      break;

      case 6:
        //Mute
      break;

      case 7:
        //Forward as attachment

      break;
      
      default:
        return response()->json(['message' => "Somthing went wrong!", 400]);
      break;
    }
  }
}
