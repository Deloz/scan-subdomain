<?php

//callback process
function requestCallback($response, $info, $request) {
    global $all_sub_domains;

    $all_sub_domains[$request->url]['http_code'] = $info['http_code'];
    $tip = '['.date('Y-m-d H:i:s', time()).'] '
            .'current index: '
            .str_pad($all_sub_domains[$request->url]['index'], 7, ' ', STR_PAD_BOTH)
            .'   http_code: '
            .str_pad($info['http_code'], 3, ' ', STR_PAD_BOTH)
            .' => '
            .$request->url."\r\n";
    echo $tip;

    writeLog(LOG_DIR.date('Y-m-d', time()).'.log', $tip);

}

function writeLog($log_file, $data) {
    $dir = dirname($log_file);
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }
    
    file_put_contents($log_file, $data, LOCK_EX | FILE_APPEND);
}

function getInput() {
    $handle = fopen('php://stdin', 'r');
    return fgets($handle);
}