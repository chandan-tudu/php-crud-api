<?php
class Main
{
    // Checking the Request Method
    static function check($req)
    {
        if ($_SERVER["REQUEST_METHOD"] === $req) {
            return true;
        }
        static::json(0, 405, "Invalid Request Method. HTTP method should be $req");
    }

    // Returns the response in JSON format
    static function json(int $ok, $status, $msg, $key = false, $value = false)
    {
        $res = ["ok" => $ok];
        if ($status !== null){
            http_response_code($status);
            $res["status"] = $status;
        }
        if ($msg !== null) $res["message"] = $msg;
        if($value){
            if($key){
                $res[$key] = $value;
            }
            else{
                $res["data"] = $value;
            }
        }
        echo json_encode($res);
        exit;
    }

    // Returns the 404 Not found
    static function _404(){
        static::json(0,404,"Not Found!");
    }
}