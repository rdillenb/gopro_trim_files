<?php

use Khill\Duration\Duration;

class Video {

    private $name;
    private $source_filename;
    private $destination_filename;
    private $startTime;
    private $endTime;
    private $duration;
    private $basePath;
    private $pathToFiles;

    public function __construct($name, $fileConfiguration, $basePath, $pathToFiles) {
        $this->name = $name;
        if (!array_key_exists('filename', $fileConfiguration) || !array_key_exists('start', $fileConfiguration)){
            throw new Exception('Invalid filename | start time');
        }
        if (empty($fileConfiguration['filename']) || empty($fileConfiguration['start'])){
            throw new Exception('Missing filename | start time');
        }
	$this->basePath = $basePath;
   	$this->pathToFiles = $pathToFiles;
        
        $this->startTime = $fileConfiguration['start'];
        if (array_key_exists('end', $fileConfiguration)){
            $this->endTime = $fileConfiguration['end'];
        }
        if (isset($this->endTime)) {
            $start = new Duration($this->startTime);
            $end = new Duration($this->endTime);
            $this->duration = $end->toSeconds() - $start->toSeconds();
            echo '[' . __FUNCTION__ . '] duration: ' . $this->duration . "\n";
        }
        $this->source_filename = (!empty($this->basePath) ? $this->basePath : '') . DIRECTORY_SEPARATOR;
	if (!empty($pathToFiles)) {
      	    $this->source_filename .= $pathToFiles . DIRECTORY_SEPARATOR;
        }
        $this->source_filename .= $fileConfiguration['filename'];
        $finfo = pathinfo($this->source_filename);
        $this->destination_filename = (!empty($basePath) ? $basePath : '') . DIRECTORY_SEPARATOR;
        if (!empty($this->pathToFiles)){
            $this->destination_filename .= $this->pathToFiles . DIRECTORY_SEPARATOR;
        }
    }

    public function getSaveFileName($saveDirectory) {
        $finfo = pathinfo($this->source_filename);
        if (!is_dir($saveDirectory)) {
            mkdir($saveDirectory);
        }
        return $saveDirectory . DIRECTORY_SEPARATOR . $this->name . '_' . $finfo['filename'] . '.' . $finfo['extension'];
    }

    public function getShell($saveToDirectory){
        return ExecuteShell::get(
	    sprintf('ffmpeg -y -i %s -ss %s%s -async 1 -strict -2 %s', 
			escapeshellarg($this->source_filename), 
			$this->startTime, 
			isset($this->duration) ? sprintf(' -t %s', $this->duration) : '', 
			escapeshellarg($this->getSaveFileName($saveToDirectory)))
        );
    }
}
