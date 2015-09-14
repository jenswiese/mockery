<?php

namespace Mockery;

class ReceivedMethodCalls
{
    private $methodCalls = array();
    
    public function push(MethodCall $methodCall)
    {
        $this->methodCalls[] = $methodCall;
    }

    public function verify(Expectation $expectation)
    {
        foreach ($this->methodCalls as $methodCall) {
            if ($methodCall->getMethod() !== $expectation->getName()) {
                continue;
            }

            if (!$expectation->matchArgs($methodCall->getArgs())) {
                continue;
            }

            $expectation->verifyCall($methodCall->getArgs());
        }

        $expectation->verify();
    }

    /**
     * Returns method calls that does not match for given method.
     * Needed e.g. for displaying more information about failing expectation.
     *
     * @param Expectation $expectation
     * @return MethodCall[]
     */
    public function getNotMatchingCalls(Expectation $expectation)
    {
        $notMatchingCalls = array();

        foreach ($this->methodCalls as $methodCall) {
            if ($methodCall->getMethod() !== $expectation->getName()) {
                continue;
            }

            if ($expectation->matchArgs($methodCall->getArgs())) {
                continue;
            }

            array_push($notMatchingCalls, $methodCall);
        }

        return $notMatchingCalls;
    }
}
