<?php
/**
 * Mockery
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://github.com/padraic/mockery/master/LICENSE
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to padraic@php.net so we can send you a copy immediately.
 *
 * @category   Mockery
 * @package    Mockery
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2010-2014 Pádraic Brady (http://blog.astrumfutura.com)
 * @license    http://github.com/padraic/mockery/blob/master/LICENSE New BSD License
 */

use Mockery\Adapter\Phpunit\MockeryTestCase;

class ReceivedMethodCallsTest extends MockeryTestCase
{
    public function testRetrieveNotMatchingMethodCalls()
    {
        $expectationMock = $this->getMockBuilder('\Mockery\Expectation')->disableOriginalConstructor()->getMock();
        $expectationMock
            ->expects($this->any())
            ->method('getName')
            ->willReturn('setBar');
        $expectationMock
            ->expects($this->any())
            ->method('matchArgs')
            ->with(array('param1'))
            ->willReturn(false);

        $calls = new \Mockery\ReceivedMethodCalls();
        $calls->push(new \Mockery\MethodCall('setFoo', array('param1')));
        $calls->push(new \Mockery\MethodCall('setBar', array('param1')));

        $notMatchingCalls = $calls->getNotMatchingCalls($expectationMock);

        $this->assertCount(1, $notMatchingCalls, 'Expected count of not-matching-call does not match.');
        $this->assertEquals("setBar('param1')", (string) $notMatchingCalls[0]);
    }
}
