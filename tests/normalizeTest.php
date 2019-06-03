<?php 

use PHPUnit\Framework\TestCase;
use Faryar76\Normalizer;
/**
 * ClassNameTest
 * @group group
 */
class channelNamenormalizeTest extends TestCase
{
    /** @test */
    public function test_must_return_parsed_chanell_username()
    {
        $parser=new Normalizer();
        $username='@dailychannelsbot';
        $actual=$parser->username($username);
        $this->assertEquals('dailychannelsbot', $actual);
    }

}
