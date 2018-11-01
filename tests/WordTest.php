<?php

namespace Mystem\Tests;

use Mystem\Word;
use Mystem\Grammeme;

class WordTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public static function providerStemm()
    {
        return [
            ['самолетами', 'самолет'],
            ['пельменьки', 'пельменька'],
            ['кораблелавирователем', 'кораблелавирователь'],
        ];
    }

    /**
     * @dataProvider providerStemm
     *
     * @param string $data
     * @param string $expectedResult
     */
    public function testStemm(string $data, string $expectedResult)
    {
        $this->assertEquals($expectedResult, Word::stemm($data)->normalized());
    }

    public function testFromLexicalString()
    {
        $lex = 'самолетами{самолет=S,муж,неод=твор,мн}';
        $word = Word::stemm($lex);
        $this->assertEquals('самолет', $word->normalized());
    }

    /**
     * @return array
     */
    public static function providerCantNormalize()
    {
        return [
            ['fadsfads'],
            ['rd/ 23, dsa']
        ];
    }

    /**
     * @dataProvider providerCantNormalize
     *
     * @param string $data
     */
    public function testCantNormalize(string $data)
    {
        $this->assertEmpty(Word::stemm($data)->normalized());
    }

    /**
     * @dataProvider providerStemm
     *
     * @param string $data
     * @param string $expectedResult
     */
    public function testToString(string $data, string $expectedResult)
    {
        $this->assertEquals($expectedResult, (string)Word::stemm($data));
    }

    /**
     * @return array
     */
    public static function providerPredict()
    {
        return [
            ['варкалось', 'варкаться'],
            ['пельменевылавливалось', 'пельменевылавливаться'],
            ['кораблелавировалось', 'кораблелавироваться']
        ];
    }

    /**
     * @dataProvider providerPredict
     *
     * @param string $data
     * @param string $expectedResult
     */
    public function testPredict(string $data, string $expectedResult)
    {
        $this->assertEquals($expectedResult, Word::stemm($data, 1));
    }

    public static function providerVerbTime()
    {
        return [
            ['летел', Grammeme::PAST],
            ['полетит', Grammeme::FUTURE],
        ];
    }

    /**
     * @dataProvider providerVerbTime
     * @param string $verb
     * @param string $time
     */
    public function testVerbTime($verb, $time)
    {
        $this->assertEquals($time, Word::stemm($verb)->getVerbTime());
    }

    public static function providerVerbPerson()
    {
        return [
            ['летит', Grammeme::V_3],
            ['лечу', Grammeme::V_1],
            ['летишь', Grammeme::V_2],
            ['летаешь', Grammeme::V_2],
            ['бежит', Grammeme::V_3],
            ['бегут', Grammeme::V_3],
            ['кококо', null],
        ];
    }

    /**
     * @dataProvider providerVerbPerson
     * @param string $data
     * @param string|null $expectedResult
     */
    public function testVerbPerson(string $data, $expectedResult)
    {
        $this->assertEquals($expectedResult, Word::stemm($data)->getVerbPerson());
    }


    public static function providerCount()
    {
        return [
            ['ёжик', Grammeme::SINGULAR],
            ['ёжики', Grammeme::PLURAL],
            ['бегал', Grammeme::SINGULAR],
            ['бежали', Grammeme::PLURAL],
            ['синий', Grammeme::SINGULAR],
            ['синие', Grammeme::PLURAL],
            ['один', Grammeme::SINGULAR],
            ['одни', Grammeme::PLURAL],
            ['зверье', Grammeme::SINGULAR],
            ['студенчество', Grammeme::SINGULAR],
            ['мошкара', Grammeme::SINGULAR],
            ['профессура', Grammeme::SINGULAR],
            ['детвора', Grammeme::SINGULAR],
            ['родня', Grammeme::SINGULAR],
            ['юношество', Grammeme::SINGULAR],
            ['пролетариат', Grammeme::SINGULAR],
            ['помои', Grammeme::PLURAL],
            ['консервы', Grammeme::PLURAL],
            ['сливки', Grammeme::PLURAL],
            ['щи', Grammeme::PLURAL],
            ['очистки', Grammeme::PLURAL],
            ['обои', Grammeme::PLURAL],
            ['опилки', Grammeme::PLURAL],
            ['опилок', Grammeme::PLURAL],
            ['кококо', null],
        ];
    }

    /**
     * @dataProvider providerCount
     * @param string $noun
     * @param string|null $count
     */
    public function testCount(string $noun, $count)
    {
        $this->assertEquals($count, Word::stemm($noun)->getCount());
    }

    public static function providerGender()
    {
        return [
            ['котейка', Grammeme::FEMININE],
            ['каравай', Grammeme::MASCULINE],
            ['ведро', Grammeme::NEUTER],
            ['Женя', null],
            ['Петя', Grammeme::MASCULINE]
        ];
    }

    /**
     * @dataProvider providerGender
     * @param string $noun
     * @param string|null $gender
     */
    public function testGender(string $noun, $gender)
    {
        $this->assertEquals($gender, Word::stemm($noun)->getGender());
    }

    public static function providerAnimate()
    {
        return [
            ['поросенок', Grammeme::ANIMATE],
            ['стул', Grammeme::INANIMATE],
            ['Василий', Grammeme::ANIMATE],
            ['кококо', null],
        ];
    }

    /**
     * @dataProvider providerAnimate
     * @param string $noun
     * @param string||null $animate
     */
    public function testAnimate(string $noun, $animate)
    {
        $this->assertEquals($animate, Word::stemm($noun)->getAnimate());
    }

    /**
     * @return array
     */
    public static function providerNounCase()
    {
        return [
            ['кот', Grammeme::NOMINATIVE],
            ['коту', Grammeme::DATIVE],
            ['кота', Grammeme::ACCUSATIVE],
            ['котом', Grammeme::INSTRUMENTAL],
            ['коте', Grammeme::PREPOSITIONAL],
            ['кококо', null]
        ];
    }

    /**
     * @dataProvider providerNounCase
     * @param string $noun
     * @param string $case
     */
    public function testNounCase($noun, $case)
    {
        $this->assertEquals($case, Word::stemm($noun)->getNounCase());
    }

    public function testUndefinedGrammeme()
    {
        $this->assertNull(Word::stemm('летел')->getNounCase());
    }

    public function testCheckGrammeme()
    {
        $word = Word::stemm('банка');
        $this->assertTrue($word->checkGrammeme(Grammeme::FEMININE));
        $this->assertTrue($word->checkGrammeme(Grammeme::MASCULINE));
        $this->assertFalse($word->checkGrammeme(Grammeme::FEMININE, 1));
    }

    public function testNoVariantsWord()
    {
        $word = Word::stemm('kokoko');
        $this->assertFalse($word->checkGrammeme(Grammeme::DATIVE));
        $this->assertNull($word->getNounCase(1));
    }

    public function testAddGrammeme()
    {
        $word = Word::stemm('банка');

        $this->assertArrayNotHasKey(Grammeme::OTHER_VULGARISM, array_flip($word->getVariants()[0]['grammemes']));
        $this->assertArrayNotHasKey(Grammeme::OTHER_VULGARISM, array_flip($word->getVariants()[1]['grammemes']));

        $word->addGrammeme(Grammeme::OTHER_VULGARISM);

        $this->assertArrayHasKey(Grammeme::OTHER_VULGARISM, array_flip($word->getVariants()[0]['grammemes']));
        $this->assertArrayHasKey(Grammeme::OTHER_VULGARISM, array_flip($word->getVariants()[1]['grammemes']));

        $countValues_1 = array_count_values($word->getVariants()[0]['grammemes']);
        $countValues_1a = array_count_values($word->getVariants()[1]['grammemes']);

        $this->assertEquals(1, $countValues_1[Grammeme::OTHER_VULGARISM]);
        $this->assertEquals(1, $countValues_1a[Grammeme::OTHER_VULGARISM]);

        $word->addGrammeme(Grammeme::OTHER_VULGARISM);

        $countValues_2 = array_count_values($word->getVariants()[0]['grammemes']);
        $countValues_2a = array_count_values($word->getVariants()[0]['grammemes']);

        $this->assertEquals(1, $countValues_2[Grammeme::OTHER_VULGARISM]);
        $this->assertEquals(1, $countValues_2a[Grammeme::OTHER_VULGARISM]);
    }
}
