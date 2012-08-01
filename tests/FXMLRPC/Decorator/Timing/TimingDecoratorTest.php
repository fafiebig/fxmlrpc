<?php
/**
 * Copyright (C) 2012
 * Lars Strojny, InterNations GmbH <lars.strojny@internations.org>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace FXMLRPC\Decorator\Timing;

use FXMLRPC\Decorator\Timing\TimingDecorator;

class TimingDecoratorTest extends \PHPUnit_Framework_TestCase
{
    private $wrapped;

    private $timer;

    private $decorator;

    public function setUp()
    {
        $this->wrapped = $this
            ->getMockBuilder('FXMLRPC\ClientInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $this->timer = $this
            ->getMockBuilder('FXMLRPC\Decorator\Timing\TimerInterface')
            ->getMock();
        $this->decorator = new TimingDecorator($this->wrapped, $this->timer);
    }

    public function testRecordTimeIsCalled()
    {
        $this->timer
            ->expects($this->once())
            ->method('recordTiming')
            ->with($this->equalTo(0.1, 0.01), 'method', array('arg1', 'arg2'));

        $this->wrapped
            ->expects($this->once())
            ->method('call')
            ->with('method', array('arg1', 'arg2'))
            ->will($this->returnCallback(function() {usleep(100000);}));

        $this->decorator->call('method', array('arg1', 'arg2'));
    }
}