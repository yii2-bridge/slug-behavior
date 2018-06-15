# Yii2 Bridge Slug Behavior

[Yii2](http://www.yiiframework.com) [bridge](https://github.com/naffiq/yii2-bridge) slug behavior

This code is inspired by [https://github.com/zelenin/yii2-slug-behavior](https://github.com/zelenin/yii2-slug-behavior), with correction of errors related to the Kazakh language.
Full documentation about this behavior, you can read on [the above repository](https://github.com/zelenin/yii2-slug-behavior).
## Installation

### Composer

The preferred way to install this extension is through [Composer](http://getcomposer.org/).

Either run ```composer require yii2-bridge/slug-behavior:^0.1```

or add ```"yii2-bridge/slug-behavior": "^0.1"``` to the require section of your ```composer.json```

### Using

Attach the behavior in your model:

```php
public function behaviors()
{
    return [
        'slug' => [
            'class' => 'Bridge\Slug\BridgeSlugBehavior',
            'slugAttribute' => 'slug',
            'attribute' => 'title',
            // If intl extension is enabled, see http://userguide.icu-project.org/transforms/general.
            'transliterateOptions' => 'Russian-Latin/BGN; Any-Latin; Latin-ASCII; NFD; [:Nonspacing Mark:] Remove; NFC;'
        ],
    ];
}
```

## Author

[Altynbek Kazezov](https://github.com/altynbek07/), e-mail: [altinbek__97@mail.ru](mailto:altinbek__97@mail.ru)