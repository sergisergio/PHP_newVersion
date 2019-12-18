# Fichier Makefile

.PHONY: csfixer quality phpstan

##
## ----------------------------------------------------------------------------
##   Quality
## ----------------------------------------------------------------------------
##

csfixer: ## Run the PHP coding standards fixer on apply mode
  @test -f .php_cs || cp .php_cs.dist .php_cs && \
  php vendor/bin/php-cs-fixer fix --config=.php_cs \
    --cache-file=.php_cs.cache --verbose

quality:
  @vendor/bin/phpmetrics --report-html=var/phpmetrics ./src

phpstan: ## Run the PHP static analysis tool at level max
  php ./vendor/bin/phpstan analyse -l max src admin public -c phpstan.neon
