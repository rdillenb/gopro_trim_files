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
    private $encodingCommand;

    public function __construct($name) {
        $this->basePath = Config::instance()->get('BASE_DIR', 'config', dirname(__FILE__));
        $this->pathToFiles = Config::instance()->get('PATH_TO_FILES', 'config', dirname(__FILE__));
        $this->name = $name;
        $this->encodingCommand = Config::instance()->get('ENCODE_COMMAND', 'config', NULL);
        if (is_null($this->encodingCommand)) {
            throw new Exception('Invalid encoding command NULL');
        }
        $this->startTime = Config::instance()->get('start', $name, NULL);
        $this->endTime = Config::instance()->get('end', $name, NULL);
        $start = new Duration($this->startTime);
        if (!empty($this->endTime)) {
            $end = new Duration($this->endTime);
            $this->duration = $end->toSeconds() - $start->toSeconds();
            echo_output('[VIDEO] duration: ' . $this->duration);
        }
        $this->source_filename = (!empty($this->basePath) ? $this->basePath : '') . DIRECTORY_SEPARATOR;
        if (!empty($this->pathToFiles)) {
            $this->source_filename .= $this->pathToFiles . DIRECTORY_SEPARATOR;
        }
        $this->source_filename .= Config::instance()->get('filename', $name);
        $this->destination_filename = (!empty($this->basePath) ? $this->basePath : '') . DIRECTORY_SEPARATOR;
        if (!empty($this->pathToFiles)) {
            $this->destination_filename .= $this->pathToFiles . DIRECTORY_SEPARATOR;
        }
        echo_output('[VIDEO] SRC="' . $this->source_filename . '"');
    }

    public function getSaveFileName($saveDirectory) {
        $finfo = pathinfo($this->source_filename);
        if (!is_dir($saveDirectory)) {
            mkdir($saveDirectory);
        }
        $saveFile = $saveDirectory . DIRECTORY_SEPARATOR . $this->name . '_' . $finfo['filename'] . '.' . $finfo['extension'];
        echo_output('[' . __FUNCTION__ . '] ' . $saveFile);
	return $saveFile;
    }

    public function getShell($saveToDirectory) {
        return ExecuteShell::get(sprintf($this->encodingCommand, escapeshellarg($this->source_filename), $this->startTime, isset($this->duration) ? sprintf(' -t %s', $this->duration) : '', escapeshellarg($this->getSaveFileName($saveToDirectory))));
    }

    public function destinationFileExists() {
        return file_exists($this->destination_filename);
    }

}
