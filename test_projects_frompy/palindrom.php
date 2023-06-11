<?php
echo "Проверим, является ли ваше пятиразрядное число палиндромом.\nВведите это число:\n";
$chislo = intval(readline());
$cifr1 = intval($chislo / 10000);
$cifr2 = intval(($chislo / 1000) % 10);
$cifr3 = intval(($chislo / 100) % 10);
$cifr4 = intval(($chislo / 10) % 10);
$cifr5 = intval($chislo % 10);
$rev_chislo = $cifr5 * 10000 + $cifr4 * 1000 + $cifr3 * 100 + $cifr2 * 10 + $cifr1;
if ($chislo >= 10000 && $chislo <= 99999) {
    if ($chislo == $rev_chislo) {
        echo "Введённое вами пятиразрядное число - палиндром.\n";
    } else {
        echo "Число не является палиндромом.\n";
    }
} else {
    echo "Введённое вами число - не пятиразрядное. Перезапустите программу и введите пятизначное число.\n";
}
?>
