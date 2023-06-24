<?php
$allow_method = "POST";
require_once __DIR__ . "/config.php";

use Main as Request;
use Main as Response;

if (Request::check("POST")) {
    $data = json_decode(file_get_contents("php://input"));
    if (
        !isset($data->title) ||
        !isset($data->body) ||
        !isset($data->author)
    ) :
        $fields = [
            "title" => "Post title",
            "body" => "Post content",
            "author" => "Author name"
        ];
        Response::json(0, 400, "Please fill all the required fields", "fields", $fields);

    elseif (
        empty(trim($data->title)) ||
        empty(trim($data->body)) ||
        empty(trim($data->author))
    ) :
        $fields = [];
        foreach($data as $key => $val){
            if(empty(trim($val))) array_push($fields, $key); 
        }
        Response::json(0, 400, "Oops! empty field detected.","empty_fields", $fields);

    else :
        $Post = new Post();
        $Post->create($data->title, $data->body, $data->author);
    endif;
}