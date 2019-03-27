<?php

namespace Mystem;

/**
 * Class ArticleWord
 *
 * Initialized from Article class, has link to original article and $position, filled with in Article position
 * @package Mystem
 */
class ArticleWord extends Word
{
    /**
     * @var string $article link to original article string
     */
    protected $article;

    public $position;

    /**
     * @param array|string $lexicalString
     * @param int $maxVariants
     * @param string $article
     *
     * @return self
     */
    public static function newFromLexicalString($lexicalString, $maxVariants = null, &$article = null): self
    {
        /* @var ArticleWord $word */
        $word = parent::newFromLexicalString($lexicalString, $maxVariants);

        if ($article !== null) {
            $word->article = &$article;
        }

        return $word;
    }

    /**
     * @param $word
     * @param null $maxVariants
     * @param bool $solveSyntacticDisambiguation
     * @param null $article
     * @return self
     *
     * @throws \Exception
     */
    public static function stemm($word, $maxVariants = null, $solveSyntacticDisambiguation = false, &$article = null): self
    {
        /* @var ArticleWord $word */
        $word = parent::stemm($word, $maxVariants);

        if ($article !== null) {
            $word->article = &$article;
        }

        return $word;
    }
}
