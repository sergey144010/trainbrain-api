<?php

namespace App\Services\Question;

use App\Entities\Question;

class QuestionFiller
{
    public static function getQuestionFromData(\stdClass $data): Question
    {
        foreach (Question::AVAILABLE_KEY as $key) {
            if (! property_exists($data, $key)) {
                throw new \RuntimeException('Key not found');
            }
        }

        $question = new Question();

        $reflectionClass = new \ReflectionClass($question);

        $reflectionClass->getProperty('state')
            ->setValue(
                $question,
                [
                    Question::STATE_KEY_QUESTION => $data->{Question::STATE_KEY_QUESTION},
                    Question::STATE_KEY_ANSWERS => $data->{Question::STATE_KEY_ANSWERS},
                    Question::STATE_KEY_TRUE_ID => $data->{Question::STATE_KEY_TRUE_ID},
                ]
            );

        $reflectionClass->getProperty(Question::STATE_KEY_STATUS)
            ->setValue($question, $data->{Question::STATE_KEY_STATUS});

        $reflectionClass->getProperty(Question::STATE_KEY_CURRENT)
            ->setValue($question, $data->{Question::STATE_KEY_CURRENT});

        return $question;
    }
}
