<?php

session_start();

$host = 'localhost';
$dbname = 'your_login'; 
$user = 'your_login';   
$pass = 'your_password'; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $_SESSION['errors']['general'] = "Ошибка подключения к БД: " . $e->getMessage();
    header('Location: index.php');
    exit;
}

$languages = [];
$stmt = $pdo->query("SELECT id, name FROM programming_language ORDER BY id");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $languages[] = $row;
}

$errors = [];
$form_data = [];

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['submit'])) {
    header('Location: index.php');
    exit;
}

$form_data = [
    'full_name' => trim($_POST['field-name'] ?? ''),
    'phone' => trim($_POST['field-tel'] ?? ''),
    'email' => trim($_POST['field-email'] ?? ''),
    'birth_date' => $_POST['field-date'] ?? '',
    'gender' => $_POST['radio-group-1'] ?? '',
    'biography' => trim($_POST['field-name-2'] ?? ''),
    'agreement' => isset($_POST['check-1']) ? 1 : 0,
    'languages' => $_POST['listbox'] ?? []
];

if (empty($form_data['full_name'])) {
    $errors['field-name'] = 'ФИО обязательно для заполнения.';
} elseif (mb_strlen($form_data['full_name']) > 150) {
    $errors['field-name'] = 'ФИО не должно превышать 150 символов.';
} elseif (!preg_match('/^[a-zA-Zа-яА-ЯёЁ\s\-]+$/u', $form_data['full_name'])) {
    $errors['field-name'] = 'ФИО может содержать только буквы, пробелы и дефисы.';
}

if (empty($form_data['phone'])) {
    $errors['field-tel'] = 'Телефон обязателен для заполнения.';
} elseif (!preg_match('/^[\d\-\(\)\+]+$/', $form_data['phone'])) {
    $errors['field-tel'] = 'Введите корректный номер телефона.';
} elseif (strlen($form_data['phone']) > 20) {
    $errors['field-tel'] = 'Телефон не должен превышать 20 символов.';
}

if (empty($form_data['email'])) {
    $errors['field-email'] = 'Email обязателен.';
} elseif (!filter_var($form_data['email'], FILTER_VALIDATE_EMAIL)) {
    $errors['field-email'] = 'Введите корректный email адрес.';
} elseif (strlen($form_data['email']) > 255) {
    $errors['field-email'] = 'Email не должен превышать 255 символов.';
}

if (empty($form_data['birth_date'])) {
    $errors['field-date'] = 'Укажите дату рождения.';
} else {
    $date_obj = DateTime::createFromFormat('Y-m-d', $form_data['birth_date']);
    $min_date = new DateTime('1900-01-01');
    $max_date = new DateTime();
    
    if (!$date_obj || $date_obj->format('Y-m-d') !== $form_data['birth_date']) {
        $errors['field-date'] = 'Некорректный формат даты.';
    } elseif ($date_obj > $max_date) {
        $errors['field-date'] = 'Дата рождения не может быть в будущем.';
    } elseif ($date_obj < $min_date) {
        $errors['field-date'] = 'Укажите реальную дату рождения (после 1900 года).';
    }
}

$allowed_genders = ['Значение1', 'Значение2'];
if (!in_array($form_data['gender'], $allowed_genders)) {
    $errors['radio-group-1'] = 'Выберите пол.';
}

if ($form_data['agreement'] != 1) {
    $errors['check-1'] = 'Необходимо подтвердить согласие с контрактом.';
}

if (empty($form_data['languages'])) {
    $errors['listbox'] = 'Выберите хотя бы один язык программирования.';
} else {

    $allowed_lang_names = array_column($languages, 'name');
    $invalid_langs = array_diff($form_data['languages'], $allowed_lang_names);
    if (!empty($invalid_langs)) {
        $errors['listbox'] = 'Один или несколько выбранных языков недействительны.';
    }
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    $_SESSION['form_data'] = $form_data;
    header('Location: index.php');
    exit;
}

try {
    $pdo->beginTransaction();
    
    $gender_db = ($form_data['gender'] == 'Значение1') ? 'male' : 'female';
    
    $sql_app = "INSERT INTO application (full_name, phone, email, birth_date, gender, biography, agreement) 
                VALUES (:full_name, :phone, :email, :birth_date, :gender, :biography, :agreement)";
    $stmt_app = $pdo->prepare($sql_app);
    $stmt_app->execute([
        ':full_name' => $form_data['full_name'],
        ':phone' => $form_data['phone'],
        ':email' => $form_data['email'],
        ':birth_date' => $form_data['birth_date'],
        ':gender' => $gender_db,
        ':biography' => $form_data['biography'],
        ':agreement' => $form_data['agreement']
    ]);
    
    $application_id = $pdo->lastInsertId();
    
    $lang_map = [];
    foreach ($languages as $lang) {
        $lang_map[$lang['name']] = $lang['id'];
    }

    $sql_link = "INSERT INTO application_language (application_id, language_id) VALUES (?, ?)";
    $stmt_link = $pdo->prepare($sql_link);
    
    foreach ($form_data['languages'] as $lang_name) {
        if (isset($lang_map[$lang_name])) {
            $stmt_link->execute([$application_id, $lang_map[$lang_name]]);
        }
    }
    
    $pdo->commit();
    
    $_SESSION['success'] = true;
    unset($_SESSION['form_data']);
    unset($_SESSION['errors']);
    
    header('Location: index.php');
    exit;
    
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['errors']['general'] = 'Ошибка сохранения данных: ' . $e->getMessage();
    $_SESSION['form_data'] = $form_data;
    header('Location: index.php');
    exit;
}
?>