#!/usr/bin/php
<?php
require_once('vendor/autoload.php');

if (!file_exists(dirname(__FILE__) . '/cfg/vid.ini')) {
   throw new Exception('Configuration settings not found');
}

$settings = (object) parse_ini_file('cfg/vid.ini', TRUE);
foreach ($settings as $name => $arr) {
   if (!array_key_exists('filename', $arr) || !array_key_exists('start', $arr)){
      continue;
   }
   if (empty($arr['filename']) || empty($arr['start'])){
      continue;
   }
   $video = new Video($name, $arr, $settings->config['basePath'], $settings->config['pathToFiles']);
   $shell = $video->getShell($settings->config['saveDirectory']);
   echo $shell->command . "\n";
}
