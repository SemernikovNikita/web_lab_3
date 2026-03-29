<?php
// index.php - страница с формой
session_start();

// Подключение к БД для получения списка языков
$host = 'localhost';
$dbname = 'your_login'; // замените на ваш логин
$user = 'your_login';   // замените на ваш логин
$pass = 'your_password'; // замените на ваш пароль

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения к БД: " . $e->getMessage());
}

// Получаем список языков для отображения в форме
$languages = [];
$stmt = $pdo->query("SELECT id, name FROM programming_language ORDER BY id");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $languages[] = $row;
}

// Получаем сохраненные данные из сессии (если были ошибки)
$form_data = $_SESSION['form_data'] ?? [];
$errors = $_SESSION['errors'] ?? [];
$success = $_SESSION['success'] ?? false;

// Очищаем сессию после чтения
unset($_SESSION['form_data']);
unset($_SESSION['errors']);
unset($_SESSION['success']);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Лабораторная работа 2</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <header>
        <img src="https://avatars.mds.yandex.net/i?id=a2c747a3ccfa4d287e074cb7c474dc24c97eb28f-17641277-images-thumbs&n=13" alt="" id="logo" width="100px">
        <nav>
            <a class="link" href="#center">Список ссылок</a>
            <a class="link" href="#tablic">Таблица</a>
            <a class="link" href="#forma">Форма</a>
        </nav>
        <h1 class="name">Лабораторная работа 2</h1>
    </header>

    <div id="center">
        <h2>Список гиперссылок</h2>
        <ul class="list">           
            <li><a class="nav-link" href="http://www.kubsu.ru" title="Официальный сайт КубГУ"> Ссылка на сайт КубГУ первая</a></li>
            <li><a class="nav-link" href="https://www.kubsu.ru" title="Официальный сайт КубГУ"> Ссылка на сайт КубГУ вторая</a></li>
            <li><a class="nav-link" href="https://www.kubsu.ru"><img src="https://avatars.mds.yandex.net/i?id=82b93ff4ec4fc1a0b1e3df7bfdd8a1293bd8eb98-16441608-images-thumbs&n=13" alt="цветочек" style="width: 200px;"></a></li>
            <li><a class="nav-link" href="inside/inside_first.html"> Сокращенная ссылка на внутреннюю страницу</a></li>
            <li><a class="nav-link" href="index.html"> Сокращенная ссылка на главную страницу</a></li>
            <li><a class="nav-link" href="#element"> Ссылка на фрагмент страницы</a></li>
            <li><a class="nav-link" href="index.html?user=123&profile=234&cat=0"> Ссылка с 3 параметрами в URL</a></li>
            <li><a class="nav-link" href="index.html?id=123"> Ссылка с параметром id в URL</a></li>
            <li><a class="nav-link" href="./incatalog.html"> Ссылка на страницу в текущем каталоге</a></li>
            <li><a class="nav-link" href="./about/about.html"> Ссылка на страницу в каталоге about</a></li>
            <li><a class="nav-link" href="../1step.html"> Ссылка на страницу в каталоге уровнем выше</a></li>
            <li><a class="nav-link" href="../../2step.html"> Ссылка на страницу в каталоге двумя уровнями выше</a></li>
            <li class="nav-link">
                 Невероятно осмысленный текст 
                <a class="nav-link" href="https://www.kubsu.ru">со ссылкой</a>
            </li>
            <li><a class="nav-link" href="https://www.kubsu.ru/lib/images/Group3288.png">Ссылка на фрагмент стороннего сайта</a></li>
                
                <img src="https://i.ytimg.com/vi/tqh_z6fugJs/hq720.jpg" width="500px" usemap="#cat">
                <map name="cat">
                    <area shape="rect" coords="0,0,251,274" href="http://www.kubsu.ru">
                    <area shape="circle" coords="400,64,64" href="http://www.kubsu.ru">
                </map>
                
            <li class="nav-link">Ссылки из прямоугольных и круглых областей картинки</li>
            <li><a class="nav-link" href=""> Ссылка с пустым href</a></li>
            <li><a class="nav-link" title="тут ничего нет"> Ссылка без href</a></li>
            <li><a class="nav-link" href="index.html" rel="nofollow"> Ссылка, по которой запрещен переход поисковикам</a></li>  
            <li><noindex><a class="nav-link" href="index.html"> Ссылка, запрещенная для индексации поисковиками</a></noindex></li>
            
            <li>
                <ol class="nav-link">
                    Нумерованный список ссылок
                    <li><a class="nav-link" href="https://www.kubsu.ru" title="Первая">ссылка</a></li>
                    <li><a class="nav-link" href="https://www.kubsu.ru" title="Вторая">ссылка</a></li>
                    <li><a class="nav-link" href="https://www.kubsu.ru" title="Третья">ссылка</a></li>
                </ol>
            </li>
        </ul>
    </div>
    
    <table id="tablic">
        <tr class="chet">
            <th>Первый</th>
            <th>Второй</th>
            <th>Третий</th>
            <th>Четвертый</th>
         </tr>
         <tr class="chet">
            <td colspan="2">ф</td>
            <td>ф</td>
            <td>ф</td>
          </tr>
        <tr class="chet">
            <td rowspan="2">ф</td>
            <td>ф</td>
            <td>ф</td>
            <td>ф</td>
         </tr>
        <tr class="chet">
            <td>ф</td>
            <td>ф</td>
            <td>ф</td>
         </tr>
        <tr class="chet">
            <td>ф</td>
            <td>ф</td>
            <td>ф</td>
            <td>ф</td>
         </tr>
        <tr class="chet">
            <td>ф</td>
            <td>ф</td>
            <td>ф</td>
            <td>ф</td>
         </tr>
        <tr class="chet">
            <td>ф</td>
            <td>ф</td>
            <td>ф</td>
            <td>ф</td>
         </tr>
    </table>
    
    <?php if ($success): ?>
        <div class="success-message">
            Данные успешно сохранены! Спасибо за заполнение анкеты.
        </div>
    <?php endif; ?>
    
    <?php if (!empty($errors) && !isset($errors['general'])): ?>
        <div class="error-summary">
            <strong>Пожалуйста, исправьте следующие ошибки:</strong>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($errors['general'])): ?>
        <div class="error-summary">
            <?= htmlspecialchars($errors['general']) ?>
        </div>
    <?php endif; ?>
    
    <form action="form.php" method="POST" id="forma">
        <h3>Форма</h3>
        
        <div class="form-group">
            <label>
                Ваше ФИО
                <br><input name="field-name" placeholder="Введите ФИО" 
                    value="<?= htmlspecialchars($form_data['full_name'] ?? '') ?>"
                    class="<?= isset($errors['field-name']) ? 'error-input' : '' ?>">
                </br>
                <?php if (isset($errors['field-name'])): ?>
                    <span class="error-message"><?= htmlspecialchars($errors['field-name']) ?></span>
                <?php endif; ?>
            </label>
        </div>
        
        <div class="form-group">
            <label>
                Телефон
                <br><input name="field-tel" placeholder="Введите номер телефона" type="tel" 
                    value="<?= htmlspecialchars($form_data['phone'] ?? '') ?>"
                    class="<?= isset($errors['field-tel']) ? 'error-input' : '' ?>">
                </br>
                <?php if (isset($errors['field-tel'])): ?>
                    <span class="error-message"><?= htmlspecialchars($errors['field-tel']) ?></span>
                <?php endif; ?>
            </label>
        </div>
        
        <div class="form-group">
            <label>
                E-mail
                <br><input name="field-email" placeholder="Введите email" type="email" 
                    value="<?= htmlspecialchars($form_data['email'] ?? '') ?>"
                    class="<?= isset($errors['field-email']) ? 'error-input' : '' ?>">
                </br>
                <?php if (isset($errors['field-email'])): ?>
                    <span class="error-message"><?= htmlspecialchars($errors['field-email']) ?></span>
                <?php endif; ?>
            </label>
        </div>
        
        <div class="form-group">
            <label>
                Дата рождения
                <br><input name="field-date" placeholder="Введите дату рождения" type="date" 
                    value="<?= htmlspecialchars($form_data['birth_date'] ?? '') ?>"
                    class="<?= isset($errors['field-date']) ? 'error-input' : '' ?>">
                </br>
                <?php if (isset($errors['field-date'])): ?>
                    <span class="error-message"><?= htmlspecialchars($errors['field-date']) ?></span>
                <?php endif; ?>
            </label>
        </div>

        <br />
        Пол
        <label><input type="radio" name="radio-group-1" value="Значение1" 
            <?= (($form_data['gender'] ?? '') == 'Значение1') ? 'checked' : '' ?>>Муж</label>
        <label><input type="radio" name="radio-group-1" value="Значение2" 
            <?= (($form_data['gender'] ?? '') == 'Значение2') ? 'checked' : '' ?>>Жен</label>
        <?php if (isset($errors['radio-group-1'])): ?>
            <br><span class="error-message"><?= htmlspecialchars($errors['radio-group-1']) ?></span>
        <?php endif; ?>
        <br/>

        <div class="form-group">
            <label>
                Любимый язык программирования:
                <br>
                <select name="listbox[]" multiple="multiple" 
                    class="<?= isset($errors['listbox']) ? 'error-input' : '' ?>">
                    <?php foreach ($languages as $lang): ?>
                        <?php 
                        $selected = (isset($form_data['languages']) && in_array($lang['name'], $form_data['languages'])) ? 'selected="selected"' : '';
                        ?>
                        <option value="<?= htmlspecialchars($lang['name']) ?>" <?= $selected ?>>
                            <?= htmlspecialchars($lang['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <br/>
                <?php if (isset($errors['listbox'])): ?>
                    <span class="error-message"><?= htmlspecialchars($errors['listbox']) ?></span>
                <?php endif; ?>
                <small>Удерживайте Ctrl (Cmd на Mac) для выбора нескольких языков</small>
            </label>
        </div>

        <div class="form-group">
            <label>
                Биография:
                <br>
                <textarea name="field-name-2" 
                    class="<?= isset($errors['field-name-2']) ? 'error-input' : '' ?>"><?= htmlspecialchars($form_data['biography'] ?? 'Расскажите вашу историю') ?></textarea>
                </br>
            </label>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="check-1" value="1" 
                    <?= (($form_data['agreement'] ?? 0) == 1) ? 'checked' : '' ?>>
                С контрактом ознакомлен (а)
                <?php if (isset($errors['check-1'])): ?>
                    <br><span class="error-message"><?= htmlspecialchars($errors['check-1']) ?></span>
                <?php endif; ?>
            </label>
        </div>
        
        <input type="submit" name="submit" value="Сохранить" />
    </form>

    <footer>
        <h2 class="name2">(c)Семерников Никита</h2>
    </footer>
</body>
</html>