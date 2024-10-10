<?php
require_once('config.php');

// PDOクラスのインスタンス化
function connectPdo()
{
    try {
        return new PDO(DSN, DB_USER, DB_PASSWORD);
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }
}

function createTodoData($todoText)
{
    $dbh = connectPdo();
    $sql = 'INSERT INTO todos (content) VALUES (:todoText)'; //編集
    $stmt = $dbh->prepare($sql); //追記
    $stmt->bindValue(':todoText', $todoText, PDO::PARAM_STR); //追記
    $stmt->execute(); //追記
}

function getAllRecords(){
    $dbh = connectPdo();
    $sql = 'SELECT * FROM todos WHERE deleted_at IS NULL';
    return $dbh->query($sql)->fetchAll();
}

// update
function updateTodoData($post){
    $dbh = connectPdo();
    $sql = 'UPDATE todos SET content = :todoText WHERE id = :id'; //編集
    $stmt = $dbh->prepare($sql); //編集
    $stmt->bindValue(':todoText', $post['content'], PDO::PARAM_STR); //編集
    $stmt->bindValue(':id', (int) $post['id'], PDO::PARAM_INT); //編集
    $stmt->execute(); //編集
}

function getTodoTextById($id){
    $dbh = connectPdo();
    $sql = "SELECT * FROM todos WHERE deleted_at IS NULL AND id = :id";
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':id', (int) $id, PDO::PARAM_INT); //編集
    $stmt->execute();
    // var_dump($stmt->fetch(PDO::FETCH_ASSOC));
    // exit();
    $data = $stmt->fetch(PDO::FETCH_ASSOC) ;
    return $data['content'];
}

function deleteTodoData($id)
{
    $dbh = connectPdo();
    $now = date('Y-m-d H:i:s');
    $sql = "UPDATE todos SET deleted_at = :now  WHERE id = :id ";
    $stmt = $dbh->prepare($sql);
    // var_dump($stmt);
    // exit();
    $stmt->bindValue(':now', $now, PDO::PARAM_STR); //編集
    $stmt->bindValue(':id', (int) $id, PDO::PARAM_INT); //編集
    $stmt->execute();

}