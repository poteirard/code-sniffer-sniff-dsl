<?php

namespace Matthias\Codesniffer\Sequence\Expectation;

use Matthias\Codesniffer\Sequence\Exception\ExpectationNotMatched;
use Matthias\Codesniffer\Sequence\SequenceInterface;

class ExactMatch implements ExpectationInterface
{
    private $code;
    private $content;

    public function __construct($code, $content = null)
    {
        $this->code = $code;
        $this->content = $content;
    }

    public function match(SequenceInterface $sequence)
    {
        if ($sequence->endOfSequence()) {
            throw new ExpectationNotMatched();
        }

        $nextToken = $sequence->peek();

        if ($nextToken['code'] === $this->code) {
            if ($this->content === null || $this->content === $nextToken['content']) {
                $sequence->next();
                return;
            }
        }

        throw new ExpectationNotMatched();
    }
}
