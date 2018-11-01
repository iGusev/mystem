<?php

namespace Mystem;

/**
 * Class Word
 * @property array[] $variants lexical interpretation variants:
 *  - string $normalized - normalized word representation
 *  - boolean $strict - dictionary or predictable normalized representation
 *  - array $grammemes - lexical information (constants from Grammeme)
 */
class Word
{
    /**
     * @var string original string
     */
    protected $original;

    /**
     * @var array
     */
    public $variants = [];

    /* @var string[] $falsePositiveList */
    public static $falsePositiveList = [];

    /* @var string[] $falsePositiveList */
    public static $falsePositiveNormalizedList = [];

    /* @var string[] $falseNegativeList */
    public static $falseNegativeList = [];

    /* @var string[] $falseNegativeList */
    public static $falseNegativeNormalizedList = [];

    /**
     * @param array|string $lexicalString - prepared structure from Mystem
     * @param int|null $maxVariants
     *
     * @return Word
     */
    public static function newFromLexicalString($lexicalString, $maxVariants = null)
    {
        $word = new static();
        if (is_array($lexicalString)) {
            $word->parse($lexicalString, $maxVariants);
        } else {
            $word->original = $lexicalString;
        }
        return $word;
    }

    /**
     * @param $word
     * @param int|null $maxVariants
     *
     * @return Word
     */
    public static function stemm($word, $maxVariants = null)
    {
        try {
            $lexicalString = Mystem::stemm($word);
            return self::newFromLexicalString(isset($lexicalString[0]) ? $lexicalString[0] : $word, $maxVariants);
        } catch (\Exception $e) {
        }
    }

    /**
     * Normalized word
     *
     * @return string
     */
    public function normalized()
    {
        if (isset($this->variants[0], $this->variants[0]['normalized'])) {
            return $this->variants[0]['normalized'];
        } else {
            return '';
        }
    }

    public function __toString()
    {
        return $this->normalized();
    }

    /**
     * Parse raw morphological data from mystem and fill Word object data
     * @param array $lexicalString - prepared string from Mystem
     * @param int $maxVariants
     */
    protected function parse($lexicalString, $maxVariants = null)
    {
        $counter = 0;
        $this->original = $lexicalString['text'];
        $analysis = $lexicalString['analysis'];

        foreach ($analysis as $aVariant) {
            $variant = [
                'normalized' => $aVariant['lex'],
                'strict' => isset($aVariant['qual']) && $aVariant['qual'] === 'bastard',
                'grammemes' => preg_split("/(,|=)/", $aVariant['gr'])
            ];

            $this->variants[$counter++] = $variant;
            if ($maxVariants !== null && $counter >= $maxVariants) {
                break;
            }
        }
    }

    /**
     * @param string $grammeme - grammar primitive from Grammeme
     *
     * @return int
     */
    public function addGrammeme(string $grammeme): int
    {
        $counter = 0;
        $count = count($this->variants);
        for ($i = 0; $i < $count; $i++) {
            $counter += $this->addGrammemeInVariant($grammeme, $i);
        }
        return $counter;
    }

    /**
     * @param string $grammeme - grammar primitive from Grammeme
     * @param int $level
     *
     * @return bool
     */
    protected function addGrammemeInVariant(string $grammeme, int $level = null): bool
    {
        if (!isset($this->variants[$level]) || in_array($grammeme, $this->variants[$level]['grammemes'])) {
            return false;
        }

        $this->variants[$level]['grammemes'][] = $grammeme;

        return true;
    }

    /**
     * @param string $grammeme - grammar primitive from Grammeme
     *
     * @return int
     */
    public function removeGrammeme(string $grammeme): int
    {
        $counter = 0;
        $count = count($this->variants);

        for ($i = 0; $i < $count; $i++) {
            $counter += $this->removeGrammemeInVariant($grammeme, $i);
        }

        return $counter;
    }

    /**
     * @param string $grammeme - grammar primitive from Grammeme
     * @param int $level
     *
     * @return bool
     */
    protected function removeGrammemeInVariant(string $grammeme, int $level): bool
    {
        $key = array_search($grammeme, $this->variants[$level]['grammemes']);
        unset($this->variants[$level]['grammemes'][$key]);

        return $key !== false;
    }

