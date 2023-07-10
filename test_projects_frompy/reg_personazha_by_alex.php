<?php

// 1. Сугубо для наглядности принципа конвертации значений в булевы, поменял условие в циклах while
// при $reg === 0, while ($reg) возвращает false, while (!$reg) возвращает true
// в данном примере while ($reg < 1) выглядит лучше, чем мой while (!$reg)

// 2. Также интерполяция строк в последних версиях PHP не требуется использовать фигурные скобки
// достаточно "foo bar foo bar $baz foo bar"

// 3. В последних версиях не требуется в конце PHP скрипт ставить ? /, если дальше не следует HTML
echo "Регистрация персонажа\n";
$reg = 0;
while (!$reg) {
    $reg_gender = 0;
    while (!$reg_gender) {
        echo "Выберите пол персонажа:\n1 - мужской\n2 - женский\n";
        $gender = readline();
        if ($gender === "1") {
            $gender = "Мужской";
            $reg_gender++;
        } elseif ($gender === "2") {
            $gender = "Женский";
            $reg_gender++;
        } else {
            echo "Выберите из перечисленного - 1 или 2\n";
        }
        if ($reg_gender) {
            echo "ОК, ваш пол - $gender . \n";
            $reg_race = 0;
            while (!$reg_race) {
                echo "Выберите расу персонажа:\n1 - человек\n2 - эльф\n0 - вернуться на предыдущий этап\n";
                $race = readline();
                if ($race === "1") {
                    $race = "Человек";
                    $reg_race++;
                } elseif ($race === "2") {
                    $race = "Эльф";
                    $reg_race++;
                } elseif ($race === "0") {
                    $reg_gender = 0;
                    break;
                } else {
                    echo "Выберите из перечисленного - 1 или 2\n";
                }
                if ($reg_race) {
                    echo "ОК, ваш пол - $gender, ваша раса - $race.\n";
                    $reg_class = 0;
                    while (!$reg_class) {
                        echo "Выберите класс персонажа:\n1 - лучник\n2 - безработный\n0 - вернуться на предыдущий этап\nstart - вернуться в начало\n";
                        $class1 = readline();
                        if ($class1 === "1") {
                            $class1 = "Лучник";
                            $reg_class++;
                        } elseif ($class1 === "2") {
                            $class1 = "безработный";
                            $reg_class++;
                        } elseif ($class1 === "0") {
                            $reg_race = 0;
                            break;
                        } elseif ($class1 === "start") {
                            $reg_gender = 0;
                            break;
                        } else {
                            echo "Выберите из перечисленного - 1 или 2\n";
                        }
                    }
                }
            }
        }
    }
    echo "Вы зарегистрированы, ваш пол - $gender, ваша раса - $race, ваш класс - $class1\n";
    $reg++;
}

