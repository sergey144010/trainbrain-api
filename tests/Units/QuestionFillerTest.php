<?php

use App\Entities\Question;
use App\Services\Question\QuestionFiller;
use PHPUnit\Framework\TestCase;

class QuestionFillerTest extends TestCase
{
    public function testGetQuestion()
    {
        $data = new \stdClass();
        $data->{Question::STATE_KEY_QUESTION} = 'Question_1';
        $data->{Question::STATE_KEY_ANSWERS} = [0 => 'Answer_1', 1 => 'Answer_2', 3 => 'Answer_3', 4 => 'Answer_4'];
        $data->{Question::STATE_KEY_TRUE_ID} = 3;
        $data->{Question::STATE_KEY_STATUS} = Question::STATUS_DECIDED_RIGHT;
        $data->{Question::STATE_KEY_CURRENT} = true;

        $question = QuestionFiller::getQuestionFromData($data);

        self::assertEquals($data->{Question::STATE_KEY_QUESTION}, $question->state()[Question::STATE_KEY_QUESTION]);
        self::assertEquals($data->{Question::STATE_KEY_ANSWERS}, $question->state()[Question::STATE_KEY_ANSWERS]);
        self::assertEquals($data->{Question::STATE_KEY_TRUE_ID}, $question->state()[Question::STATE_KEY_TRUE_ID]);
        self::assertEquals($data->{Question::STATE_KEY_STATUS}, $question->state()[Question::STATE_KEY_STATUS]);
        self::assertEquals($data->{Question::STATE_KEY_CURRENT}, $question->state()[Question::STATE_KEY_CURRENT]);
    }
}
