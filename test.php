<!DOCTYPE html>
<html>
    <head>
        <title>Search solution</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    </head>
    <body>
        <div class="process-data">
            <?php
                if ($_POST) {
                    include "Functional.php";
                    include "values.php";
                    $obj1 = unserialize($obj1Saved);
                    //$obj1 = new Functional();
                    echo 'Исходная матрица: <br>';
                    $obj1->showMatrix($obj1->getSourceMatrix());
                    $arr = $obj1->getSourceMatrix();
                    echo '<br>';
                    $expression = htmlspecialchars($_POST['expression']);
                    $obj1->setExpression($expression);
                    echo 'Тупиковые тесты: ' . $obj1->getExpression() . '<br><br>';
                    $dParam = htmlspecialchars($_POST['dParam']);
                    $obj1->setDParam($dParam);
                    $maxObj = htmlspecialchars($_POST['maxObj']);
                    $obj1->setMaxObj($maxObj);

                    $obj1->createMatrixChoose($obj1->getExpression(), $dParam);
                    $matrixChoose = $obj1->getMatrixChoose();
                    echo 'Результаты голосования: <br>';
                    $obj1->showMatrix($matrixChoose);
                    echo '<br>';
                    $result = $obj1->findWinner($matrixChoose);
                    echo 'Результат: ' . $result;

                    echo '  <div class="findSolution">
                                <form name="" method="post" action="test.php">
                                    <p>Введите значение d: <input type="number" size="1" name="dParam"></p>
                                    <input type="hidden" size="1" value="' . $maxObj . '" name="maxObj">
                                    <input type="hidden" size="1" value="' . $expression . '" name="expression">
                                    <p><input type="submit" value="Отправить" /></p>
                                </form>
                            </div>';
                }
            ?>
        </div>
    </body>
</html>