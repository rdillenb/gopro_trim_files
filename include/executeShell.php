<?php

class ExecuteShell {

  var $results;
  var $returnStatus;
  var $timeToRun = 0;
  var $command;

  public function ExecuteShell($command) {
     $this->command = $command;
     $this->results = array();
     $this->returnStatus = -1;
  }

  public static function get($command) {
     return new ExecuteShell($command);
  }


  public function run(){
     $st = microtime(TRUE);
     exec($this->command, $this->results, $this->returnStatus);
     $this->timeToRun = microtime(TRUE) - $st;
     return $this;
  }

}
