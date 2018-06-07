<?php

namespace naffiq\bridge\behaviors


use Zelenin\yii\behaviors\Slug;
use Zelenin\yii\behaviors\Service\Slugifier;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class BridgeSlugBehavior extends Slug
{
    /**
     * @var bool
     */
    private $slugIsEmpty = false;

    /**
     * @inheritdoc
     */
    protected function getValue($event)
    {
        /* @var $owner ActiveRecord */
        $owner = $this->owner;

        if (!empty($owner->{$this->slugAttribute}) && !$this->slugIsEmpty && $this->immutable) {
            $slug = $owner->{$this->slugAttribute};
        } else {
            if ($owner->getIsNewRecord()) {
                $this->slugIsEmpty = true;
            }
            if ($this->attribute !== null) {
                $attributes = $this->attribute;

                $slugParts = array_map(function ($attribute) {
                    return ArrayHelper::getValue($this->owner, $attribute);
                }, $attributes);

                $slug = $this->slugify(implode($this->replacement, $slugParts), $this->replacement, $this->lowercase);

                if (!$owner->getIsNewRecord() && $this->slugIsEmpty) {
                    $owner->{$this->slugAttribute} = $slug;
                    $owner->save(false, [$this->slugAttribute]);
                }
            } else {
                $slug = parent::getValue($event);
            }
        }

        if ($this->ensureUnique) {
            $baseSlug = $slug;
            $iteration = 0;
            while (!$this->validateSlug($slug)) {
                $iteration++;
                $slug = $this->generateUniqueSlug($baseSlug, $iteration);
            }
        }

        return $slug;
    }

    /**
     * @param string $string
     * @param string $replacement
     * @param bool $lowercase
     *
     * @return string
     */
    private function slugify($string, $replacement = '-', $lowercase = true)
    {
        $string = $this->transliterateKazakhToLatin($string);

        $transliterateOptions = $this->transliterateOptions !== null ? $this->transliterateOptions : 'Any-Latin; Latin-ASCII; NFD; [:Nonspacing Mark:] Remove; NFKC;';
        return (new Slugifier($transliterateOptions, $replacement, $lowercase))->slugify($string);
    }

    /**
     * Заменяем казахские буквы на латинские
     *
     * @param $string
     * @return string
     */
    private function transliterateKazakhToLatin($string)
    {
        return strtr($string, self::getKazakhAlphabet());
    }

    /**
     * Возвращает массив с казахсикими буквами с латиницой
     *
     * @return array
     */
    private function getKazakhAlphabet() {
        return [
            'ә' => 'a',
            'ғ' => 'g',
            'қ' => 'q',
            'ң' => 'n',
            'ө' => 'o',
            'ұ' => 'u',
            'ү' => 'u',
            'һ' => 'h',
            'і' => 'i',

            'Ә' => 'A',
            'Ғ' => 'G',
            'Қ' => 'Q',
            'Ө' => 'O',
            'Ұ' => 'U',
            'Ү' => 'U',
            'Һ' => 'H',
            'І' => 'I',
        ];
    }
}