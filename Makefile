install:
	composer install

gendiff:
	./bin/gendiff

lint:
	composer exec --verbose phpcs -- --standard=PSR12 src bin tests

validate:
	composer validate

test:
	composer exec --verbose phpunit tests