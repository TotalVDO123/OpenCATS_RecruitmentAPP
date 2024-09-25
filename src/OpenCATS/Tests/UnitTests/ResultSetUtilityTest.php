<?php

use PHPUnit\Framework\TestCase;

if (! defined('LEGACY_ROOT')) {
    define('LEGACY_ROOT', '.');
}

include_once(LEGACY_ROOT . '/lib/ResultSetUtility.php');

class ResultSetUtilityTest extends TestCase
{
    /* Tests for findRowByColumnValue(). */
    public function testFindRowByColumnValue()
    {
        $input = [
            0 => [
                'ID' => 100,
                'Name' => 'Cat',
                'Sound' => 'Meow',
                'Type' => 'Mammal',
            ],
            1 => [
                'ID' => 200,
                'Name' => 'Dog',
                'Sound' => 'Bark',
                'Type' => 'Mammal',
            ],
            2 => [
                'ID' => 300,
                'Name' => 'Wolf',
                'Sound' => 'Howl',
                'Type' => 'Mammal',
            ],
            3 => [
                'ID' => 400,
                'Name' => 'Cow',
                'Sound' => 'Moo',
                'Type' => 'Mammal',
            ],
            4 => [
                'ID' => 500,
                'Name' => 'Snake',
                'Sound' => 'Hiss',
                'Type' => 'Reptile',
            ],
        ];

        /* Test simple 'finding' functionality. */
        $this->assertSame(
            ResultSetUtility::findRowByColumnValue($input, 'ID', 100),
            0
        );
        $this->assertSame(
            ResultSetUtility::findRowByColumnValue($input, 'ID', 200),
            1
        );
        $this->assertSame(
            ResultSetUtility::findRowByColumnValue($input, 'ID', 300),
            2
        );
        $this->assertSame(
            ResultSetUtility::findRowByColumnValue($input, 'ID', 400),
            3
        );
        $this->assertSame(
            ResultSetUtility::findRowByColumnValue($input, 'ID', 500),
            4
        );
        $this->assertSame(
            ResultSetUtility::findRowByColumnValue($input, 'ID', 500.0),
            4
        );
        $this->assertSame(
            ResultSetUtility::findRowByColumnValue($input, 'ID', '500'),
            4
        );
        $this->assertSame(
            ResultSetUtility::findRowByColumnValue($input, 'Type', 'Mammal'),
            0
        );
        $this->assertSame(
            ResultSetUtility::findRowByColumnValue($input, 'Sound', 'Hiss'),
            4
        );
        $this->assertSame(
            ResultSetUtility::findRowByColumnValue($input, 'ID', 600),
            false
        );

        /* Test skipping. */
        $this->assertSame(
            ResultSetUtility::findRowByColumnValue($input, 'Type', 'Mammal', 1),
            1
        );
        $this->assertSame(
            ResultSetUtility::findRowByColumnValue($input, 'Type', 'Mammal', 2),
            2
        );


        /* Test strict matching. */
        $this->assertSame(
            ResultSetUtility::findRowByColumnValueStrict($input, 'Sound', 'Hiss'),
            4
        );
        $this->assertSame(
            ResultSetUtility::findRowByColumnValueStrict($input, 'ID', '500'),
            false
        );
        $this->assertSame(
            ResultSetUtility::findRowByColumnValueStrict($input, 'ID', 500.0),
            false
        );

        /* Just in case strict and non-strict functions aren't identical... */
        $this->assertSame(
            ResultSetUtility::findRowByColumnValueStrict($input, 'Type', 'Mammal', 1),
            1
        );
        $this->assertSame(
            ResultSetUtility::findRowByColumnValueStrict($input, 'Type', 'Mammal', 2),
            2
        );
    }
}
