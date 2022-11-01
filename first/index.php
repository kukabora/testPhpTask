<?php
require_once('init.php');
$testingClass = new Init();
$resultOfGetQuery = $testingClass->get();

foreach ($resultOfGetQuery as $key => $value) {
    foreach ($value as $key => $innerValue) {
        print($key . ":" . $innerValue . "|");
    }
    print("\n");
}
