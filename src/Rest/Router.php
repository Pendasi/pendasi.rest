<?php
namespace Pendasi\Rest\Rest;

class Router {
    public static function api(string $controller){
        $method = $_SERVER['REQUEST_METHOD'];
        $id = $_GET['id'] ?? null;

        $c = new $controller;

        switch($method){
            case "GET":
                $id ? $c->show($id) : $c->index();
                break;
            case "POST":
                $c->store($_POST);
                break;
            case "PUT":
                parse_str(file_get_contents("php://input"), $putData);
                $c->update($id, $putData);
                break;
            case "DELETE":
                $c->delete($id);
                break;
            default:
                http_response_code(405);
                echo json_encode(["success"=>false,"message"=>"Method Not Allowed"]);
        }
    }
}