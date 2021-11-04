<?php
session_start();

if(!empty($_COOKIE["name"]))
    $_SESSION['name'] = $_COOKIE["name"];

echo ((strpos($_SESSION['error'], 'заполнин' ) !== false) ? $_SESSION['error'] : "");
?>
<!-- комментарий Ctrl + / -->
<!-- сдвинуть текст Tab или Shift + Tab -->
<!-- найти нужную строку Ctrl + G -->
<!-- Поместить курсор в конец каждой выделенной строки = Ctrl + G -->
<!-- Форматирование = Shift + Alt + F -->
<!-- выделить текущую строку = Ctrl + L -->
<!-- Быстрое перемещение вниз и вверх = PG -->
<!-- перейти в конец строки = end; в начало = hm -->
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title><?= "Новая страница";?></title>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/form.js"></script>
    
</head>

<body>

<?php
if($_SESSION['name'] == '')
{
?>
<!-- form_registration -->

    <div class="form">
        <form method = "POST" action="php/registration.php">
            <?echo ((strpos($_SESSION['error'], 'Такой login уже есть' ) !== false) ? "Такой login уже есть<br>" : "")?>
            <?echo ((strpos($_SESSION['error'], 'Минимум 6 символов' ) !== false) ? "Минимум 6 символов<br>" : "")?>
            <input type="text" name="login" pattern="[A-Za-zА-Яа-яЁё0-9]{6,}" title="Минимум 6 символов" value="<?htmlspecialchars($_POST['login'], ENT_QUOTES);?>" placeholder="Логин"><br>
            <?echo ((strpos($_SESSION['error'], 'Имя пользователя должно быть из 2 букв' ) !== false) ? "Имя пользователя должно быть из 2 букв<br>" : "")?>
            <input type="text" name="name" pattern="[A-Za-zА-Яа-яЁё]{2}" title="2 буквы" value="<?htmlspecialchars($_POST['name'], ENT_QUOTES)?>" placeholder="Имя"><br>
            <?echo (($_SESSION['error']=='Такой email уже есть') ? "Такой email уже есть<br>" : "")?>
            <?echo ((strpos($_SESSION['error'], 'Неправильно введен email' ) !== false) ? "Неправильно введен email<br>" : "")?>
            <input type="email" name="email" value="<?htmlspecialchars($_POST['email'], ENT_QUOTES)?>" placeholder="Почта"><br>
            <?echo ((strpos($_SESSION['error'], 'Пароль должен содержать хотя бы одну букву и цифру' ) !== false) ? "Пароль должен содержать хотя бы одну букву и цифру<br>" : "")?>
            <input type="text" name="password" pattern="(?=^.{6,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[a-z]).*" title="Минимум 6 символов, должна быть одна цифра и одна латинская буква" value="<?htmlspecialchars($_POST['password'], ENT_QUOTES)?>" placeholder="Пароль"><br>
            <input type="text" name="confirm_password" pattern="(?=^.{6,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[a-z]).*" title="Минимум 6 символов, должна быть одна цифра и одна латинская буква" value="<?htmlspecialchars($_POST['confirm_password'], ENT_QUOTES)?>" placeholder="Повторить пароль"><br>
            <?echo (($_SESSION['error']=='Пароли не совпадают') ? "Пароли не совпадают<br>" : "")?>
            <input type="submit" value="Регистрация">
        </form>
    </div>

    <div class='form'>
        <form method = "POST" action="php/entrance.php">
            <?echo (($_SESSION['error']=='login введен не правильно') ? 'login введен не правильно<br>' : "")?>
            <input type="text" name="login" value="<?htmlspecialchars($_POST['login'], ENT_QUOTES)?>" placeholder="Логин"><br>
            <?echo (($_SESSION['error']=='password введен не правильно') ? 'password введен не правильно<br>' : "")?>
            <input type="text" name="password" value="<?htmlspecialchars($_POST['password'], ENT_QUOTES)?>" placeholder="Пароль"><br>
            <input type="submit" value="вход">
        </form>
    </div>

<?php
}
else
{
?>    

<div class="size">
    <h1>HELLO <?echo $_SESSION['name']?></h1>
    <form method = "post" action="php/out.php">
        <input type="submit" value="Выйти">
    </form>
</div>

<?php
}
?> 
</body>
</html>