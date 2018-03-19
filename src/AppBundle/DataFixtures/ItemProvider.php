<?php

namespace AppBundle\DataFixtures;

use Faker\Provider\Base as BaseProvider;

class ItemProvider extends BaseProvider
{
    protected static $title = [
        'Tech', 'Cell phone'
    ];

    public function itemTitle(): string
    {
        return 'Tech #' . random_int(100, 999);
    }
}
