<?php

use PHPUnit\Framework\TestCase;

if (!defined('LEGACY_ROOT')) {
    define('LEGACY_ROOT', '.');
}

include_once(LEGACY_ROOT . '/lib/AJAXInterface.php');

class AJAXInterfaceTest extends TestCase
{
    private $AJAXInterface;

    protected function setUp(): void
    {
        $this->AJAXInterface = new AJAXInterface();
    }

    public function testIsRequiredIDValid()
    {
        /* Make sure an unset key does not pass. */
        $random = md5('random' . time());
        $this->assertFalse(
            $this->AJAXInterface->isRequiredIDValid($random, true, true),
            sprintf("\$_POST['%s'] should not exist and should not be a valid required ID", $random)
        );

        /* Make sure -0, non-numeric strings, and symbols never pass. */
        $invalidIDs = ['-0', 'test', '0abc', '1abc', '-abc', '$'];
        foreach ($invalidIDs as $ID) {
            $_REQUEST['isRequiredIDValidTest'] = $ID;
            $this->assertFalse(
                $this->AJAXInterface->isRequiredIDValid('isRequiredIDValidTest', true, true),
                sprintf("'%s' should not be a valid required ID", $ID)
            );
            $this->assertFalse(
                $this->AJAXInterface->isRequiredIDValid('isRequiredIDValidTest', true, false),
                sprintf("'%s' should not be a valid required ID", $ID)
            );
            $this->assertFalse(
                $this->AJAXInterface->isRequiredIDValid('isRequiredIDValidTest', false, true),
                sprintf("'%s' should not be a valid required ID", $ID)
            );
            $this->assertFalse(
                $this->AJAXInterface->isRequiredIDValid('isRequiredIDValidTest', false, false),
                sprintf("'%s' should not be a valid required ID", $ID)
            );
        }

        /* Make sure we don't allow '0' if $allowZero is false. */
        $invalidIDs = [0, '0'];
        foreach ($invalidIDs as $ID) {
            $_REQUEST['isRequiredIDValidTest'] = $ID;
            $this->assertFalse(
                $this->AJAXInterface->isRequiredIDValid('isRequiredIDValidTest', false, true),
                sprintf("'%s' should not be a valid required ID with \$allowZero false", $ID)
            );
            $this->assertFalse(
                $this->AJAXInterface->isRequiredIDValid('isRequiredIDValidTest', false, false),
                sprintf("'%s' should not be a valid required ID with \$allowZero false", $ID)
            );
        }

        /* Make sure we don't allow negatives if $allowNegative is false. */
        $invalidIDs = [-1, -100, '-1', '-100'];
        foreach ($invalidIDs as $ID) {
            $_REQUEST['isRequiredIDValidTest'] = $ID;
            $this->assertFalse(
                $this->AJAXInterface->isRequiredIDValid('isRequiredIDValidTest', true, false),
                sprintf("'%s' should not be a valid required ID with \$allowNegative false", $ID)
            );
            $this->assertFalse(
                $this->AJAXInterface->isRequiredIDValid('isRequiredIDValidTest', false, false),
                sprintf("'%s' should not be a valid required ID with \$allowNegative false", $ID)
            );
        }

        /* Make sure any positive, negative, or 0 number passes valid ID checks
         * if $allowZero and $allowNegative are true.
         */
        $validIDs = [1, 100, -1, -100, 0, '0', '-100', '1', '65535'];
        foreach ($validIDs as $ID) {
            $_REQUEST['isRequiredIDValidTest'] = $ID;
            $this->assertTrue(
                $this->AJAXInterface->isRequiredIDValid('isRequiredIDValidTest', true, true),
                sprintf("'%s' should be a valid required ID", $ID)
            );
        }

        /* Make sure any positive number always passes valid ID checks
         * regardless of $allowZero and $allowNegative.
         */
        $validIDs = [1, 100, '1', '65535'];
        foreach ($validIDs as $ID) {
            $_REQUEST['isRequiredIDValidTest'] = $ID;
            $this->assertTrue(
                $this->AJAXInterface->isRequiredIDValid('isRequiredIDValidTest', false, false),
                sprintf("'%s' should be a valid required ID", $ID)
            );
        }
    }

    public function testIsOptionalIDValid()
    {
        /* Make sure an unset key does not pass. */
        $random = md5('random' . time());
        $this->assertFalse(
            $this->AJAXInterface->isOptionalIDValid($random),
            sprintf("\$_POST['%s'] should not exist and should not be a valid optional ID", $random)
        );

        /* Make sure 0, -0, negative numbers, non-numeric strings, and symbols never pass. */
        $invalidIDs = [0, -1, -100, '0', '-0', '-1', '-100', 'test', '0abc', '1abc', '-abc', '$'];
        foreach ($invalidIDs as $ID) {
            $_REQUEST['isRequiredIDValidTest'] = $ID;
            $this->assertFalse(
                $this->AJAXInterface->isOptionalIDValid('isRequiredIDValidTest'),
                sprintf("'%s' should not be a valid optional ID", $ID)
            );
        }

        /* Make sure any positive number always passes. */
        $validIDs = [1, 100, '1', '65535'];
        foreach ($validIDs as $ID) {
            $_REQUEST['isOptionalIDValidValidTest'] = $ID;
            $this->assertTrue(
                $this->AJAXInterface->isOptionalIDValid('isOptionalIDValidValidTest'),
                sprintf("'%s' should be a valid optional ID", $ID)
            );
        }

        /* Make sure 'NULL' always passes. */
        $_REQUEST['isOptionalIDValidValidTest'] = 'NULL';
        $this->assertTrue(
            $this->AJAXInterface->isOptionalIDValid('isOptionalIDValidValidTest'),
            "'NULL' should be a valid optional ID"
        );
    }
}