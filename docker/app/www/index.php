<?php

//define('SERVER', 'sparql'); # switch resolver to pure sparql
header('Access-Control-Allow-Credentials: true', true);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');

$payload = null;

switch($_SERVER['REQUEST_METHOD']) {
    case "POST":
        if (isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] === 'application/json') {
            $rawBody = file_get_contents('php://input');
            $requestData = json_decode($rawBody ?: '', true);
        } else {
            $requestData = $_POST;
        }
        break;
    case "GET":
        $requestData = $_GET;
        break;
    default:
        exit;
}

$payload = isset($requestData['query']) ? $requestData['query'] : null;

require_once __DIR__.'/../vendor/autoload.php';

$processor = \Datatourisme\Api\DatatourismeApi::create('http://blazegraph:9999/blazegraph/namespace/kb/sparql');
$response = $processor->process($payload);
header('Content-Type: application/json');
echo json_encode($response);
exit;
