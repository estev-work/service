<?php

/**
 * Глобальная функция для получения экземпляра класса через контейнер
 *
 * @param string $class Класс или интерфейс, для которого нужно получить экземпляр
 *
 * @return object
 * @throws Exception
 */
function resolve(string $class): object
{
    if (!isset($GLOBALS['container'])) {
        throw new RuntimeException("Контейнер не найден.");
    }
    return $GLOBALS['container']->get($class);
}