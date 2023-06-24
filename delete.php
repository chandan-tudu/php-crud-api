<?php
$allow_method = "DELETE";
require_once __DIR__ . "/config.php";

use Main as Request;
use Main as Response;

if (Request::check("DELETE")) {
    $data = json_decode(file_get_contents("php://input"));
    if (!isset($data->id) || !is_numeric($data->id)) :
        Response::json(0, 400, "Please provide the valid Post ID");
    endif;
    $Post = new Post();
    $Post->delete($data->id);
}