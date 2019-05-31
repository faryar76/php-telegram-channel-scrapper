<?php 
use PHPUnit\Framework\TestCase;
use Faryar76\Guzzle;
/**
 * ClassNameTest
 * @group group
 */
class ClassNameTest extends TestCase
{
    /** @test */
    public function test_load_a_url()
    {
        $client=new Guzzle();    
        $actual=$client->get('http://www.google.com')->getStatus();
        $this->assertEquals(200,$actual);
    }


}
