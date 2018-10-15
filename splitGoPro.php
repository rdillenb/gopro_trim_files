#!/usr/bin/php
<?php
#error_reporting(0);

require_once(dirname(__FILE__) . '/vendor/autoload.php');

if (!file_exists(dirname(__FILE__) . '/cfg/vid.ini')) {
   throw new Exception('Configuration settings not found');
}

$settings = (object) parse_ini_file(dirname(__FILE__) . '/cfg/vid.ini', TRUE);
foreach ($settings as $name => $fileConfiguration) {
   if (!array_key_exists('filename', $fileConfiguration) || !array_key_exists('start', $fileConfiguration)){
      continue;
   }
   if (empty($fileConfiguration['filename']) || empty($fileConfiguration['start'])){
      continue;
   }
   $video = new Video($name, $fileConfiguration, $settings->config['basePath'], $settings->config['pathToFiles']);
   $shell = $video->getShell($settings->config['saveDirectory']);
   echo $shell->command . "\n";
}
