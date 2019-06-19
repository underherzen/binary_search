Задание:
Написать функцию, реализующую бинарный поиск значения по ключу в текстовом файле.
Аргументы: имя файла, значение ключа
Результат: если найдено: значение, соответствующее ключу, если не найдено: undef
Исходные данные и требования к реализации:
1. Объем используемой памяти не должен зависеть от размера файла, только от максимального размера записи.
2. Формат файла: ключ1\tзначение1\x0Aключ2\tзначение2\x0A...ключN\tзначениеN\x0A Где: \x0A - разделитель записей (код ASCII: 0Ah) \t - разделитель ключа и значения (табуляция, код ASCII: 09h) Символы разделителей гарантированно не могут встречаться в ключах или значениях. Записи упорядочены по ключу в лексикографическом порядке с учетом регистра. Все ключи гарантированно уникальные.
3. Ограничений на длину ключа или значения нет.
Функция на файле размером 10Гб с записями длиной до 4000 байт должна отрабатывать любой запрос менее чем за 5 секунд.

