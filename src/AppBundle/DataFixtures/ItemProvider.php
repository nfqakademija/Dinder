<?php

namespace AppBundle\DataFixtures;

use Faker\Provider\Base as BaseProvider;

class ItemProvider extends BaseProvider
{
    protected static $category = [
        'Accessories', 'Jewelry', 'Cosmetics', 'Books', 'Mobiles', 'Computers', 'Games', 'Toys',
        'Furniture', 'Appliances', 'Tools', 'Sports', 'Music', 'Tickets', 'Photo', 'Audio', 'Video', 'Artwork',
        'Clocks', 'Handicraft', 'Bicycles', 'Real Estate'
    ];

    protected static $title = [
        'Cheese Pizza', 'Hamburger', 'Cheeseburger', 'Bacon Burger', 'Bacon Cheeseburger',
        'Little Hamburger', 'Little Cheeseburger', 'Little Bacon Burger', 'Little Bacon Cheeseburger',
        'Veggie Sandwich', 'Cheese Veggie Sandwich', 'Grilled Cheese',
        'Cheese Dog', 'Bacon Dog', 'Bacon Cheese Dog', 'Pasta',
        'Beer', 'Bud Light', 'Budweiser', 'Miller Lite',
        'Milk Shake', 'Tea', ' Sweet Tea', 'Coffee', 'Hot Tea',
        'Champagne', 'Wine', 'Limonade', 'Coca cola', 'Diet-Coke',
        'Water', 'Sprite', 'Orange Juice', 'Iced Coffee',
        'Chair', 'Car', 'Computer', 'Gloves', 'Pants', 'Shirt', 'Table', 'Shoes', 'Hat', 'Plate', 'Knife', 'Bottle',
        'Coat', 'Lamp', 'Keyboard', 'Bag', 'Bench', 'Clock', 'Watch', 'Wallet'
    ];

    public function itemCategory()
    {
        return static::randomElement(static::$category);
    }

    public function itemTitle()
    {
        return static::randomElement(static::$title);
    }
}
