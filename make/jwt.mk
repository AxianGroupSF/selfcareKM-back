## 🔐 JWT ================================

JWT_DIR ?= config/jwt

jwt-generate: ## [JWT] Generate JWT keys in $(JWT_DIR)
	php bin/console lexik:jwt:generate-keypair --overwrite

jwt-reset: ## [JWT] Delete and regenerate JWT keys
	@echo "Deleting existing JWT keys..."
	rm -f $(JWT_DIR)/private.pem $(JWT_DIR)/public.pem
	$(MAKE) jwt-generate
