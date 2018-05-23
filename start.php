<!DOCTYPE html>
<html>
    <head>
        <title>Lab4_MSK</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    </head>
    <body>
        <form name="" method="post" action="start.php">
            <p>Введите количество объектов(не более 25): <input type="number" size="1" name="$maxObj"></p>
            <p><input type="submit" value="Отправить" /></p>
        </form>
        <?php
            if ($_POST) {
                include 'Functional.php';
                $obj1 = new Functional();
                $maxObj = htmlspecialchars($_POST['$maxObj']);
                if ($maxObj <= 25) {
                    echo '<form name="" method="post" action="start2.php">';
                    $codeSymb = 97;
                    echo '<br>Введите количество объектов каждой группы<br>';
                    for ($i = 0; $i < $maxObj; $i++) {
                        echo '<p>' . chr($codeSymb) . '&nbsp;&nbsp;&nbsp;<input type="number" size="1" name="numObj_' . $i . '"></p>';
                        $codeSymb++;
                    }
                    echo '<input type="hidden" size="1" value="' . $maxObj . '" name="$maxObj">';
                    echo '<p>Введите количество признаков:&nbsp;&nbsp;&nbsp;<input type="number" size="1" name="countX"></p>';
                    echo '<p><input type="submit" value="Построить матрицу" /></p>
                          </form>';
                } else {
                    echo '<br>Количество объектов не должно превышать 25. Повторите ввод.<br>';
                }
            }
        ?>
    </body>
</html>