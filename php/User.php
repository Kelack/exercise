<?php
class User {
    // Массив $_POST
    private $userValues; 
    private $arrayJson; 

    public function __construct($userValues,$arrayJson)
    {   
        //$_POST
        $this->userValues = $userValues;

        //Массив из $file_name
        $this->arrayJson = $arrayJson;
    }

    public function Checking_Fields_In_Function_Create()
    {
        foreach($this->userValues as $key => $value)
        {
            //проверка login на сопадение (проход по всему $arrayJson)
            if($key == 'login')
                foreach($this->arrayJson as $numberUser => $item)
                    foreach($item as $fieldNames => $fieldValue)
                        if($fieldNames=='login' and $fieldValue == $value)
                            return 'Такой login уже есть';

            //проверка email на сопадение (проход по всему $arrayJson)
            if($key == 'email')
                foreach($this->arrayJson as $numberUser => $item)
                    foreach($item as $fieldNames => $fieldValue)
                        if($fieldNames=='email' and $fieldValue == $value)
                            return 'Такой email уже есть';

            //сохранене 'password' для дальнейшей проверки
            if($key=='password')
                $password = $value;

            //проверка паролей на совпадение
            if($key=='confirm_password' and $value!==$password)
                return 'Пароли не совпадают';
        }

        return true;
    }

    public function Creating_An_Array()
    {
        foreach($this->userValues as $key => $value)
        {
            //чтобы не добавлять 'confirm_password' в массив
            if($key !== 'confirm_password')
                if($key !== 'password')
                    //добавление значений в массив
                    $userDataArray[count($this->arrayJson)+1][$key] = $value;
                else
                    //Добавление измененного пароля
                    $userDataArray[count($this->arrayJson)+1][$key] = "соль".$value;
        }
        return $userDataArray;
    }

    public function Convert_Array_And_Write_To_File($userDataArray)
    {
        //Добавление в старый массив, новый массив введенный пользователем
        $this->arrayJson += $userDataArray;   

        //Преобразование массива в json
        $jsonData = json_encode($this->arrayJson);

        //Добавление json в файл 'bd.json'
        file_put_contents('../json/bd.json',$jsonData);

        return $this->userValues['name'];
    }

    public function Checking_Fields_In_Function_Read()
    {
        $login = '';
        //Проходимся по arrayJson и ищим совпадение на 'login' введеный пользователем
        foreach($this->arrayJson as $numberUser => $item)
            foreach($item as $fieldNames => $fieldValue)
                if($fieldNames == 'login' and $this->userValues["login"] == $fieldValue)
                {
                    //Как только нашли такой же 'login', сохраняем id массива и останавливаем циклы
                    $login = $numberUser;
                    break 2;
                }
        
        //Если мы не нашли 'login', выводим сообщение
        if($login == '')
            return 'login введен не правильно';

        //Если 'login' был найден, ищем "password"
        if($this->arrayJson[$login]['password'] == ("соль".$this->userValues["password"]))
            return $this->arrayJson[$login]['name'];

        //Если мы не нашли 'password', выводим сообщение
        else
            return 'password введен не правильно'; 
    }

}
?>