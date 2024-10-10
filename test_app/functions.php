<?php
require_once('connection.php');
session_start();

// SESSIONにtokenを格納する
function setToken()
{
  $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(16));
}
// SESSIONに格納されたtokenのチェックを行い、SESSIONにエラー文を格納する
function checkToken($token)
{
  if (empty($_SESSION['token']) || ($_SESSION['token'] !== $token)) {
    $_SESSION['err'] = '不正な操作です';
    redirectToPostedPage();
  }
}

function unsetError()
{
  $_SESSION['err'] = '';
}

function redirectToPostedPage()
{
  header('Location: ' . $_SERVER['HTTP_REFERER']);
  exit();
}

function e($text)
{
  return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function getTodoList()
{
  return getAllRecords();
}

function getSelectedTodo($id)
{
  return getTodoTextById($id);
}

function savePostedData($post)
{
  checkToken($post['token']); // 追記
  validate($post);
  $path = getRefererPath();
  switch ($path) {
    case '/new.php':
      createTodoData($post['content']);
      break;
    case '/edit.php':
      updateTodoData($post);
      break;
    case '/index.php':
      deleteTodoData($post['id']);
    default:
      break;
  }
}

function validate($post)
{
    if (isset($post['content']) && $post['content'] === '') {
        $_SESSION['err'] = '入力がありません';
        redirectToPostedPage();
    }
}

function getRefererPath()
{
  $urlArray = parse_url($_SERVER['HTTP_REFERER']);
  // var_dump($urlArray);
  // var_dump($_SERVER);
  // exit();
  return $urlArray['path'];
}