<?php

namespace Matthias\Codesniffer\Tests\Sequence;

use Matthias\Codesniffer\Sequence\BackwardSequence;
use Matthias\Codesniffer\Tests\Sequence\Expectation\Spy;
use Matthias\Codesniffer\TokenBuilder;

class BackwardSequenceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider tokensProvider
     */
    public function it_walks_backward_over_an_array_of_tokens($tokens, $tokenIndex, $expectedTokens)
    {
        $forwardSequence = new BackwardSequence();
        $expectationSpy = new Spy();
        $forwardSequence->addExpectation($expectationSpy);

        $forwardSequence->matches($tokens, $tokenIndex);

        $this->assertEquals($expectedTokens, $expectationSpy->getTokens());
    }

    public function tokensProvider()
    {
        $tokens = array(
            TokenBuilder::create('T_OPEN_TAG')->build(),
            TokenBuilder::create('T_WHITESPACE')->build(),
            TokenBuilder::create('T_NAMESPACE')->build(),
        );

        return array(
            // start with index 0, expect no tokens
            array(
                $tokens,
                0,
                array()
            ),
            // start with index 1, expect token at index 0
            array(
                $tokens,
                1,
                array($tokens[0])
            ),
            // start with index 2, expect tokens starting at index 0, ending at index 1
            array(
                $tokens,
                2,
                array($tokens[1], $tokens[0])
            ),
        );
    }


    /**
     * @test
     */
    public function it_fails_when_next_is_called_but_end_of_sequence_is_reached()
    {
        $forwardSequence = new BackwardSequence();
        $this->setExpectedException('Matthias\Codesniffer\Sequence\Exception\EndOfSequence');
        $forwardSequence->next();
    }

    /**
     * @test
     */
    public function it_fails_when_peek_is_called_but_end_of_sequence_is_reached()
    {
        $forwardSequence = new BackwardSequence();
        $this->setExpectedException('Matthias\Codesniffer\Sequence\Exception\EndOfSequence');
        $forwardSequence->peek();
    }
}
