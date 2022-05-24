<?php

require_once "./vendor/autoload.php";

use App\Connections\MongoDbClient;

# TODO: create console application
/*
use App\Application;

(new Application());
*/

/* Like migration */
class Migration
{
    public function __construct()
    {
    }

    public function run(): void
    {
        $collection = (new MongoDbClient())->collection();

        $insertManyResult = $collection->insertMany(
            [
                [
                    'id' => 1,
                    'name' => 'array_change_key_case',
                    'definition' => 'Changes the case of all keys in an array'
                ],
                [
                    'id' => 2,
                    'name' => 'array_chunk',
                    'definition' => 'Split an array into chunks'
                ],
                [
                    'id' => 3,
                    'name' => 'array_column',
                    'definition' => 'Return the values from a single column in the input array'
                ],
                [
                    'id' => 4,
                    'name' => 'array_combine',
                    'definition' => 'Creates an array by using one array for keys and another for its values'
                ],
                [
                    'id' => 5,
                    'name' => 'array_count_values',
                    'definition' => 'Counts all the values of an array'
                ],
                [
                    'id' => 6,
                    'name' => 'array_diff_assoc',
                    'definition' => 'Computes the difference of arrays with additional index check'
                ],
                [
                    'id' => 7,
                    'name' => 'array_diff_key',
                    'definition' => 'Computes the difference of arrays using keys for comparison'
                ],
                [
                    'id' => 8,
                    'name' => 'array_diff_uassoc',
                    'definition' => 'Computes the difference of arrays with additional index check which is performed by a user supplied callback function'
                ],
                [
                    'id' => 9,
                    'name' => 'array_diff_ukey',
                    'definition' => 'Computes the difference of arrays using a callback function on the keys for comparison'
                ],
                [
                    'id' => 10,
                    'name' => 'array_diff',
                    'definition' => 'Computes the difference of arrays'
                ],
            ]
        );

        printf("Inserted %d document(s)\n", $insertManyResult->getInsertedCount());

        var_dump($insertManyResult->getInsertedIds());
    }
}

class Console
{
    public function run(): void
    {
        var_dump((new \App\Repositories\DefinitionRepository())->definitionCollection());
    }
}

$options = getopt('t:');
if (! isset($options['t'])) {
    echo 'Need set up type like option -t ( -t migration, -t console)' . PHP_EOL;

    return;
}

if ($options['t'] === 'migration') {
    (new Migration())->run();

    return;
}

if ($options['t'] === 'console') {
    (new Console())->run();

    return;
}
