<?php
$allow_method = "PUT";
require_once __DIR__ . "/config.php";

use Main as Request;
use Main as Response;

if (Request::check("PUT")) {
    $data = json_decode(file_get_contents("php://input"));

    $fields = [
        "id" => "Post ID (Required)",
        "title" => "Post title (Optional)",
        "body" => "Post content (Optional)",
        "author" => "Author name (Optional)"
    ];

    if (!isset($data->id) || !is_numeric($data->id)) :
        Response::json(0, 400, "Please provide the valid Post ID and at least one field.", "fields", $fields);
    endif;

    $isEmpty = true;
    $empty_fields =  [];

    foreach((array)$data as $key => $val){
        if (in_array($key, ["title","body","author"])){
            if(!empty(trim($val))){
                $isEmpty = false;
            }
            else{
                array_push($empty_fields, $key);
            }
        }
    }

    if($isEmpty){
        $has_empty_fields = count($empty_fields);
        Response::json(0, 400,
        $has_empty_fields ? "Oops! empty field detected." : "Please provide the Post ID and at least one field.",
        $has_empty_fields ? "empty_fields" : "fields",
        $has_empty_fields ? $empty_fields : $fields);
    }

    $Post = new Post();
    $Post->update($data->id, $data);
}