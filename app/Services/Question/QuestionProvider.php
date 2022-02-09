<?php

namespace App\Services\Question;

use App\Entities\Question;
use App\Services\Definition\DefinitionProvider;

class QuestionProvider
{
    public const COUNT_ANSWER_TO_QUESTION = 4;

    public function __construct(private DefinitionProvider $definitionProvider)
    {
    }

    /** @return Array<Question> */
    public function getCollection(): array
    {
        $collection = [];
        $i = 0;
        $question = new Question();
        foreach ($this->definitionProvider->getCollection() as $definition) {
            if ($i < self::COUNT_ANSWER_TO_QUESTION) {
                $question->addDefinition($definition);
                $i += 1;
            } else {
                $question->make();
                $collection[] = $question;
                $i = 0;
                $question = new Question();
                $question->addDefinition($definition);
            }
        }

        if ($question->hasDefinitions()) {
            $question->make();
            $collection[] = $question;
        }

        return $collection;
    }
}