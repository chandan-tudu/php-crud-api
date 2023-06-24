<?php
if(!isset($allow_method)) $allow_method = "GET";
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: $allow_method");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");

require_once __DIR__ . "/classes/Main.php";
require_once __DIR__ . "/classes/Post.php";