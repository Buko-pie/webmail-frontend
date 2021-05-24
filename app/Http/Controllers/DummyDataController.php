<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DummyData;

class DummyDataController extends Controller
{
  public function get_dummy_data(Request $request)
  {
    if(isset($request['option'])){
      if($request['option'] == 'get_all'){
        $dummy_data = DummyData::all();
      }else if($request['option'] == 'starred_only'){
        $dummy_data = DummyData::where('starred', true)->get();
      }else if($request['option'] == 'important_only'){
        $dummy_data = DummyData::where('important', true)->get();
      }

      if(isset($dummy_data)){
        return response()->json(['dummy_data' => $dummy_data, 200]);
      }else{
        return response()->json(['error_msg' => 'dummy_data empty', 400]);
      }
    }
  }

  public function toggle_dummy_data(Request $request)
  {
    if(isset($request['id'])){
      $value = ($request['value'] == 'true') ? 1 : 0;

      if($request['column'] == 'starred'){
        $data_update = DummyData::find($request['id']);
        if(isset($data_update)){
          $data_update->starred = $value;
          $data_update->save();
        }else{
          return response()->json(['error_msg' => 'data not found', 404]);
        }
        
      }else if($request['column'] == 'important'){
        $data_update = DummyData::find($request['id']);
        if(isset($data_update)){
          $data_update->important = $value;
          $data_update->save();
        }else{
          return response()->json(['error_msg' => 'data not found', 404]);
        }
      }else if($request['column'] == 'read'){
        $data_update = DummyData::find($request['id']);
        if(isset($data_update)){
          if($value == 1){
            $data_update->read = 1;
            $data_update->save();
          }else{
            $data_update->read = 0;
            $data_update->save();
          }
        }else{
          return response()->json(['error_msg' => 'data not found', 404]);
        }
      }

      if(isset($data_update)){
        return response()->json(['data_update' => $data_update, 200]);
      }else{
        return response()->json(['error_msg' => 'dummy_data empty', 400]);
      }
    }
  }
}
