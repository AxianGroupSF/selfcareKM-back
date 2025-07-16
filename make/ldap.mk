## 📂 LDAP ================================

LDIF_DIR ?= make/ldif
LDAP_CONTAINER ?= ldap-server
LDAP_ADMIN_DN ?= cn=admin,dc=pulse,dc=local
LDAP_ADMIN_PASSWORD ?=admin
LDAP_BASE_DN ?= dc=pulse,dc=local

ldap-add-user: ## [LDAP] Ajouter un utilisateur via LDIF (ex: make ldap-add-user file=user.ldif)
	@file=$(file); \
	if [ -z "$$file" ]; then \
		file=add-user.ldif; \
	fi; \
	echo "-----------"; \
	echo "$$file"; \
	echo "-----------"; \
	docker cp $(LDIF_DIR)/$$file $(LDAP_CONTAINER):/$$file; \
	docker exec -it $(LDAP_CONTAINER) ldapadd -x \
		-D "$(LDAP_ADMIN_DN)" \
		-w "$(LDAP_ADMIN_PASSWORD)" \
		-f /$$file

ldap-list-users: ## [LDAP] Lister les utilisateurs LDAP (inetOrgPerson)
	docker exec -it $(LDAP_CONTAINER) ldapsearch -x \
		-D "$(LDAP_ADMIN_DN)" \
		-w "$(LDAP_ADMIN_PASSWORD)" \
		-b "$(LDAP_BASE_DN)" "(objectClass=inetOrgPerson)"
