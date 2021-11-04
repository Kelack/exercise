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
        $response_array['status'] = 'not successfully fields filled';
        echo json_encode($response_array);
        exit;
        
    }
    //Если все поля заполнены, происходит дополнительная проверка, после добавление данных пользователя в bd.json
    else
    {
        //Проверка валидации
        if(!Field_Validation($_POST))
        {
            $response_array['status'] = 'not successfully validation';
            echo json_encode($response_array);
            exit;
        }
        else
        {
            require 'DataBase.php';

            $DataBase = new DataBase($_POST,'bd.json');
            
            //Добавляем данные с функции в переменную, для дальнейшей проверки
            $check = $DataBase->Create();
            
            //Если переменная определенная строка, добавляем ее в 'error', в противном случае добавляем ее в 'name'
            if(!Fullness_Of_Fields($check))
            {
                //Добавление определенной ошибки в $_SESSION['error']
                $_SESSION['error'] = $check;

                //вывод в формате json
                $response_array['status'] = 'not successfully certain fields';
                echo json_encode($response_array);
                exit;
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
                exit;
            }
        }
    }
}
else
{
    //Если это не ajax запрос
    exit;
}



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
function Field_Validation($array_POST)
{
    $examination = true;

    //Проверка login
    if(iconv_strlen($array_POST['login']) < 6)
    {
        $_SESSION['error'] .= 'Минимум 6 символов';
        $examination = false;
    }

    //Проверка password
    $containsLetter  = preg_match('/[a-zA-Z]/', $array_POST['name']);
    if(!($containsLetter and iconv_strlen($array_POST['name']) == 2))
    {
        $_SESSION['error'] .= 'Имя пользователя должно быть из 2 букв';
        $examination = false;
    }

    return $examination;

    //Проверка email
    if(!(filter_var($array_POST['email'], FILTER_VALIDATE_EMAIL)))
    {
        $_SESSION['error'] .= 'Неправильно введен email';
        $examination = false;
        
    }

    //Проверка password
    $containsLetter  = preg_match('/[a-zA-Z]/', $array_POST['password']);
    $containsDigit   = preg_match('/\d/', $array_POST['password']);
    if(!($containsLetter and $containsDigit) and iconv_strlen($array_POST['password']) < 6)
    {
        $_SESSION['error'] .= 'Пароль должен содержать хотя бы одну букву и цифру';
        $examination = false;
    }

    return $examination;
}

//Проверка определенных значений 
function Fullness_Of_Fields($check)
{
    //если переменная определенная строка возвращаем false, в противном случае true
    if($check == 'Такой login уже есть' or $check == 'Такой email уже есть'  or $check == 'Пароли не совпадают')
    {
        return false;
    }
    return true;
}

?>