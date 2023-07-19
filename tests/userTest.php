<?php
//run with this in the PHPUnit folder  ./phpunit.phar ../tests/userTest.php

use PHPUnit\Framework\TestCase;
require_once '../User.php'; // Update this path to the actual location of your User.php file
require_once 'constants.php';


class UserTest extends TestCase
{
    public function testGetKarma()
    {
       
        $userId = 1; 
        $expectedKarma = 0; 
        
       
        $actualKarma = User::getKarma($userId);

        
        $this->assertSame($expectedKarma, $actualKarma);
    }
}
?>