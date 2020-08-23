<?php

/**
 * Return all posts from Data Base
 * @param DataBase $pdo
 */
function getPosts(DataBase $pdo ) {
    $posts = $pdo->query( 'SELECT * FROM articles' );
    echo json_encode( $posts );
}

/**
 * Return Post from Data Base by ID
 * @param DataBase $pdo
 * @param int $id
 */
function getPostById(DataBase $pdo, int $id ) {
    $post = $pdo->query( 'SELECT * FROM articles WHERE id = ?', array( $id ) );
    if ( empty( $post ) ) {
        http_response_code( 404 );
        $response = [
            'status' => false,
            'msg' => 'Пост с таким идентификатором отсутствует'
        ];
        echo json_encode( $response );
    }
    echo json_encode( $post );
}

/**
 * Add new post to Data Base if content of post isn`t empty
 * if content is empty -> return response with error message
 * @param DataBase $pdo
 * @param array $post
 */
function addPost(DataBase $pdo, array $post ) {
    if ( empty( $post['title'] ) || empty( $post['body'] ) ) {
        http_response_code( 204 );
        $response = [
            'status' => false,
            'msg' => 'Нет содержимого'
        ];
        echo json_encode( $response );
    }
    else {
        $pdo->execute( 'INSERT articles( title, body ) VALUES ( ?, ? )', array( $post['title'], $post['body'] ) );
        $post_id = $pdo->query( 'SELECT id FROM articles ORDER BY id DESC LIMIT 1' );
        http_response_code( 201 );
        $response = [
            'status' => true,
            'post_id' => $post_id[0]['id']
        ];
        echo json_encode( $response );
    }

}

/**
 * Check post existence in Data Base by id
 * @param DataBase $pdo
 * @param int $post_id
 * @return bool
 */
function checkPostExistence(DataBase $pdo, int $post_id ): bool
{
    $find_post = $pdo->query( 'SELECT * FROM articles WHERE id = ?', array( $post_id ) );
    if ( empty( $find_post ) ) {
        return false;
    }
    else {
        return true;
    }
}

/**
 * Update post content in Data Base by Id
 * If post does not found or post content is empty, then return response with error message
 * @param DataBase $pdo
 * @param int $post_id
 * @param array $post
 */
function updatePost(DataBase $pdo, int $post_id, array $post ) {
    if ( empty( $post['title'] ) || empty( $post['body'] ) ) {
        http_response_code( 204 );
        $response = [
            'status' => false,
            'msg' => 'Нет содержимого'
        ];
        echo json_encode( $response );
    }
    else {
        if ( checkPostExistence( $pdo, $post_id ) ) {
            $pdo->execute( 'UPDATE articles SET title = ?, body = ? WHERE id = ?',
            array( $post['title'], $post['body'], $post_id ) );
            $response['status'] = true;
            echo json_encode( $response );
        }
        else {
            http_response_code( 404 );
            $response = [
                'status' => false,
                'msg' => 'Пост с таким идентификатором отсутствует'
            ];
            echo json_encode( $response );
        }
    }
}

/**
 * Delete post from Data Base by ID
 * If post does not found, then return response with error message
 * @param DataBase $pdo
 * @param int $post_id
 */
function deletePost(DataBase $pdo, int $post_id ) {
    if ( checkPostExistence( $pdo, $post_id ) ) {
        $pdo->execute('DELETE FROM articles WHERE id = ?', array( $post_id ));
        $response['status'] = true;
        echo json_encode( $response );
    }
    else {
        http_response_code( 404 );
        $response = [
            'status' => false,
            'msg' => 'Пост с таким идентификатором отсутствует'
        ];
        echo json_encode( $response );
    }
}
