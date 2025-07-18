## 🔧 DOCKER ===============================

docker-up: ## [Docker] Lancer les conteneurs LDAP (docker-compose up -d)
	docker compose up -d

docker-down: ## [Docker] Arrêter les conteneurs LDAP (docker-compose down)
	docker compose down

docker-logs: ## [Docker] Afficher les logs du conteneur LDAP
	docker logs -f $(LDAP_CONTAINER)
