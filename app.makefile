SHELL=/bin/bash

check:
	./vendor/bin/phpcbf --standard=PSR12 ./app
	./vendor/bin/phpcs --standard=PSR12 ./app
	./vendor/bin/phpstan analyse -l 8 ./app
	./vendor/bin/phpunit