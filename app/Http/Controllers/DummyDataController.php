<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Filesystem\Filesystem;

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
        $emails = LaravelGmail::message()->in('inbox')->all();
        $inbox_items_length = count($emails);
        $gmail_data = LaravelGmail::message()->in('starred')->take(10)->preload()->all();
      }else if($request['option'] == 'important_only'){
        $emails = LaravelGmail::message()->in('inbox')->all();
        $inbox_items_length = count($emails);
        $gmail_data = LaravelGmail::message()->in('important')->take(10)->preload()->all();
      }else if($request['option'] == 'sent_emails'){
        $emails = LaravelGmail::message()->in('inbox')->all();
        $inbox_items_length = count($emails);
        $gmail_data = LaravelGmail::message()->in('sent')->take(10)->preload()->all();
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

    // $content = $request->getContent();

    // return response()->json($content, 200);
    if(isset($user) && isset($request['message'])){
      
      
      if($request['option'] == 'new_email'){
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
          foreach ($request['attachments'] as $index => $file) {
            $attachment = json_decode($file);
            
            $path = Storage::disk('storage_attachment')->path($user.'/'.$attachment->filename);
            $mail->attach($path);
          }
        }

        $mail->send();
      }else if($request['option'] == 'reply_email'){
        $mail = LaravelGmail::message()->get($request['email_id']);
        $mail_reference = $mail->getHeader('References');
        $mail_in_reply_to = $mail->getHeader('In-Reply-To');
        $mail_subject = $mail->getHeader('Subject');
        $mail_messege_id = $mail->getHeader('Message-ID');
        
        $mail->to($request['addresses']);
        $mail->message($request['message']);
        $mail->setHeader('In-Reply-to', $mail_messege_id);

        if(isset($request['cc'])){
          $mail->cc($request['cc']);
        }

        if(isset($request['bcc'])){
          $mail->bcc($request['bcc']);
        }

        if(isset($request['attachments'])){
          foreach ($request['attachments'] as $index => $file) {
            $attachment = json_decode($file);
            
            $path = Storage::disk('storage_attachment')->path($user.'/'.$attachment->filename);
            $mail->attach($path);
          }
        }
        
        $mail->reply();
        // return response()->json([
        //   'reply_email',
        //   $request['addresses'],
        //   $request['email_id'],
        //   $mail_reference,
        //   $mail_in_reply_to,
        //   $mail_subject,
        //   $mail_messege_id
        // ], 200);
      }else if($request['option'] == 'forward_email'){
        $mail = LaravelGmail::message()->get($request['email_id']);
        $mail_reference = $mail->getHeader('References');
        $mail_in_reply_to = $mail->getHeader('In-Reply-To');
        $mail_subject = $mail->getHeader('Subject');
        $mail_messege_id = $mail->getHeader('Message-ID');
        
        $mail->subject($mail_subject);
        $mail->to($request['addresses']);
        $mail->message($request['message']);
        // $mail->setHeader('In-Reply-to', $mail_messege_id);

        if(isset($request['cc'])){
          $mail->cc($request['cc']);
        }

        if(isset($request['bcc'])){
          $mail->bcc($request['bcc']);
        }

        if(isset($request['attachments'])){
          foreach ($request['attachments'] as $index => $file) {
            $attachment = json_decode($file);
            
            $path = Storage::disk('storage_attachment')->path($user.'/'.$attachment->filename);
            $mail->attach($path);
          }
        }
        
        $mail->send();
        // return response()->json([
        //   'reply_email',
        //   $request['addresses'],
        //   $request['email_id'],
        //   $mail_reference,
        //   $mail_in_reply_to,
        //   $mail_subject,
        //   $mail_messege_id
        // ], 200);
      }

      

      

      return response()->json('message Sent!', 200);
    }else{
      return response()->json('Empty email content!', 404);
    }
  }

  public function upload_attachment(Request $request)
  { 
    // return response()->json("error", 400);
    $user = LaravelGmail::user();
    if(isset($user)){
      if(isset($request['UploadFiles'])){
        // $request['UploadFiles']->move(public_path('storage/attachments'), $request['filename']);
        // $request['UploadFiles']->move(storage_path('app/public/attachments'), $request['filename']);
        // $request->file('UploadFiles')->storeAs('uploads', $request['filename'], 'storage');
        $store_path = Storage::disk('storage_attachment')->putFileAs($user, $request['UploadFiles'], $request['filename']);
        $url = Storage::disk('storage_attachment')->path($store_path);

        return response()->json([$store_path, $request['filename'], $url], 200);
      }else{
        return response()->json("Empty UploadFiles", 400);
      }
    }else{
      return LaravelGmail::redirect();
    }
    
  }

  public function remove_attachment(Request $request)
  {
    if(isset($request['UploadFiles'])){
      return response()->json($request['UploadFiles'], 200);
    }else{
      return response()->json("Empty UploadFiles", 400);
    }
  }

  public function check_attachment(Request $request)
  {
    $user = LaravelGmail::user();

    if(isset($user)){
      if(isset($request['file'])){
        $subpath = 'app/public/attachments/'.$user.'/opened/'.$request['file'];
        $path = storage_path($subpath);

        if(file_exists($path)){
          $download_link = route('download_attachment', $subpath);

          return response()->json([$path, $request['file'], $download_link], 200);
        }else{
          return response()->json("File does not exist", 405);
        }
      }else{
        return response()->json("Empty filename query", 400);
      }
    }else{
      return LaravelGmail::redirect();
    }
  }

  public function download_attachment(Request $request)
  {
    $user = LaravelGmail::user();

    if(isset($user)){
      if(isset($user)){
        if(isset($request['file'])){
          $subpath = 'app/public/attachments/'.$user.'/opened/'.$request['file'];
          $path = storage_path($subpath);
  
          if(file_exists($path)){
            
            return response()->download($path);
          }else{
            return response()->json("File does not exist", 405);
          }
        }else{
          return response()->json("Empty filename query", 400);
        }
      }else{
        return LaravelGmail::redirect();
      }
    }else{
      return LaravelGmail::redirect();
    }
  }

  public function toggle_dummy_data(Request $request)
  {
    $user = LaravelGmail::user();

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
          $email_data = [
            'email_id' => $email->getId(),
            'from' => $email->getFrom(),
            'to' => $email->getTo()[0],
            'cc' => $email->getHeader("Cc"),
            'subject' => $email->getSubject(),
            'date' => $email->getDate(),
            'recipients' => $email->getTo(),
            'headers' => $email->getHeaders()
          ];

          if(isset($request['with']) && $request['with'] == 'bodyHtml'){
            $bodyHtml = $email->getHtmlBody();
            $attachments_files = [];

            $files = Storage::disk('storage_attachment')->files($user.'/opened');

            if(isset($files)){
              $file = new Filesystem;
              $file->cleanDirectory(storage_path('app/public/attachments/'.$user.'/opened'));
            }
            
            if($email->hasAttachments()){
              $attachments = $email->getAttachments();

              foreach ($attachments as $attachment) {
                $attachment->saveAttachmentTo($path = $user.'/opened', $filename = null, $disk = 'storage_attachment');
                array_push($attachments_files, $attachment->filename);
              }

              // $files = Storage::disk('storage_attachment')->files($user.'/opened');
              // Storage::download(storage_path('app/public/attachments/'.$user.'/opened/'.$attachments[0]->filename));

            }
            // return response()->download(storage_path('app/public/attachments/'.$user.'/opened/'.$attachments[0]->filename));
            return response()->json([
              'bodyHtml' => $bodyHtml,
              'attachments_files' => $attachments_files,
              'email_data' => $email_data
            ], 
              200
            );
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
