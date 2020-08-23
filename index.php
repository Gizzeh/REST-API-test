<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Credentials: true');
header('Content-type: json/application');

    require_once "database.php";
    require_once "functions.php";

    $method = $_SERVER['REQUEST_METHOD'];
    $q = $_GET['q'];
    $result = explode('/', $q);
    $params = [
        'type' => $result[0],
        'id' => $result[1]
    ];

    if ($params['type'] !== 'posts') die('Неверные параметры');

    switch( $method ) {
        case 'GET':
            if ( empty( $params['id'] ) ) {
                getPosts( $pdo );
            }
            else {
                getPostById( $pdo, $params['id'] );
            }
            break;

        case 'POST':
            addPost( $pdo, $_POST );
            break;

        case "PATCH":
            if ( empty( $params['id'] ) ) {
                http_response_code( 400 );
                $response = [
                    'status' => false,
                    'msg' => 'Идентификатор не задан'
                ];
                echo json_encode( $response );
            }
            else {
                $data = file_get_contents('php://input');
                $data = json_decode( $data, true );
                updatePost( $pdo, $params['id'], $data );
            }
            break;

        case "DELETE":
            if ( empty( $params['id'] ) ) {
                http_response_code( 400 );
                $response = [
                    'status' => false,
                    'msg' => 'Идентификатор не задан'
                ];
                echo json_encode( $response );
            }
            else {
                deletePost( $pdo, $params['id'] );
            }
    }


