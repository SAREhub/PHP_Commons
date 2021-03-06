<?php

use PHPUnit\Framework\TestCase;
use SAREhub\Commons\Misc\Dsn;
use SAREhub\Commons\Zmq\PublishSubscribe\Subscriber;

class SubscriberTest extends TestCase
{

    /**
     * @var \ZMQContext | PHPUnit_Framework_MockObject_MockObject
     */
    private $socketMock;

    private $dsn;

    /**
     * @var Subscriber
     */
    private $subscriber;

    protected function setUp()
    {
        parent::setUp();
        $context = $this->createMock(ZMQContext::class);
        $this->socketMock = $this->createMock(ZMQSocket::class);
        $context->method('getSocket')->willReturn($this->socketMock);
        $this->subscriber = Subscriber::inContext($context);
        $this->dsn = Dsn::tcp()->endpoint('127.0.0.1:1000');
    }

    public function testCreate()
    {
        $context = $this->createMock(ZMQContext::class);
        $context->expects($this->once())->method('getSocket')
            ->with(ZMQ::SOCKET_SUB)->willReturn($this->socketMock);
        $this->subscriber = Subscriber::inContext($context);
    }

    public function testReceiveWhenNotConnectedThenThrowException()
    {
        $this->expectException(LogicException::class);
        $this->subscriber->receive();
    }

    public function testReceiveWhenConnectedThenSocketCallReceiveInNonBlocking()
    {
        $this->subscriber->connect($this->dsn);
        $this->socketMock->expects($this->once())->method('recvmulti')
            ->with(ZMQ::MODE_DONTWAIT)->willReturn("topic");
        $this->subscriber->receive();
    }

    public function testReceiveWhenConnectedAndMessageAndTopicMatchThenReturnMessage()
    {
        $this->subscriber->connect($this->dsn);
        $this->subscriber->subscribe('topic1');
        $this->socketMock->method('recvmulti')->willReturn(["topic1", "message"]);
        $this->assertEquals(['topic' => "topic1", 'body' => "message"], $this->subscriber->receive());
    }

    public function testReceiveWhenConnectedAndMessageAndTopicNonMatchThenReturnNull()
    {
        $this->subscriber->connect($this->dsn);
        $this->socketMock->method('recvmulti')->willReturn(["topic1", "message"]);
        $this->assertNull($this->subscriber->receive());
    }

    public function testReceiveWhenConnectedAndNoMessageThenReturnNull()
    {
        $this->subscriber->connect($this->dsn);
        $this->socketMock->expects($this->once())->method('recvmulti')->willReturn(false);
        $this->assertNull($this->subscriber->receive());
    }

    public function testReceiveWhenConnectedAndWaitModeThenSocketCallReceiveBlocking()
    {
        $this->subscriber->connect($this->dsn);
        $this->socketMock->expects($this->once())->method('recvmulti')
            ->with(0)->willReturn(false);
        $this->subscriber->receive(true);
    }

    public function testSubscribeThenSocketSubscribeOptionSet()
    {
        $topic = "topic";
        $this->socketMock->expects($this->once())->method('setSockOpt')->with(\ZMQ::SOCKOPT_SUBSCRIBE, $topic);
        $this->subscriber->subscribe($topic);
    }

    public function testSubscribeWhenSubscribedThenNoop()
    {
        $topic = "topic";
        $this->subscriber->subscribe($topic);
        $this->socketMock->expects($this->never())->method('setSockOpt');
        $this->subscriber->subscribe($topic);
    }

    public function testSubscribeThenReturnThis()
    {
        $this->assertSame($this->subscriber, $this->subscriber->subscribe("topic"));
    }

    public function testUnsubscribeWhenSubscribeThenSocketUnsubscribeOptionSet()
    {
        $topic = "topic";
        $this->subscriber->subscribe($topic);
        $this->socketMock->expects($this->once())->method('setSockOpt')
            ->with(\ZMQ::SOCKOPT_UNSUBSCRIBE, $topic);
        $this->subscriber->unsubscribe($topic);
    }

    public function testUnsubscribeWhenNotSubscribedThenNoop()
    {
        $this->socketMock->expects($this->never())->method('setSockOpt');
        $this->subscriber->unsubscribe("topic");
    }

    public function testUnsubscribeThenReturnThis()
    {
        $this->assertSame($this->subscriber, $this->subscriber->unsubscribe("topic"));
    }

    public function testGetTopicsWhenSubscribeThenReturnTopics()
    {
        $this->subscriber->subscribe("topic");
        $this->assertEquals(['topic'], $this->subscriber->getTopics());
    }

    public function testIsSubscribedWhenNotSubscribeThenReturnFalse()
    {
        $this->assertFalse($this->subscriber->isSubscribed("topic"));
    }

    public function testIsSubscribedWhenSubscribeAndUnsubscribeThenReturnFalse()
    {
        $topic = "topic";
        $this->subscriber->subscribe($topic);
        $this->subscriber->unsubscribe($topic);
        $this->assertFalse($this->subscriber->isSubscribed($topic));
    }

    public function testIsSubscribeWhenSubscribedAllThenReturnTrue()
    {
        $this->subscriber->subscribeAll();
        $this->assertTrue($this->subscriber->isSubscribed('abc'));
    }


}
