<!DOCTYPE html>
<html>
    <head>
        <title>Lab4_MSK</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    </head>
    <body>
        <div class="process-data">
            <?php
            if ($_POST) {

                /*$sourceMatrix = array(  array('_', 'X1', 'X2', 'X3', 'X4', 'X5', 'X6'),
                                        array('a1', 1, 0, 1, 0, 0, 1),
                                        array('a2', 0, 1, 1, 0, 1, 1),
                                        array('b1', 1, 1, 0, 1, 1, 0),
                                        array('b2', 1, 0, 0, 0, 1, 1),
                                        array('b3', 0, 0, 1, 1, 0, 0));*/
                include 'Functional.php';
                $obj1 = new Functional();
                $obj1->setMaxObj(htmlspecialchars($_POST['maxObj']));
                $maxObj = $obj1->getMaxObj();
                $obj1->createSourceMatrix();
                //$obj1->setSourceMatrix($sourceMatrix);
                $sourceMatrix = $obj1->getSourceMatrix();
                echo 'Исходная матрица: <br>';
                $obj1->showMatrix($sourceMatrix);
                echo '<br><br>';
                $obj1->createEPM($sourceMatrix, ($obj1->getSumAllObj() + 1), ($obj1->getCountX() + 1));
                $arrEpm = $obj1->getArrEpm();
                echo 'Матрица ЭПМ: <br>';
                $obj1->showMatrix($arrEpm);
                echo '<br><br>';
                $fail = $obj1->checkEPM($arrEpm);
                //$fail = FALSE;
                if (!$fail) {
                    $obj1->sortStrEpmAsc($arrEpm);
                    echo 'Отсортированная матрица ЭПМ: <br>';
                    $obj1->showMatrix($obj1->getArrEpm());
                    echo '<br><br>';
                    $obj1->deleteStrExtension($obj1->getArrEpm());
                    echo 'Сокращеная матрица ЭПМ: <br>';
                    $obj1->showMatrix($obj1->getArrEpm());
                    echo '<br><br>';
                    $expression = $obj1->createExpressionP($obj1->getArrEpm());
                    echo 'Выражение: ' . $expression . '<br>';
                    echo '<br>';

                    $obj1->createDeadlockTest($expression);
                    $expression = $obj1->getExpression();

                    echo "Тупиковые тесты: $expression<br><br>";

                    $obj1->createRate($expression);
                    $arrRate = $obj1->getArrRate();

                    echo 'Отношения: <br>';
                    foreach ($arrRate as $key => $value) {
                        echo $key . ' = ' . $value . '<br>';
                    }

                    $obj1JSON = serialize($obj1);
                    $data = '<?php $obj1Saved = ' . '\'' . $obj1JSON . '\';';

                    //echo '!!! ' . $data . ' !!!';

                    $fd = fopen("values.php", 'w') or die("Не удалось открыть файл");
                    fwrite($fd, $data);
                    fclose($fd);

                    echo '    <div class="findSolution">
                            <form name="" method="post" action="test.php">
                            <p>Введите значение d: <input type="number" size="1" name="dParam"></p>
                            <input type="hidden" size="1" value="' . $maxObj . '" name="maxObj">
                            <input type="hidden" size="1" value="' . $expression . '" name="expression">
                            <p><input type="submit" value="Отправить" /></p>
                            </form>
                        </div>';

                } else {
                    echo 'Данные некорректны';
                }
            } else {
                echo 'Значения для обработки не получены';
            }
            ?>
        </div>
    </body>
</html>