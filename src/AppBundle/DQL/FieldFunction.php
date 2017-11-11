<?php

namespace AppBundle\DQL;

use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;

class FieldFunction extends FunctionNode
{
    private $field;
    private $values = array();

    public function parse(Parser $parser): void
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->field = $parser->ArithmeticPrimary();

        $lexer = $parser->getLexer();

        while (count($this->values) < 1 ||
            $lexer->lookahead[ 'type' ] !== Lexer::T_CLOSE_PARENTHESIS) {
            $parser->match(Lexer::T_COMMA);
            $this->values[ ] = $parser->ArithmeticPrimary();
        }

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker): string
    {
        $query = '(CASE '.$this->field->dispatch($sqlWalker);
        for ($i = 0, $limiti = count($this->values); $i < $limiti; $i++) {
            $query .= ' WHEN '.$this->values[ $i ]->dispatch($sqlWalker).' THEN '.($i + 1);
        }
        $query .= ' ELSE 0 END)';

        return $query;
    }
}
