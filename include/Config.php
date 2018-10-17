<?php

class Config {

    static $instance;
    private $ini;

    public function Config() {
        if (!isset($this->ini)) {
            $this->ini = parse_ini_file(dirname(__FILE__) . '/../cfg/vid.ini', TRUE);
            if ($this->ini === FALSE) {
                throw new Exception('Configuration settings not found');
            }
        }
    }

    /**
     * 
     * @return \Config
     */
    public static function instance() {
        if (!isset(Config::$instance)) {
            Config::$instance = new Config();
        }
        return Config::$instance;
    }

    public function get($name, $group = FALSE, $defaultValue = FALSE) {
        if (isset($this->ini[$group])) {
            if (isset($this->ini[$group][$name])) {
                return $this->ini[$group][$name];
            }
        } else if (isset($this->ini[$name])) {
            return $this->ini[$name];
        }
        return $defaultValue;
    }

    public function intValue($name, $group = FALSE, $defaultValue = 0) {
        return intval($this->get($name, $group, $defaultValue));
    }

    public function boolValue($name, $group = FALSE, $defaultValue = FALSE) {
        return boolval($this->get($name, $group, $defaultValue));
    }

    public function floatVal($name, $group = FALSE, $defaultValue = 0.0) {
        return floatval($this->get($name, $group, $defaultValue));
    }

}
