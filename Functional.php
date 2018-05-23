<?php
/**
 * Created by PhpStorm.
 * User: Xpeyshen65
 * Date: 03.12.2017
 * Time: 18:26
 */

class Functional
{
    private $arrCountObj = array();
    private $maxObj = 0;
    private $countX = 0;
    private $sumAllObj = 0;
    private $sourceMatrix = array ();
    private $arrEpm = array();
    private $arrRate = array();
    private $dParam = '';
    private $expression = '';
    private $matrixChoose = array();

    public function init() {
        $arrTmp = array();
        $maxObj = htmlspecialchars($_POST['$maxObj']);
        for ($i = 0; $i < $maxObj; $i++) {
            $arrTmp[] = htmlspecialchars($_POST['numObj_' . $i]);
        }
        $this->setMaxObj($maxObj);
        $this->setArrCountObj($arrTmp);
        $countX = htmlspecialchars($_POST['countX']);
        $this->setCountX($countX);
    }

    public function createSourceMatrix() {
        $countX = htmlspecialchars($_POST['countX']);
        $this->setCountX($countX);
        $sumAllObj = htmlspecialchars($_POST['sumAllObj']);
        $this->setSumAllObj($sumAllObj);
        $sourceMatrix = array ();


        for ($i = 0; $i < ($sumAllObj+1); $i++) {
            for ($j = 0; $j < ($countX+1); $j++) {
                if (($i != 0) && ($j == 0)) $sourceMatrix[$i][$j] = htmlspecialchars($_POST[$i-1]);
                elseif (($i == 0) && ($j == 0)) $sourceMatrix[$i][$j] = '_';
                elseif (($i == 0) && ($j != 0)) $sourceMatrix[$i][$j] = 'X' . $j;
                else {
                    $value = htmlspecialchars($_POST[($i-1) . '_' . ($j-1)]);
                    if ($value != 0) $sourceMatrix[$i][$j] = 1;
                    else $sourceMatrix[$i][$j] = 0;
                }
            }
        }
        $this->setSourceMatrix($sourceMatrix);
    }

    public function showMatrix($matrix) {
        foreach ($matrix as $i) {
            foreach ($i as $value) {
                echo $value . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            }
            echo '<br>';
        }
    }

    public function createEPM($matrix, $maxI, $maxJ) {
        $arrEpm = array(); $indexOneObj = 1; $indexTwoObj = 2;
        for ($i = 0; $i < $maxI*3; $i++) {  //ИСПРАВИТЬ КОСТЫЛЬ. ВМЕСТО (maxI*3) - сочетания
            if ($i != 0) {
                $indexTwoObj = $this->findNextObj($matrix, $indexOneObj, $indexTwoObj, $maxI);
                if (is_null($indexTwoObj)) {
                    $indexOneObj++;
                    $indexTwoObj = $indexOneObj;
                    $indexTwoObj = $this->findNextObj($matrix, $indexOneObj, $indexTwoObj, $maxI);
                }
                if (!$indexTwoObj) break 1;
            }
            for ($j = 0; $j < $maxJ; $j++) {
                if (($i == 0) && ($j == 0)) $arrEpm[$i][$j] = '_';
                elseif (($i == 0) && ($j != 0)) $arrEpm[$i][$j] = 'P' . $j;
                else {
                    if ($j == 0) $arrEpm[$i][$j] = $matrix[$indexOneObj][0] . '-' . $matrix[$indexTwoObj][0];
                    else {
                        $arrEpm[$i][$j] = ($matrix[$indexOneObj][$j] ^ $matrix[$indexTwoObj][$j]);
                    }
                }
            }
        }
        $this->setArrEpm($arrEpm);
    }

    public function checkEPM($matrixEPM) {
        $firstLine = TRUE;
        foreach ($matrixEPM as $i => $arr) {
            $sum = 0;
            if (!$firstLine) {
                $firstElem = TRUE;
                foreach ($arr as $j => $value) {
                    if (!$firstElem) $sum += $value;
                    $firstElem = FALSE;
                }
            } else $sum = -1;
            $firstLine = FALSE;
            if ($sum == 0) return TRUE;
        }
        return FALSE;
    }

