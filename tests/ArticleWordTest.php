<?php

namespace Mystem\Tests;

use \Mystem\ArticleWord;

class ArticleWordTest extends \PHPUnit_Framework_TestCase {

    public function testLoad()
    {
        $article = 'Мы летели самолетами';
        $word = ArticleWord::stemm('самолетами', null, $article);
        $this->assertInstanceOf(ArticleWord::class, $word);
    }

}