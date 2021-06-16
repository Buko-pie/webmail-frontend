<?php

class Webmail
{
  public function __construct()
  {
    $this->include();
  }

  private function include()
  {
    require __DIR__ . '/vendor/autoload.php';
    include 'GmailConnection.php';
  }

  public function go()
  {
    $conn = new GmailConnection();

    if($conn->is_connected()){
      require_once("Gmail.php");

      $gmail = new Gmail($conn->get_client());
      return $gmail->readLabels();
    }else{
      return $conn->get_unauthenticated_data();
    }
  }
}

$webmail = new Webmail();
echo "<!DOCTYPE html><html>";
echo $webmail->go();
echo "</html>";