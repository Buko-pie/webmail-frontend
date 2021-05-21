<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\DummyData;

class generate_dummy_data extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'Dummy:generate_data';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Command description';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return int
   */
  public function handle()
  {
    $dummy_data = [
      [
        'starred' => true, 
        'important' => true, 
        'sender' => 'Gmail',
        'receiver' => 'test',
        'message' => 'test1', 
        'read' => false, 
        'has_attachment' => false
      ],
      [
        'starred' => false, 
        'important' => false, 
        'sender' => 'Test1', 
        'receiver' => 'test',
        'message' => 'test2', 
        'read' => false, 
        'has_attachment' => true
      ],
      [
        'starred' => true, 
        'important' => false, 
        'sender' => 'John Doe', 
        'receiver' => 'test',
        'message' => 'test3', 
        'read' => false, 
        'has_attachment' => true
      ],
      [
        'starred' => false, 
        'important' => true, 
        'sender' => 'Emily Doe', 
        'receiver' => 'test',
        'message' => 'test4', 
        'read' => false, 
        'has_attachment' => false
      ],
      [
        'starred' => true, 
        'important' => false, 
        'sender' => 'James Baxter', 
        'receiver' => 'test',
        'message' => 'test5', 
        'read' => false, 
        'has_attachment' => false
      ]
    ];

    foreach ($dummy_data as $key => $data) {
      $data = DummyData::create($data);
    }
    
    error_log("bruh moment");
    return 0;
  }
}
