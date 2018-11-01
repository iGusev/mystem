<?php

namespace Mystem\Tests;

use Mystem\Article;
use Mystem\ArticleWord;
use Mystem\Grammeme;
use PHPUnit\Framework\TestCase;

class ArticleTest extends TestCase
{

    public function testLoad()
    {
        $article = new Article('На дворе смеркалось');
        $this->assertCount(3, $article->getWords());
    }

    /**
     * @expectedException \TypeError
     */
    public function testInvalidLoad2()
    {
        $article = new Article(null);
    }

    public function testGetArticle()
    {
        $article = new Article('Ёжик в тумане');
        $this->assertTrue(is_string($article->getArticle()));
    }

    public function testCheckBadWords()
    {
        $article = new Article('Не те бляди, что денег ради…');
        $this->assertNotEmpty($article->checkBadWords());
        $this->assertNotEmpty($article->checkBadWords(true));
    }

    public function testCheckNoBadWords()
    {
        $article = new Article('Однажды лебедь, рак и щука…');
        $this->assertEmpty($article->checkBadWords());
    }


    /**
     * @return array
     */
    public static function providerGetWordsWithGrammeme()
    {
        return [
            ['Сергея Петров', Grammeme::PART_S, ['сергей', 'петров']],
            ['Петр Иванович Петров', Grammeme::PART_S, ['петр', 'иванович', 'петров']],
            ['Петр Иванович Петров', Grammeme::OTHER_NAME, ['петр']],
            ['Петр Иванович Петров', Grammeme::OTHER_SURNAME, ['петров']],
            ['кококо', Grammeme::PART_S, []],
            ['женя', Grammeme::OTHER_NAME, ['женя']],
            ['альберт', Grammeme::OTHER_NAME, ['альберт']],
        ];
    }

    /**
     * @dataProvider providerGetWordsWithGrammeme
     *
     * @param string $text
     * @param string $grammeme
     * @param $expectedResult
     *
     * @throws \Exception
     */
    public function testGetWordsWithGrammeme(string $text, string $grammeme, $expectedResult)
    {
        $article = new Article($text);
        $words = $article->getWordsWithGrammeme($grammeme);

        array_map(function (ArticleWord $articleWord) {
            $this->assertInstanceOf(ArticleWord::class, $articleWord);
        }, $words);

        $this->assertEquals($expectedResult, array_map(function (ArticleWord $articleWord) {
            return $articleWord->normalized();
        }, $words));
    }
}