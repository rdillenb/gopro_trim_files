<?php

class Duration {

    public $days;
    public $hours;
    public $minutes;
    public $seconds;
    public $hoursPerDay;
    private $output;
    private $daysRegex;
    private $hoursRegex;
    private $minutesRegex;
    private $secondsRegex;

    public function __construct($duration= null, $hoursPerDay = 24) {
        $this->days = 0;
        $this->hours = 0;
        $this->minutes = 0;
        $this->seconds = 0;
        $this->output = '';
        $this->daysRegex = '/([0-9]{1,2})\s?(?:d|D)/';
        $this->hoursRegex = '/([0-9]{1,2})\s?(?:h|H)/';
        $this->minutesRegex = '/([0-9]{1,2})\s?(?:m|M)/';
        $this->secondsRegex = '/([0-9]{1,2}(\.\d+)?)\s?(?:s|S)/';
        $this->hoursPerDay = $hoursPerDay;
        if (!is_null($duration)) {
            $this->parse($duration);
        }
    }

    public function parse($duration) {
        $this->reset();
        if (is_numeric($duration)) {
            $this->seconds = (float)$duration;
            if ($this->seconds >= 60) {
                $this->minutes = (int)floor($this->seconds / 60);
                // count current precision
                $precision = 0;
                if (($delimiterPos = strpos($this->seconds, '.')) !== false) {
                    $precision = strlen(substr($this->seconds, $delimiterPos + 1));
                }
                $this->seconds = (float)round(($this->seconds - ($this->minutes * 60)), $precision);
            }
            if ($this->minutes >= 60) {
                $this->hours = (int)floor($this->minutes / 60);
                $this->minutes = (int)($this->minutes - ($this->hours * 60));
            }
            if ($this->hours >= $this->hoursPerDay) {
                $this->days = (int)floor($this->hours / $this->hoursPerDay);
                $this->hours = (int)($this->hours - ($this->days * $this->hoursPerDay));
            }
            return $this;
        }
        if (preg_match('/\:/', $duration)) {
            $parts = explode(':', $duration);
            if (count($parts) == 2) {
                $this->minutes = (int)$parts[0];
                $this->seconds = (float)$parts[1];
            } else {
                if (count($parts) == 3) {
                    $this->hours = (int)$parts[0];
                    $this->minutes = (int)$parts[1];
                    $this->seconds = (float)$parts[2];
                }
            }
            return $this;
        }
        if (preg_match($this->daysRegex, $duration) ||
            preg_match($this->hoursRegex, $duration) ||
            preg_match($this->minutesRegex, $duration) ||
            preg_match($this->secondsRegex, $duration)) {
            if (preg_match($this->daysRegex, $duration, $matches)) {
                $this->days = (int)$matches[1];
            }
            if (preg_match($this->hoursRegex, $duration, $matches)) {
                $this->hours = (int)$matches[1];
            }
            if (preg_match($this->minutesRegex, $duration, $matches)) {
                $this->minutes = (int)$matches[1];
            }
            if (preg_match($this->secondsRegex, $duration, $matches)) {
                $this->seconds = (float)$matches[1];
            }
            return $this;
        }
        return false;
    }

    private function reset()
    {
        $this->output = '';
        $this->seconds = 0;
        $this->minutes = 0;
        $this->hours = 0;
        $this->days = 0;
    }
    /**
     * Returns the output of the Duration object and resets.
     *
     * @access private
     * @return string
     */
    private function output()
    {
        $out = $this->output;
        $this->reset();
        return $out;
    }

}
