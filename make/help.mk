MKFILES := Makefile make/docker.mk make/ldap.mk make/jwt.mk make/help.mk make/selfcare.mk

help: ## Affiche aide
	@echo
	@echo "📦 Commandes disponibles :"
	@echo
	@grep -h -E '^[a-zA-Z0-9_-]+:.*?## \[.*\]' $(MKFILES) | \
	sed -E 's/^([a-zA-Z0-9_-]+):.*## \[([^]]+)\] (.*)/\2|\1|\3/' | \
	sort | \
	awk -F'|' 'BEGIN {cat=""} \
	{ if ($$1 != cat) {cat=$$1; print ""; print "🔷 " cat} \
	  printf "  %-20s %s\n", $$2, $$3 }'
	@echo
