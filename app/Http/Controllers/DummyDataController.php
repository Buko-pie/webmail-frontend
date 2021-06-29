<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DummyData;
use Dacastro4\LaravelGmail\Facade\LaravelGmail;
use Dacastro4\LaravelGmail\Services\Message\Mail;
use Dacastro4\LaravelGmail\Services\Message\Attachment;

class DummyDataController extends Controller
{
  public function get_dummy_data(Request $request)
  {
    if(isset($request['option'])){
      $user = LaravelGmail::user();
      $repackaged_data = [];
      $inbox_items_length = null;

      if($request['option'] == 'get_all'){
        $emails = LaravelGmail::message()->in('inbox')->all();
        $inbox_items_length = count($emails);
        $gmail_data = LaravelGmail::message()->in('inbox')->take(10)->preload()->all();
        // $gmail_data = $gmail_data->next();

        // $data_1 = $gmail_data[0]->hasAttachments();
        // $dummy_data = DummyData::orderBy('created_at', 'DESC')->get();
      }else if($request['option'] == 'get_next_page'){
        $gmail_data = LaravelGmail::message()->in('inbox')->take(10)->preload()->all();

        for ($i = 1; $i <= $request['page']; $i++) { 
          $gmail_data = $gmail_data->next();
        }

        // return response()->json(['gmail_data' => $gmail_data], 200);
        // $gmail_data = $gmail_data->next();
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

        return response()->json(['gmail_data' => $gmail_data, 'repackaged_data' => $repackaged_data, 'inbox_items_length' => $inbox_items_length], 200);
      }else{
        return response()->json(['error_msg' => 'Nothing Found'], 400);
      }
    }
  }

  public function send_mail(Request $request)
  {
    $user = LaravelGmail::user();
    if(isset($user) && isset($request['message'])){
      
      $mail = new Mail;

      $mail->to($request['addresses']);
      $mail->from($user);
      $mail->subject($request['subject']);
      $mail->message($request['message']);
      
      if(isset($request['cc'])){
        $mail->cc($request['cc']);
      }

      if(isset($request['bcc'])){
        $mail->bcc($request['bcc']);
      }

      if(isset($request['attachments'])){
        $attachment = new Attachment;
        //stopped here
       
      }

      $mail->send();

      return response()->json('message Sent!', 200);
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
