SHELL=/bin/bash

hello:
	@echo 'This is make file app'

#console-app:
	#@docker exec train-php-cli php ./console/app.php

check:
	docker exec train-php-cli ./vendor/bin/phpcbf --standard=PSR12 ./app
	docker exec train-php-cli ./vendor/bin/phpcs --standard=PSR12 ./app
	docker exec train-php-cli ./vendor/bin/phpstan analyse -l 8 ./app
	docker exec train-php-cli ./vendor/bin/phpunit