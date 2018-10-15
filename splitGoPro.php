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
continue;
   $startTime = strtotime($arr['start']);
   if (array_key_exists('end', $arr)){
      $endTime = strtotime($arr['end']);
   }
   if (isset($endTime)){
      $fileDuration = gmdate("H:i:s", $endTime - $startTime);
   }
   $finfo = pathinfo($arr['filename']);
   $file = (!empty($settings->config['basePath']) ? $settings->config['basePath'] : dirname(__FILE__)) . DIRECTORY_SEPARATOR;
   if (!empty($settings->config['pathToFiles'])) {
      $file .= $settings->config['pathToFiles'] . DIRECTORY_SEPARATOR;
   }
   $file .= $arr['filename'];
   $saveFileName = dirname(__FILE__) . DIRECTORY_SEPARATOR . $settings->config['saveDirectory'] . DIRECTORY_SEPARATOR . $name . '_' . $finfo['filename'] . '.' . $finfo['extension'];
   $shell = ExecuteShell::get( sprintf('ffmpeg -i %s -ss %s%s -async 1 %s', escapeshellarg($file), $arr['start'], isset($t) ? sprintf(' -t %s', $t) : '', escapeshellarg($saveFileName)));
   unset($t);
   if (file_exists($arr['filename'])){
      if (!is_dir('final')){
          mkdir('final');
      }
      $shell->run();
      echo 'Completed in ' . $shell->timeToRun .'s (' . $shell->returnStatus . ') Saved file ' . $saveFileName . "\n";
   }
}
