<?php
class DataBase {
    private $user; 
    public function __construct($userValues,$file_name)
    {   
        require 'User.php';

        // //Создание из файла $file_name массивa
        $file_name = '../json/'.$file_name;
        $arrayJson = json_decode(file_get_contents($file_name),true);

        $this->user = new User($userValues,$arrayJson);
    }

    public function Create()
    {
        //Вызываем метод, для проверки полей и записываем в $examination
        $examination = $this->user->Checking_Fields_In_Function_Create();

        //если в $examination есть строка с ошибкой, возвращаем ошибку
        if(is_string($examination))
            return $examination;
        else
        //если в $examination нет ошибки, создаем массив из значений введенных пользователем
        //добавляем массив в базу данных и возвращаем имя пользователя
            return $this->user->Convert_Array_And_Write_To_File($this->user->Creating_An_Array());

    }

    public function Read()
    {
        return $this->user->Checking_Fields_In_Function_Read();
    }
}
?>