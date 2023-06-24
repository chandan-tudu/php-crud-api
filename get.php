<?php
require_once __DIR__ . "/config.php";

use Main as Request;

if (Request::check("GET")) {
    $Post = new Post();
    if (isset($_GET['id'])) $Post->read(trim($_GET['id']));
    $Post->read();
}