<?php

require 'vendor/autoload.php';
require "Ocr.php";

//$img = "https://xxx.xxx.jpeg";
$img = "./demo-img/demo.png";

$ocr = new Ocr($img);

//$ocr->rotate(270);

$result = $ocr->getRecognition();
echo $result;

//$ocr->save();