#!/usr/bin/php
<?php
#error_reporting(0);

require_once(dirname(__FILE__) . '/vendor/autoload.php');
require_once(dirname(__FILE__) . '/include/misc_functions.php');

//How many instances of this script are allowed to run.  See no reason for more than 1.
define('ALLOWED_PROCESSES', Config::instance()->intValue('ALLOWED_PROCESSES'));

//Extra output will be provided when script runs.
define('VERBOSE', Config::instance()->boolValue('VERBOSE'));

$settings = (object) parse_ini_file(dirname(__FILE__) . '/cfg/vid.ini', TRUE);
foreach ($settings as $name => $fileConfiguration) {
    if ($name === 'config') {
        continue;
    }
    $startTime = Config::instance()->get('start', $name, NULL);
    $filename = Config::instance()->get('filename', $name, NULL);
    if (empty($startTime) || empty($filename)) {
        throw new Exception('Unable to process invalid group <' . $name . '>');
    }
    $video = new Video($name);
    $shell = $video->getShell(Config::instance()->get('SAVE_DIRECTORY', 'config'));
    $shell->run();
}
