<?php
session_start();
//отчистка SESSION
$_SESSION['name'] = '';

//отчистка COOKIE
unset($_COOKIE['name']);
setcookie('lk', null, -1, '/');

//вывод в формате json
$response_array['status'] = 'successfully';
echo json_encode($response_array);
?>