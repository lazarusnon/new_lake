<?php
$servername = "127.0.0.1:3306";
$username = "root";
$password = "1234";
$dbname = "contacts";

$conn = mysqli_connect($servername, $username, $password, $dbname);



// Проверяет, отправлена ли форма
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Извлекаем данные формы
    $name = $_POST['contact_name'];
    $phone = $_POST['contact_number'];
    $message = $_POST['contact_message'];
    
    // Подготовливаем SQL-запрос для вставки данных
    $sql = "INSERT INTO contacts (name, phone, message) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    
    // Привязываем параметры и выполняем запрос
    mysqli_stmt_bind_param($stmt, "sss", $name, $phone, $message);
    
    // Выполнить инструкцию
    if (mysqli_stmt_execute($stmt)) {

        // папка с txt
        $folderPath = 'contactstxt/';
        
        // генерирует уникальное имя
        $filename = time() . '.txt';
        
        // записывает содержимое файла
        $fileContent = "Name: $name\nPhone: $phone\nMessage: $message";
        
        // путь к файлу
        $filePath = $folderPath . $filename;
        
        // создаст папку, если не найдет
        if (!is_dir($folderPath)) {
            mkdir($folderPath, 0777, true);
        }
        
        // Записывает данные в текстовый файл
        if (file_put_contents($filePath, $fileContent) !== false) {
            echo "Данные были отправлены и сохранены успешно!";
        } else {
            echo "Упс! Не удалось сохранить данные в виде текстового файла.";
        }
    } else {
        // Не удалось вставить данные
        echo "Упс! Что-то пошло не так.";
    }
}

// закрываем соединение с дб
mysqli_close($conn);
?>
