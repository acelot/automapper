.PHONY: build-static-api
generate-static-api:
	composer run generate-static-api

.PHONY: fix-static-api
fix-static-api:
	composer run php-cs-fixer -- fix \
		--rules=@PSR12,no_unused_imports,ordered_imports,class_attributes_separation,blank_line_before_statement,-blank_line_after_opening_tag \
		src/AutoMapper.php

.PHONY: build
build: generate-static-api fix-static-api
