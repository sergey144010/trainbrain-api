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

    /**
     * @return Array<Question>
     */
    public function getCollection(): array
    {
        $collection = [];
        $i = 0;
        $question = new Question();
        foreach ($this->definitionProvider->getCollection() as $definition) {
            /** if contain one conditions */
            if ($i === self::COUNT_ANSWER_TO_QUESTION) {
                $question->make();
                $collection[] = $question;
                $i = 1;
                $question = new Question();
                $question->addDefinition($definition);

                continue;
            }

            $question->addDefinition($definition);
            $i++;

            /** if contain two conditions */
            /*
            if (
                $i != 0 &&
                $i % self::COUNT_ANSWER_TO_QUESTION === 0
            ) {
                $question->make();
                $collection[] = $question;
                $question = new Question();
                $question->addDefinition($definition);
                $i = 1;

                continue;
            }

            $question->addDefinition($definition);
            $i++;
            */
        }

        if ($question->hasDefinitions()) {
            $question->make();
            $collection[] = $question;
        }

        return $collection;
    }
}
