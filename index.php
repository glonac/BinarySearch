<?php
//Тестовое задание для PHP программиста
//Написать функцию, реализующую бинарный поиск значения по
//ключу в текстовом файле.
//Аргументы: имя файла, значение ключа
//Результат: если найдено: значение, соответствующее ключу, если не найдено: undef
//Исходные данные и требования к реализации:
//1 Объем используемой памяти не должен зависеть от размера файла, только от максимального
//размера записи.
//2 Формат файла: ключ1\tзначение1\x0Aключ2\tзначение2\x0A...ключN\tзначениеN\x0A Где: \x0A -
//разделитель записей (код ASCII: 0Ah) \t - разделитель ключа и значения (табуляция, код ASCII:
//09h) Символы разделителей гарантированно не могут встречаться в ключах или значениях.
//Записи упорядочены по ключу в лексикографическом порядке с учетом регистра. Все ключи
//гарантированно уникальные.
//3 Ограничений на длину ключа или значения нет.
//Функция на файле размером 10Гб с записями длиной до 4000 байт должна отрабатывать любой
//запрос менее чем за 5 секунд.
function createFile($fileName, $count)//Создание файла
{
    $file = fopen($fileName, "w");
    for ($i = 0; $i < $count; $i++) {
        fwrite($file, "ключ" . $i . "\t" . "значение" . $i . "\x0A");
    }
}

function getTime($time = false)//Подсчет времени
{
    if (!$time)
    {
        return microtime(true);
    }else {
        return round(microtime(true) - $time , 5);
    }
}

function binarySearch($fileName, $desiredValue)//Функция бинарного поиска
{
    $file = new SplFileObject($fileName);
    $start = 0;
    $end = count(file($fileName)) - 1;

    while ($start <= $end) {
        $middle = floor(($start + $end) / 2);
        $file->seek($middle);
        $elem = explode("\t", $file->current());
        $offset = strnatcmp($elem[0], $desiredValue);
        if ($offset > 0) {
            $end = $middle - 1;
        } elseif ($offset < 0) {
            $start = $middle + 1;
        } else {
            return $elem[1];
        }
    }
    return 'undef';
}
$fileName = "Text.txt";
if (!file_exists("/$fileName")) {
    createFile($fileName, 200000);
}
if (isset($_POST['key'])) {
    $desiredValue = "ключ" . $_POST['key'];
} else {
    $desiredValue = "ключ49999";
}
if (isset($_POST['submit'], $_POST['key']) && !empty($_POST['key'])) {
    $time = getTime();
    $result = binarySearch($fileName, $desiredValue);
    $time = getTime($time);
    $view = "<li> Ответ - " . $result . "</li><li>" . "Время- " . $time . "секунд </li>";
} else {
    $view = " <label> Введите число в поле для ввода  </label>";
}
?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Бинарный поиск</title>
</head>
<body>
<div class="container">
    <div class="row justify-content-center" id="autouser" style="margin-top: 3rem">
            <div class="card">
                <div class="card-header">
                    <?= $view ?>
                </div>
                <div class="card-body">
                    <form action="" method="post" >
                        <input  type="text" class="form-control " type="text" name="key" autocomplete="off" >
                                <button type="submit" class="btn btn-dark" name="submit" style="margin-top: 1rem;">
                                    поиск
                                </button>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

