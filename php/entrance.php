<?php
if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
{
// Если к нам идёт Ajax запрос
    session_start();
    $_SESSION['error'] = '';
    $_SESSION['name'] = '';
    $_COOKIE["name"] = '';

    $errors = Checking_Data_For_Errors($_POST);
    //Проверка на незаполненость хотя бы одного поля
    if(!empty($errors))
    {
        //Добавление всех ошибок в $_SESSION['error'] для дальнейшего вывода на index.php
        foreach($errors as $err)
            $_SESSION['error'] .= $err;

        //Вывод в формате json
        $response_array['status'] = 'not successfully';
        echo json_encode($response_array);
    }
    //Если ошибок нет, добавление в bd.json
    else
    {
        require 'DataBase.php';

        $user = new DataBase($_POST,'bd.json');

        //Добавляем данные с функции в переменную, для дальнейшей проверки
        $check = $user->Read($_POST);

        //если переменная определенная строка, добавляем ее в 'error', в противном случае добавляем ее в 'name'
        if(!Fullness_Of_Fields($check))
        {
            //Добавление определенной ошибки в $_SESSION['error']
            $_SESSION['error'] = $check;

            //вывод в формате json
            $response_array['status'] = 'not successfully';
            echo json_encode($response_array);
        }
        else
        {
            //сохранение имени пользователя
            $_SESSION['name'] = $check; 
            $_COOKIE["name"] = $check; 
            
            $_SESSION['error'] = '';
            
            //вывод в формате json
            $response_array['status'] = 'successfully';
            echo json_encode($response_array);
        }
    }
}
else
    //Если это не ajax запрос
    exit;

// Проверка на заполненость полей
function Checking_Data_For_Errors($array_POST)
{
    //Создание массива, для добавление ошибок, если они есть
    $errors = [];
    foreach($array_POST as $key => $value)
    {
        //Проверка на заполненость полей и добавление ошибок в массив
        if(empty($value))
            $errors[] = "$key не заполнин; ";
    }
    return $errors;
}

//Проверка определенных значений 
function Fullness_Of_Fields($check)
{
    //если переменная определенная строка возвращаем false, в противном случае true
    if($check == 'login введен не правильно' or $check == 'password введен не правильно')
    {
        return false;
    }
    return true;
}

?>