    protected function findNextObj($matrix, $positionFirstObj, $currentPosition, $maxI) {
        $currentObj = $matrix[$currentPosition][0];

        if (($currentPosition+1) >= $maxI) return null;
        else {
            if ($matrix[$positionFirstObj][0][0] != $matrix[$currentPosition][0][0]) return ($currentPosition + 1);
            else {
                for ($i = $currentPosition; $i < $maxI; $i++) {
                    if ($matrix[$i][0][0] != $currentObj[0]) {
                        return $i;
                    }
                }
            }
        }
        return false;
    }

    public function sortStrEpmAsc($matrixEPM) {
        $count = 0; $sortedMatrixEPM = array();
        foreach ($matrixEPM as $i => $arr) {
            foreach ($arr as $j => $value) {
                if (($value == 1) && ($i != 0) && ($j != 0)) $count++;
            }
            if ($i == 0) $arrCountOne[$i] = -1;
            else $arrCountOne[$i] = $count;
            $count = 0;
        }
        asort($arrCountOne);

        $k = 0;
        foreach ($arrCountOne as $i => $val) {
            foreach ($matrixEPM[$i] as $value) {
                $sortedMatrixEPM[$k][] = $value;
            }
            $k++;
        }
        $this->setArrEpm($sortedMatrixEPM);
    }

    public function deleteStrExtension($matrixEPM) {
        $newMatrixEPM = array();
        $arrPositionOneFirstObj = array();
        $arrPositionOneSecondObj = array();
        $k = 1; $maxI = count($matrixEPM); $delStr = false;
        $countOneFirstObj = 0; $countOneSecondObj = 0;
        do {
            for($i = $k; $i < $maxI; $i++) {
                //echo "<br>i = $i; k = $k; first: $countOneFirstObj; second: $countOneSecondObj<br>";
                foreach ($matrixEPM[$i] as $j => $value) {
                    if ($value === 'del') break 1;
                    if (($value == 1) && ($i == $k)) {
                        $arrPositionOneFirstObj[] = $j;
                        $countOneFirstObj++;
                    }
                    elseif (($value == 1) && ($i != $k)) {
                        $arrPositionOneSecondObj[] = $j;
                        $countOneSecondObj++;
                    }
                    //echo $value . ' ';
                }
                //echo "first: $countOneFirstObj; second: $countOneSecondObj<br>";
                if ($countOneSecondObj >= $countOneFirstObj) {
                    foreach ($arrPositionOneFirstObj as $key => $val) {
                        $delStr = true;
                        if (!in_array($val, $arrPositionOneSecondObj)) {
                            $delStr = false;
                            break 1;
                        }
                    }
                    if ($delStr) $matrixEPM[$i][0] = 'del';
                }
                $arrPositionOneSecondObj = array();
                $countOneSecondObj = 0;
                //echo '<br>';
            }
            //echo '<br><br>';
            $arrPositionOneFirstObj = array();
            $countOneFirstObj = 0;
            $k++;
        } while ($k < ($maxI-1));

        $k = 0;
        foreach ($matrixEPM as $i => $arr) {
            foreach ($arr as $j => $value) {
               if ($value === 'del') {
                    $k--;
                    break 1;
                }
                else {
                    $newMatrixEPM[$k][$j] = $value;
                }
            }
            $k++;
        }
        $this->setArrEpm($newMatrixEPM);
    }

    public function createExpressionP($matrixEPM) {
        $expressionP = '('; $first = TRUE;
        foreach ($matrixEPM as $i => $arr) {
            if ($i == 0) continue;
            if ($i > 1) $expressionP .= '∧';
            foreach ($arr as $j => $value) {
                if ($j == 0) continue;
                if (($value == 1) && ($first)) {
                    $expressionP .= ('P' . $j);
                    $first = FALSE;
                }
                elseif (($value == 1) && (!$first)) $expressionP .= ('∨P' . $j);
            }
            $first = TRUE;
        }
        $expressionP = str_ireplace('∧', ')∧(', $expressionP);
        $expressionP .= ')';
        return $expressionP;
    }

