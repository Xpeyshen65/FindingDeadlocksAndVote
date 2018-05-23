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
                include 'Functional.php';
                $obj1 = new Functional();
                $obj1->init();
                $maxObj = $obj1->getMaxObj();
                $countX = $obj1->getCountX();
                $arrCountObj = $obj1->getArrCountObj();
                $sumAllObj = array_sum($arrCountObj);
                $numObj = 0; $currentObj = 0; $arrFirstColumn = array ();
                echo '<form name="" method="post" action="lab4_MSK.php">';
                echo '<p>';
                for ($j = 0; $j < $countX; $j++) {
                    echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X' . ($j+1);
                }
                echo '</p>';
                for ($i = 0; $i < $sumAllObj; $i++) {
                    echo '<p>';
                    if ($numObj >= $arrCountObj[$currentObj]) {
                        $currentObj++;
                        $numObj = 0;
                    }
                    $symbNum = chr($currentObj + 97) . ($numObj + 1);
                    echo '<input type="hidden" value="' . $symbNum . '" name="' . $i . '">';
                    //$arrFirstColumn[$i] = chr($currentObj + 97) . ($numObj + 1);
                    echo $symbNum . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                    $numObj++;
                    for ($j = 0; $j < $countX; $j++) {
                        echo '<input type="number" size="1" name="' . $i . '_' . $j . '">    ';
                    }
                }
                    echo '</p>';

                    echo '<input type="hidden" size="1" value="' . $maxObj . '" name="maxObj">';
                    echo '<input type="hidden" size="1" value="' . $sumAllObj . '" name="sumAllObj">';
                    echo '<input type="hidden" size="1" value="' . $countX . '" name="countX">';
                    echo '<p><input type="submit" value="Найти решение" /></p>
                          </form>';
            }
            ?>
        </div>
    </body>
</html>