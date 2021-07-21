<?php

namespace Suzuken\Ion;

class Tokenizer
{
    public string $in;
    /**
     * @var int[]
     */
    private array $buffer;

    private Token $token;
    private bool $unfinished;
    private int $pos;

    /**
     * Tokenizer constructor.
     */
    public function __construct(string $in)
    {
        $this->in = $in;
        $this->buffer = [];
    }

    public function getBuffer(): array
    {
        return $this->buffer;
    }

    public function getToken(): int
    {
        return $this->token;
    }

    public function isUnfinished(): bool
    {
        return $this->unfinished;
    }

    public function getPos(): int
    {
        return $this->pos;
    }

    public function next()
    {
        $c = null;
        if ($this->unfinished) {
            $c = $this->skipValue();
        }
        
        switch ($c) {
            case -1:
                return $this->ok(Token::tokenEOF, true);
            // TODO implements each token..
            default:
                return $this->invalidChar($c);
        }
    }

    private function ok(int $t, bool $more)
    {
        $this->token = new Token($t);
        $this->unfinished = $more;
    }

    private function skipValue()
    {
    }

    private function invalidChar(int $c)
    {
        if ($c === -1) {
            throw new UnexpectedEOFException($this->pos - 1);
        }
        throw new UnexpectedRuneException($this->pos - 1, $c);
    }
}

class TokenizerException extends \RuntimeException implements \Throwable {
    private int $pos;

    public function __construct(int $pos)
    {
        $this->pos = $pos;
    }
}

class UnexpectedEOFException extends TokenizerException {
}

class UnexpectedRuneException extends TokenizerException {
    private string $c;
    public function __construct(int $pos, string $c)
    {
        parent::__construct($pos);
        $this->c = $c;
    }
}

class Token
{
    const tokenError            =  0; //
	const tokenEOF              =  1; // End of input
	const tokenNumber           =  2; // Haven't seen enough to know which, yet
	const tokenBinary           =  3; // 0b[01]+
	const tokenHex              =  4; // 0x[0-9a-fA-F]+
	const tokenFloatInf         =  5; // +inf
	const tokenFloatMinusInf    =  6; // -inf
	const tokenTimestamp        =  7; // 2001-01-01T00:00:00.000Z

	const tokenSymbol           =  8; // [a-zA-Z_]+
	const tokenSymbolQuoted     =  9; // '[^']+'
	const tokenSymbolOperator   = 10; // +-/*

	const tokenString           = 10; // "[^"]+"
	const tokenLongString       = 11; // '''[^']+'''

	const tokenDot              = 12; // .
	const tokenComma            = 13; // ,
	const tokenColon            = 14; // :
	const tokenDoubleColon      = 15; // ::

	const tokenOpenParen        = 16; // (
	const tokenCloseParen       = 17; // )
	const tokenOpenBrace        = 18; // {
	const tokenCloseBrace       = 19; // }
	const tokenOpenBracket      = 20; // [
	const tokenCloseBracket     = 21; // ]
	const tokenOpenDoubleBrace  = 22; // {{
	const tokenCloseDoubleBrace = 23; // }}

    private int $t;

    public function __construct(int $t)
    {
        assert(self::tokenError <= $t && $t <= self::tokenCloseDoubleBrace);
        $this->t = $t;
    }

    public function __toString(): string
    {
        switch ($this->t) {
            case tokenError:
                return "<error>";
	        case tokenEOF:
                return "<EOF>";
	        case tokenNumber:
                return "<number>";
	        case tokenBinary:
                return "<binary>";
	        case tokenHex:
                return "<hex>";
	        case tokenFloatInf:
                return "+inf";
	        case tokenFloatMinusInf:
                return "-inf";
	        case tokenTimestamp:
                return "<timestamp>";
	        case tokenSymbol:
                return "<symbol>";
	        case tokenSymbolQuoted:
                return "<quoted-symbol>";
	        case tokenSymbolOperator:
                return "<operator>";

	        case tokenString:
                return "<string>";
	        case tokenLongString:
                return "<long-string>";

	        case tokenDot:
                return ".";
	        case tokenComma:
                return ",";
	        case tokenColon:
                return ":";
	        case tokenDoubleColon:
                return "::";

	        case tokenOpenParen:
                return "(";
	        case tokenCloseParen:
                return ")";

	        case tokenOpenBrace:
                return "{";
	        case tokenCloseBrace:
                return "}";

	        case tokenOpenBracket:
                return "[";
	        case tokenCloseBracket:
                return "]";

	        case tokenOpenDoubleBrace:
                return "{{";
	        case tokenCloseDoubleBrace:
                return "}}";

	        default:
                return "<???>";
	    }
    }
}
