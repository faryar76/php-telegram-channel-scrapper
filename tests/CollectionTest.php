<?php 

use PHPUnit\Framework\TestCase;
use Faryar76\Collection;
/**
 * ClassNameTest
 * @group group
 */
class CollectionTest extends TestCase
{
    /** @test */
    public function test_must_return_first_item_of_Collcetion()
    {
        $collection=new Collection([1,2,3,4,5]);
        $actual=$collection->first();
        $this->assertEquals(1, $actual);
    }
    /** @test */
    public function test_must_return_last_item_of_Collcetion()
    {
        $collection=new Collection([1,2,3,4,5]);
        $actual=$collection->latest();
        $this->assertEquals(5, $actual);
    }
}
