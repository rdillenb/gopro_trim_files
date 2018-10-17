<?php

function capitalize($output) {
    return ucfirst(strtolower($output));
}

function echo_output($output) {
    echo "[" . date('Y-m-d H:i:s', time()) . " " . date_default_timezone_get() . "] ${output} \n";
}

function getSelf() {
    $arr = explode('/', $_SERVER['PHP_SELF']);
    return str_replace('.php', '', array_pop($arr));
}

function check_processes($processname) {
    //ALLOWED_PROCESSES
    $num_processes = exec("export TERM=xterm;/bin/ps axf | /bin/grep ${processname} | /bin/grep -v grep | grep -v \"/bin/sh\" |  /usr/bin/wc -l");

    //Now we compare to the number of allowed processes we are allowed to have.  If we exceeed that amount we will exit the script.  Most likely only 1 is allowed.
    if ($num_processes > ALLOWED_PROCESSES) {
        echo_output("WARNING:  The number of detected processes ('${num_processes}') has exceeded the allowed ('" . ALLOWED_PROCESSES . "') for process '${processname}'.  The script will now exit.");
        exit(0);
    } else if (VERBOSE) {
        echo_output("OKAY:  The number of processes ('${num_processes}') falls within the allowed limit ('" . ALLOWED_PROCESSES . "').  Script will continue.");
    }

    //Just in case there is a need for it.
    return $num_processes;
}
