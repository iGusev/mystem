<?php

namespace Mystem;

/**
 * Class MystemArticle
 */
class Article
{
    /**
     * @var string
     */
    protected $article = '';

    /**
     * @var ArticleWord[]
     */
    protected $words = [];

    /**
     * Article constructor.
     * @param string $text
     *
     * @throws \Exception
     */
    public function __construct(string $text)
    {
        $offset = 0;
        $this->article = $text;

        $stemmed = Mystem::stemm($text);
        foreach ($stemmed as $part) {
            $word = ArticleWord::newFromLexicalString($part, 1, $this->article);

            $position = @mb_strpos($this->article, $word->getOriginal(), $offset);
            if ($position === false) { //Can't find original word
                $position = $offset + 1;
            }
            $word->position = $position;
            $offset = $word->position + mb_strlen($word->getOriginal());
            $this->words[] = $word;
        }
    }

    /**
     * @return string
     */
    public function getArticle(): string
    {
        return $this->article;
    }

    /**
     * @return ArticleWord[]
     */
    public function getWords(): array
    {
        return $this->words;
    }

    /**
     * @param string $grammeme
     * @param int|null $level
     *
     * @return ArticleWord[]
     */
    public function getWordsWithGrammeme(string $grammeme, int $level = null): array
    {
        $result = [];

        foreach ($this->getWords() as &$word) {
            if ($word->checkGrammeme($grammeme, $level)) {
                $result[] = $word;
            }
        }

        return $result;
    }

    /**
     * @param bool $stopOnFirst
     * @return array
     */
    public function checkBadWords(bool $stopOnFirst = false)
    {
        $result = [];
        foreach ($this->getWords() as &$word) {
            if ($word->isBadWord()) {
                $result[$word->getOriginal()] = $word->normalized();
                if ($stopOnFirst) {
                    break;
                }
            }
        }
        return $result;
    }
}