    public function createDeadlockTest($expression) {
        $expression = str_replace('∨', '+or+', $expression);
        $expression = str_replace('∧', '+and+', $expression);

        $appid = "2RTWH9-H3A9KJ2RUX";
        //$input = "2plus5";
        $input = $expression;
        $url = "http://api.wolframalpha.com/v2/query?input=$input&appid=$appid";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        $output = curl_exec($ch);
        curl_close($ch);

        $output = strip_tags($output);

        $startPosition = strpos($output, 'DNF');
        $endPosition = strpos($output, 'CNF');
        $output = substr($output, $startPosition, ($endPosition-$startPosition-1));

        $sourceSymb = array('DNF | ', '&amp;&amp;', '||', 'P');
        $replaceSymb = array('', '∧', '∨', 'X');
        $output = str_ireplace($sourceSymb, $replaceSymb, $output);

        $this->setExpression($output);

    }

    public function createRate($expression) {
        $arrRate = array(); $fraction = 0;
        $countX = $this->getCountX();
        $denominator = substr_count($expression, '∨') + 1;
        for ($i = 1; $i <= $countX; $i++) {
            $numerator = substr_count($expression, ('X' . $i));
            $fraction = $this->reductionOfFraction($numerator . '/' . $denominator);
            $arrRate['R(X' . $i . ')'] = $fraction;
            $fraction = 0;
        }
        $this->setArrRate($arrRate);
        //return $arrRate;
    }
    
    public function createMatrixChoose($expression, $dParam) {
        $matrixChoose = array();
        $maxObj = $this->getMaxObj();
        $countVoices = 0;
        $countConjunct = substr_count($expression, '∨') + 1;
        for ($i = 0; $i < ($countConjunct+1); $i++) {
            for ($j = 0; $j < ($maxObj+1); $j++) {
                if (($i == 0) && ($j != 0)) $matrixChoose[$i][$j] = chr($j+96);
                elseif (($i == 0) && ($j == 0)) $matrixChoose[$i][$j] = '_';
                elseif ($j == 0) {
                    $matrixChoose[$i][$j] = $this->extractionConjunct($expression, $i); //Получение конъюнкта, нужного номера
                } else {
                    $countVoices = $this->searchCongruence($matrixChoose[$i][0], $dParam, $j); //Получение количества голосов для соответствующего класса
                    $matrixChoose[$i][$j] = $countVoices;
                    $countVoices = 0;
                }
            }
        }
        //Подсчет общего числа голосов каждой группы
        $sum = 0;
        for ($j = 0; $j < ($maxObj+1); $j++) {
            if ($j == 0) continue;
            for ($i = 0; $i < ($countConjunct+1); $i++) {
                if ($i == 0) continue;
                $sum += $matrixChoose[$i][$j];
            }
            $matrixChoose[$i][$j] = $sum;
            $sum = 0;
        }
        $this->setMatrixChoose($matrixChoose);
    }

    public function findWinner($matrixChoose) { //Поиск максимального значения среди общего числа голосов и возврат соответствующей группы
        $maxI = count($matrixChoose)-1; $max = -1;
        $indexJWinner = 0; $noSolution = TRUE;
        foreach ($matrixChoose as $key => $arr) {
            foreach ($arr as $j => $value) {
                if ($j == 0) continue;
                if ($matrixChoose[$maxI][$j] > $max) {
                    $max = $matrixChoose[$maxI][$j];
                    $indexJWinner = $j;
                    $noSolution = FALSE;
                } elseif ($matrixChoose[$maxI][$j] == $max) {
                    $noSolution = TRUE;
                }
            }
            break 1;
        }
        if (!$noSolution) return 'Объект принадлежит к группе ' . $matrixChoose[0][$indexJWinner];
        else return 'Отказ от принятия решения';
    }