    /**
     * Search grammese primitive in word variants
     * @param string $grammeme - grammar primitive from Grammeme
     * @param int $level - variants maximum depth
     *
     * @return boolean
     */
    public function checkGrammeme(string $grammeme, int $level = null): bool
    {
        $counter = 0;

        foreach ($this->variants as $variant) {
            if (in_array($grammeme, $variant['grammemes'])) {
                return true;
            } elseif ($level !== null && ++$counter >= $level) {
                return false;
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function getOriginal(): string
    {
        return $this->original;
    }

    /**
     * Get verb time: present, past or future
     * @param int $variant find in which morphological variant
     *
     * @return null|string Grammeme::PRESENT, Grammeme::PAST or Grammeme::FUTURE
     */
    public function getVerbTime($variant = 0)
    {
        return $this->searchGrammemeInList(array(
            Grammeme::PRESENT,
            Grammeme::FUTURE,
            Grammeme::PAST
        ), $variant);
    }

    /**
     * @param int $variant find in which morphological variant
     *
     * @return null|string Grammeme::V_1, Grammeme::V_2 or Grammeme::V_3
     */
    public function getVerbPerson($variant = 0)
    {
        return $this->searchGrammemeInList(array(
            Grammeme::V_1,
            Grammeme::V_2,
            Grammeme::V_3
        ), $variant);
    }

    /**
     * Get count: single or plural
     * @param int $variant find in which morphological variant
     * @return null|string - Grammeme
     */
    public function getCount($variant = 0)
    {
        return $this->searchGrammemeInList([
            Grammeme::SINGULAR,
            Grammeme::PLURAL
        ], $variant);
    }

    /**
     * Get gender
     * @param int $variant find in which morphological variant
     * @return null|string - Grammeme
     */
    public function getGender($variant = 0)
    {
        return $this->searchGrammemeInList([
            Grammeme::FEMININE,
            Grammeme::MASCULINE,
            Grammeme::NEUTER
        ], $variant);
    }

    /**
     * Get animate
     * @param int $variant find in which morphological variant
     * @return null|string - Grammeme
     */
    public function getAnimate($variant = 0)
    {
        return $this->searchGrammemeInList(array(
            Grammeme::ANIMATE,
            Grammeme::INANIMATE
        ), $variant);
    }

    /**
     * Get noun case
     * @param int $variant
     * @return null|string - Grammeme
     */
    public function getNounCase($variant = 0)
    {
        return $this->searchGrammemeInList(array(
            Grammeme::NOMINATIVE,
            Grammeme::GENITIVE,
            Grammeme::DATIVE,
            Grammeme::ACCUSATIVE,
            Grammeme::INSTRUMENTAL,
            Grammeme::PREPOSITIONAL,
            Grammeme::PARTITIVE,
            Grammeme::LOCATIVE,
            Grammeme::VOCATIVE,
        ), $variant);
    }

    /**
     * @param array $constants
     * @param int $variant
     * @return null|string
     */
    protected function searchGrammemeInList(array $constants, $variant = 0)
    {
        if (!isset($this->variants[$variant])) {
            return null;
        }

        foreach ($constants as $grammeme) {
            if (in_array($grammeme, $this->variants[$variant]['grammemes'])) {
                return $grammeme;
            }
        }

        return null;
    }

    /**
     * @return bool
     */
    public function isBadWord()
    {
        $original = mb_strtolower($this->getOriginal(), 'UTF-8');
        if ($this->checkGrammeme(Grammeme::OTHER_VULGARISM)) {
            $inExceptions = in_array($original, self::$falsePositiveList)
                || (in_array($this->normalized(), self::$falsePositiveNormalizedList)
                    && !in_array($original, self::$falseNegativeList));

            if ($inExceptions) {
                $this->removeGrammeme(Grammeme::OTHER_VULGARISM);
            }

            return !$inExceptions;
        } else {
            $inExceptions = in_array($original, self::$falseNegativeList)
                || (in_array($this->normalized(), self::$falseNegativeNormalizedList)
                    && !in_array($original, self::$falsePositiveList));

            if ($inExceptions) {
                $this->addGrammeme(Grammeme::OTHER_VULGARISM);
            }

            return $inExceptions;
        }
    }
}
