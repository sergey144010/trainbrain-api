<?php

use App\Entities\Definition;
use App\Entities\Question;
use PHPUnit\Framework\TestCase;

class QuestionTest extends TestCase
{
    public function testMakeDefaultState()
    {
        $definition1 = new Definition(1, 'one', 'OneDefinition');
        $definition2 = new Definition(2, 'two', 'TwoDefinition');
        $definition3 = new Definition(3, 'three', 'ThreeDefinition');

        $question = new Question();
        $question->addDefinition($definition1);
        $question->addDefinition($definition2);
        $question->addDefinition($definition3);

        self::assertArrayHasKey(Question::STATE_KEY_QUESTION, $question->state());
        self::assertArrayHasKey(Question::STATE_KEY_ANSWERS, $question->state());
        self::assertArrayHasKey(Question::STATE_KEY_TRUE_ID, $question->state());
        self::assertArrayHasKey(Question::STATE_KEY_STATUS, $question->state());
        self::assertArrayHasKey(Question::STATE_KEY_CURRENT, $question->state());

        self::assertEquals(null, $question->state()[Question::STATE_KEY_STATUS]);
        self::assertEquals(false, $question->state()[Question::STATE_KEY_CURRENT]);
    }
}
