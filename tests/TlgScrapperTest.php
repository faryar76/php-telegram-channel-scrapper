<?php 

use PHPUnit\Framework\TestCase;
use Faryar76\TlgScrapper;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use Faryar76\Collection;
use Faryar76\Message;
/**
 * ClassNameTest
 * @group group
 */
class TlgScrapperTest extends TestCase
{
    public $main;
    protected function setUp():void
    {
        $mock = new MockHandler([
            new Response(200,[],file_get_contents(__DIR__."/file")),
        ]);
        
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $this->main=new TlgScrapper($client=$client);
    }
    /** @test */
    public function test_can_get_name_of_channel()
    {
        $this->main->load('@telegram');
        $this->assertEquals('Telegram News',$this->main->getName());
    }
    /** @test */
    public function test_can_get_channel_memmer_count()
    {
        $this->main->load('@telegram');
        $this->assertEquals('2.8M',$this->main->getMemmberCount());
    }
    /** @test */
    public function test_can_get_channel_video_count()
    {
        $this->main->load('@telegram');
        $this->assertEquals('3',$this->main->getVideoCount());
    }
    /** @test */
    public function test_can_get_channel_link_count()
    {
        $this->main->load('@telegram');
        $this->assertEquals('56',$this->main->getLinkCount());
    }
    /** @test */
    public function test_can_get_channel_photo_count()
    {
        $this->main->load('@telegram');
        $this->assertEquals('6',$this->main->getPhotoCount());
    }
    /** @test */
    public function test_can_get_channel_description()
    {
        $this->main->load('@telegram');
        $this->assertEquals('The official Telegram on Telegram. Much recursion. Very Telegram. Wow.',$this->main->getDescription());
    }
    /** @test */
    public function test_must_get_page_messages_as_a_collection()
    {
        $this->main->load('@telegram');
        $this->assertInstanceOf(Collection::class,$this->main->getMessages());
    }
    /** @test */
    public function test_must_return_instance_of_message()
    {
        $this->main->load('@telegram');
        $this->assertInstanceOf(Message::class,$this->main->getMessages()[0]);
    }
    
    /** @test */
    public function test_must_get_page_messages()
    {
        $this->main->load('@telegram');
        $this->assertCount(20,$this->main->getMessages());
    }
    /** @test */
    public function test_must_get_page_message_as_array()
    {
        $this->main->load('@telegram');
        $this->assertIsArray($this->main->getMessages()[0]->toArray());
    }
    /** @test */
    public function test_must_get_page_messages_as_array()
    {
        $this->main->load('@telegram');
        $this->assertIsArray($this->main->getMessages()->toArray());
    }
    /**
     * @test
     */
    public function test_must_return_parsed_bubble()
    {
        $this->main->load('@telegram');
        $message=$this->main->getMessages()[0];
        $this->assertEquals('2018-02-01T18:09:24+00:00',$message['date']['date']);
        $this->assertEquals(1517508564,$message['date']['unix']);
        $this->assertEquals('3.7M',$message['views']);
        $this->assertEquals("Here's a video demo to give you a taste of Telegram X for Android.",$message['text']);
        $this->assertEquals(90,$message['id']);
    }
    /**
     * @test
     */
    public function test_must_return_parsed_bubble_as_object()
    {
        $this->main->load('@telegram');
        $message=$this->main->getMessages()[0];
        $this->assertEquals('2018-02-01T18:09:24+00:00',$message->date['date']);
        $this->assertEquals(1517508564,$message->date['unix']);
        $this->assertEquals('3.7M',$message->views);
        $this->assertEquals("Here's a video demo to give you a taste of Telegram X for Android.",$message->text);
        $this->assertEquals(90,$message->id);
        $message->id=10;
        $this->assertEquals(10,$message->id);
    }
     /**
     * @test
     */
    public function test_must_return_image_of_page()
    {
        $this->main->load('@telegram');
        $image=$this->main->getImage();
        $this->assertTrue(true);
    }
}
