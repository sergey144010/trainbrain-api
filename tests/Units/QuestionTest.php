<?php
use PHPUnit\Framework\TestCase;

class QuestionTest extends TestCase
{
    public function testMakeState()
    {
        $definition1 = new \App\Entities\Definition(1, 'one', 'OneDefinition');
        $definition2 = new \App\Entities\Definition(2, 'two', 'TwoDefinition');
        $definition3 = new \App\Entities\Definition(3, 'three', 'ThreeDefinition');

        $question = new \App\Entities\Question();
        $question->addDefinition($definition1);
        $question->addDefinition($definition2);
        $question->addDefinition($definition3);

        self::assertEquals(['qwe'=>123], $question->state());
    }
}