<?php

error_reporting(E_ALL | E_STRICT);

define('DS', DIRECTORY_SEPARATOR);
define('ROOT_DIR', dirname(__FILE__).DS);
define('LIB_DIR', ROOT_DIR.'libs'.DS);
define('LOG_DIR', ROOT_DIR.'log'.DS);
define('START_TIME', microtime(true));

require LIB_DIR.'arrangement.php';
require LIB_DIR.'RollingCurl.php';
require LIB_DIR.'functions.php';



/********Domain***********/
$domains = array(
    'qq.com',
);
/************************/

do {
    echo 'What length do you need ? Type a number(greater than zero): ';
    $handle = fopen('php://stdin', 'r');
    $line_num = intval(getInput());
} while ($line_num < 0);

echo 'you haved inputed: ', $line_num, PHP_EOL;

do {
    echo 'select sub-domain type:', PHP_EOL,
        ' 1) alpha [a-z]', PHP_EOL,
        ' 2) number [0-9]', PHP_EOL,
        ' 3) both [a-z0-9]', PHP_EOL; 
    $input_num = intval(getInput());
    $num = range(1, 3);
} while (!in_array($input_num, $num));

switch ($input_num) {
    case 1:
        $words = range('a', 'z');
        break;
    case 2:
        $words = range(0, 9);
        break;
    case 3:
        $words = array_merge(range('a', 'z'), range(0, 9));
        break;
    default:
        break;
}

$obj = new Arrangement($words, $line_num);
$sub_domains = $obj->getAll();

$rc = new RollingCurl('requestCallback');
$rc->window_size = 20;
$rc->timeout = 5;
$rc->options = array(
    CURLOPT_HEADER => true,
    CURLOPT_NOBODY => true,
);

$all_sub_domains = array();

foreach ($domains as $domain) {
    foreach ($sub_domains as $index => $sub_domain) {
        $sub_domain = join('', $sub_domain).'.'.trim($domain);

        $request = new RollingCurlRequest($sub_domain);
        $rc->add($request);

        $all_sub_domains[$sub_domain] = array(
            'index' => $index + 1,
        );

        echo $index + 1, ' ', $sub_domain, PHP_EOL;
    }
}

$rc->execute();

echo 'run time: ', microtime(true) - START_TIME, ' seconds', PHP_EOL;