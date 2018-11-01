<?php

namespace Mystem;

/**
 * Class Grammeme
 * Lexical information constants
 */
class Grammeme
{
    // Части речи
    const PART_A = 'A'; // прилагательное
    const PART_ADV = 'ADV'; // наречие
    const PART_ADVPRO = 'ADVPRO'; // местоименное наречие
    const PART_ANUM = 'ANUM'; // числительное-прилагательное
    const PART_APRO = 'APRO'; // местоимение-прилагательное
    const PART_COM = 'COM'; // часть композита - сложного слова
    const PART_CONJ = 'CONJ'; // союз
    const PART_INTJ = 'INTJ'; // междометие
    const PART_NUM = 'NUM'; // числительное
    const PART_PART = 'PART'; // частица
    const PART_PR = 'PR'; // предлог
    const PART_S = 'S'; // существительное
    const PART_SPRO = 'SPRO'; // местоимение-существительное
    const PART_V = 'V'; // глагол

    // Время глаголов
    const PRESENT = 'наст'; // настоящее
    const FUTURE = 'непрош'; // непрошедшее
    const PAST = 'прош'; // прошедшее

    // Падеж
    const NOMINATIVE = 'им'; // именительный
    const GENITIVE = 'род'; // родительный
    const DATIVE = 'дат'; // дательный
    const ACCUSATIVE = 'вин'; // винительный
    const INSTRUMENTAL = 'твор'; //творительный
    const PREPOSITIONAL = 'пр'; // предложный
    const PARTITIVE = 'парт'; // партитив (второй родительный)
    const LOCATIVE = 'местн'; // местный (второй предложный)
    const VOCATIVE = 'зват'; // звательный

    // Число
    const SINGULAR = 'ед'; // единственное число
    const PLURAL = 'мн'; // множественное число

    // Репрезентация и наклонение глагола
    const VERBAL_ADV = 'деепр'; // деепричастие
    const INFINITIVE = 'инф'; // инфинитив
    const PARTICIPLE = 'прич'; // причастие
    const INDICATIVE = 'изъяв'; // изьявительное наклонение
    const IMPERATIVE = 'пов'; // повелительное наклонение

    // Форма прилагательных
    const SHORT = 'кр'; // краткая форма
    const FULL = 'полн'; // полная форма
    const POSSESIVE = 'притяж'; // притяжательные прилагательные

    // Степень сравнения
    const SUPERLATIVE = 'прев'; // превосходная
    const COMPARATIVE = 'срав'; // сравнительная

    // Лицо глагола @TODO
    const V_1 = '1-л'; // 1-е лицо
    const V_2 = '2-л'; // 2-е лицо
    const V_3 = '3-л'; // 3-е лицо

    // Род
    const FEMININE = 'жен'; // женский род
    const MASCULINE = 'муж'; // мужской род
    const NEUTER = 'сред'; // средний род

    // Вид (аспект) глагола
    const PERFECT = 'сов'; // совершенный
    const IMPERFECT = 'несов'; // несовершенный

    // Залог
    const ACTIVE = 'действ'; // действительный залог
    const PASSIVE = 'страд'; // страдательный залог

    // Одушевленность
    const ANIMATE = 'од'; // одушевленное
    const INANIMATE = 'неод'; // неодушевленное

    //Переходность
    const TRANSITIVE = 'пе'; // переходный глагол
    const INTRANSITIVE = 'нп'; // непереходный глагол

    //Прочие обозначения
    const OTHER_PARENTHESIS = 'вводн'; // вводное слово
    const OTHER_GEO = 'гео'; // географическое название
    const OTHER_WTF = 'затр'; // образование формы затруднено
    const OTHER_NAME = 'имя'; // имя собственное
    const OTHER_CORRUPT = 'искаж'; // искаженная форма
    const OTHER_MF = 'мж'; // общая форма мужского и женского рода
    const OTHER_VULGARISM = 'обсц'; // обсценная лексика
    const OTHER_PATRONYMIC = 'отч'; // отчество
    const OTHER_PREDICTIVE = 'прдк'; // предикатив
    const OTHER_COLLOQUIAL = 'разг'; // разговорная форма
    const OTHER_RARE = 'редк'; // редко встречающееся слово
    const OTHER_ABBREVIATION = 'сокр'; // сокращение
    const OTHER_OBSOLETE = 'устар'; // устаревшая форма
    const OTHER_SURNAME = 'фам'; // фамилия

    // Все граммемы
    const ALL = [
        self::PART_A,
        self::PART_ADV,
        self::PART_ADVPRO,
        self::PART_ANUM,
        self::PART_APRO,
        self::PART_COM,
        self::PART_CONJ,
        self::PART_INTJ,
        self::PART_NUM,
        self::PART_PART,
        self::PART_PR,
        self::PART_S,
        self::PART_SPRO,
        self::PART_V,
        self::PRESENT,
        self::FUTURE,
        self::PAST,
        self::NOMINATIVE,
        self::GENITIVE,
        self::DATIVE,
        self::ACCUSATIVE,
        self::INSTRUMENTAL,
        self::PREPOSITIONAL,
        self::PARTITIVE,
        self::LOCATIVE,
        self::VOCATIVE,
        self::SINGULAR,
        self::PLURAL,
        self::VERBAL_ADV,
        self::INFINITIVE,
        self::PARTICIPLE,
        self::INDICATIVE,
        self::IMPERATIVE,
        self::SHORT,
        self::FULL,
        self::POSSESIVE,
        self::SUPERLATIVE,
        self::COMPARATIVE,
        self::V_1,
        self::V_2,
        self::V_3,
        self::FEMININE,
        self::MASCULINE,
        self::NEUTER,
        self::PERFECT,
        self::IMPERFECT,
        self::ACTIVE,
        self::PASSIVE,
        self::ANIMATE,
        self::INANIMATE,
        self::TRANSITIVE,
        self::INTRANSITIVE,
        self::OTHER_PARENTHESIS,
        self::OTHER_GEO,
        self::OTHER_WTF,
        self::OTHER_NAME,
        self::OTHER_CORRUPT,
        self::OTHER_MF,
        self::OTHER_VULGARISM,
        self::OTHER_PATRONYMIC,
        self::OTHER_PREDICTIVE,
        self::OTHER_COLLOQUIAL,
        self::OTHER_RARE,
        self::OTHER_ABBREVIATION,
        self::OTHER_OBSOLETE,
        self::OTHER_SURNAME,
    ];
}
