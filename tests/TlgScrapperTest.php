<?php 

use PHPUnit\Framework\TestCase;
use Faryar76\TlgScrapper;
/**
 * ClassNameTest
 * @group group
 */
class TlgScrapperTest extends TestCase
{
    /** @test */
    public function test_can_get_name_of_channel()
    {
        $main=new TlgScrapper;
        $main->load('@telegram');
        $this->assertEquals('Telegram News',$main->getName());
    }

}