    protected function extractionConjunct($expression, $numConjunct) {
        $conjunct = '';
        $arrConjunct = explode(' ∨ ', $expression);
        //echo "<br>$arrConjunct[0]<br>";
        /*$expression = str_ireplace(' ∨ ', '|', $expression);
        $lenExpression = strlen($expression);
        $currentConjuenct = 1;
        echo '<br>';
        for ($i = 0; $i < $lenExpression; $i++) {
            if ($numConjunct == 1) {
                echo $expression[$i] . ' ';
                if ($expression[$i] === '|') break 1;
                $conjunct .= $expression[$i];
            } elseif ($currentConjuenct == $numConjunct) {
                if ($expression[$i] === '|') break 1;
                $conjunct .= $expression[$i];
            }
            if ($expression[$i] == '∨') $currentConjuenct++;
        }*/
        $arrSourceSymb = array('(', ')');
        $arrReplaceSymb = array('', '');
        $arrConjunct[$numConjunct-1] = str_ireplace($arrSourceSymb, $arrReplaceSymb, $arrConjunct[$numConjunct-1]);
        return $arrConjunct[$numConjunct-1];
    }

    protected function searchCongruence($conjunct, $dParam, $numAttribute) {
        $sourceMatrix = $this->getSourceMatrix();
        $conjunct = str_ireplace(' ∧ ', '', $conjunct);
        $arrIndexCheckValue = explode('X', $conjunct);
        $countVoices = 0; $congruence = TRUE;
        foreach ($sourceMatrix as $i => $arr) {
            if ($i == 0) continue;
            $congruence = TRUE; $exit = FALSE;
            foreach ($arrIndexCheckValue as $key => $value) {
                if ($key == 0) continue;
                if (($sourceMatrix[$i][0][0] !== chr($numAttribute+96)) ||
                    (($sourceMatrix[$i][$value] != $dParam[$value-1]))) $congruence = FALSE;

                if ($sourceMatrix[$i][0][0] === chr($numAttribute+96)) $exit = TRUE;
                elseif (($sourceMatrix[$i][0][0] !== chr($numAttribute+96)) && ($exit)) break 2;
            }
            if ($congruence) $countVoices++;
        }
        return $countVoices;
    }

    protected function reductionOfFraction($fraction) {
        $arr = explode('/', $fraction);
        $numerator = (int)$arr[0];
        $denominator = (int)$arr[1];
        $nod = gmp_gcd($numerator, $denominator);
        $numerator = $numerator / $nod;
        $denominator = $denominator / $nod;
        return ($numerator . '/' . $denominator);
    }

    //<getters|setters>
    public function getMatrixChoose() {
        return $this->matrixChoose;
    }

    protected function setMatrixChoose($value) {
        $this->matrixChoose = $value;
    }

    public function getDParam() {
        return $this->dParam;
    }

    public function setDParam($value) {
        $this->dParam = $value;
    }
    
    public function getExpression() {
        return $this->expression;
    }
    
    public function setExpression($value) {
        $this->expression = $value;
    }

    public function getArrRate() {
        return $this->arrRate;
    }

    protected function setArrRate($value) {
        $this->arrRate = $value;
    }

    public function getArrEpm() {
        return $this->arrEpm;
    }

    protected function setArrEpm($value) {
        $this->arrEpm = $value;
    }

    public function getSumAllObj() {
        return $this->sumAllObj;
    }

    protected function setSumAllObj($value) {
        $this->sumAllObj = $value;
    }

    public function getSourceMatrix() {
        return $this->sourceMatrix;
    }

    public function setSourceMatrix($value) {
        $this->sourceMatrix = $value;
    }

    public function getArrCountObj() {
        return $this->arrCountObj;
    }

    protected function setArrCountObj($value) {
        $this->arrCountObj = $value;
    }

    public function getMaxObj() {
        return $this->maxObj;
    }

    public function setMaxObj($value) {
        $this->maxObj = $value;
    }

    public function getCountX() {
        return $this->countX;
    }

    protected function setCountX($value) {
        $this->countX = $value;
    }
    //</getters|setters>
}