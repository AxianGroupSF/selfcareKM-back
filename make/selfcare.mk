## 🔧 SELFCARE ===============================

selfcare-user-add: ## [Selfcare] Add a user via Selfcare (Usage: make selfcare-user-add <login> <email> <phone> <password>)
	@login=$(word 2, $(MAKECMDGOALS)); \
	email=$(word 3, $(MAKECMDGOALS)); \
	phone=$(word 4, $(MAKECMDGOALS)); \
	password=$(word 5, $(MAKECMDGOALS)); \
	if [ -z "$$login" ] || [ -z "$$email" ] || [ -z "$$phone" ] || [ -z "$$password" ]; then \
		echo "❌ Usage: make selfcare-user-add <login> <email> <phone> <password>"; \
		exit 1; \
	fi; \
	echo "👤 Creating user: $$login, $$email, $$phone"; \
	php bin/console selfcare:create:user $$login $$email $$phone $$password

selfcare-ldap-test: ## [Selfcare] Testing LDAP connection (Usage: make selfcare-ldap-test <name> <password>)
	@name=$(word 2, $(MAKECMDGOALS)); \
	password=$(word 3, $(MAKECMDGOALS)); \
	if [ -z "$$name" ] || [ -z "$$password" ]; then \
		echo "❌ Usage: selfcare-ldap-test <name> <password>"; \
		exit 1; \
	fi; \
	echo "👤 Testing connection: $$name"; \
	php bin/console selfcare:test:ldap $$name $$password

%:
	@: