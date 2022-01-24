<?php

$name = "名無しさん#koto";
$converted = convert($name);

var_dump($converted);

function convert($name){
    $nth = strpos($name, "#");
    $tripkey = substr($name, $nth + 1);
    $trip = get_cap($tripkey);
    return substr($name, 0, $nth) . $trip;
}

function get_cap($tripkey){
//    $tripkey = '#istrip';? //パスワードとする文字列（# 付き）
//    $tripkey = substr($key, 1);

    $salt = substr($tripkey . 'H.', 1, 2);
    $salt = preg_replace('/[^\.-z]/', '.', $salt);
    $salt = strtr($salt, ':;<=>?@[\\]^_`', 'ABCDEFGabcdef');

    $trip = crypt($tripkey, $salt);
    $trip = substr($trip, -10);
    $trip = '◆' . $trip;

    return $trip;
}