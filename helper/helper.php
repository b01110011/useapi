<?php

/**
 * Выводит слово из массива с правильным окончанием при подсчёте (1 яблоко, 2 яблока, 5 яблок)
 * @param integer $num - кол-во чего либо
 * @param array $words - массив с тремя словами
 */
function wordend($num, array $words)
{
    return $words[(($num % 100 > 10 && $num % 100 < 15) || $num % 10 > 4 || $num % 10 == 0) ? 2 : ($num % 10 == 1 ? 0 : 1)];
}

/**
 * Форматирует телефонный номер.
 */
function phoneFormat($phone)
{
    if ($phone)
    {
        $result = preg_replace("/[^0-9]/", '', $phone);
        if ($result[0] == '7')
        {
            return substr_replace($result, '8', 0, 1);
        }
        else
        {
            return $result;
        }
    }
    else
    {
        return '';
    }
}