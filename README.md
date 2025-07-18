## ✅ Sonar Metric
[![Quality Gate Status](https://sonar-php.pulse.mg/api/project_badges/measure?project=SelfcareKM-back&metric=alert_status&token=ea073f7c49fa2ae4d77f5b81d48159e2f15eacf1)](https://sonar-php.pulse.mg/dashboard?id=SelfcareKM-back)
[![Lines of Code](https://sonar-php.pulse.mg/api/project_badges/measure?project=SelfcareKM-back&metric=ncloc&token=ea073f7c49fa2ae4d77f5b81d48159e2f15eacf1)](https://sonar-php.pulse.mg/dashboard?id=SelfcareKM-back)
[![Security Rating](https://sonar-php.pulse.mg/api/project_badges/measure?project=SelfcareKM-back&metric=security_rating&token=ea073f7c49fa2ae4d77f5b81d48159e2f15eacf1)](https://sonar-php.pulse.mg/dashboard?id=SelfcareKM-back)
[![Maintainability Rating](https://sonar-php.pulse.mg/api/project_badges/measure?project=SelfcareKM-back&metric=sqale_rating&token=ea073f7c49fa2ae4d77f5b81d48159e2f15eacf1)](https://sonar-php.pulse.mg/dashboard?id=SelfcareKM-back)
[![Reliability Rating](https://sonar-php.pulse.mg/api/project_badges/measure?project=SelfcareKM-back&metric=reliability_rating&token=ea073f7c49fa2ae4d77f5b81d48159e2f15eacf1)](https://sonar-php.pulse.mg/dashboard?id=SelfcareKM-back)
[![Vulnerabilities](https://sonar-php.pulse.mg/api/project_badges/measure?project=SelfcareKM-back&metric=vulnerabilities&token=ea073f7c49fa2ae4d77f5b81d48159e2f15eacf1)](https://sonar-php.pulse.mg/dashboard?id=SelfcareKM-back)
[![Bugs](https://sonar-php.pulse.mg/api/project_badges/measure?project=SelfcareKM-back&metric=bugs&token=ea073f7c49fa2ae4d77f5b81d48159e2f15eacf1)](https://sonar-php.pulse.mg/dashboard?id=SelfcareKM-back)
[![Code Smells](https://sonar-php.pulse.mg/api/project_badges/measure?project=SelfcareKM-back&metric=code_smells&token=ea073f7c49fa2ae4d77f5b81d48159e2f15eacf1)](https://sonar-php.pulse.mg/dashboard?id=SelfcareKM-back)
[![Duplicated Lines (%)](https://sonar-php.pulse.mg/api/project_badges/measure?project=SelfcareKM-back&metric=duplicated_lines_density&token=ea073f7c49fa2ae4d77f5b81d48159e2f15eacf1)](https://sonar-php.pulse.mg/dashboard?id=SelfcareKM-back)
[![Coverage](https://sonar-php.pulse.mg/api/project_badges/measure?project=SelfcareKM-back&metric=coverage&token=ea073f7c49fa2ae4d77f5b81d48159e2f15eacf1)](https://sonar-php.pulse.mg/dashboard?id=SelfcareKM-back)
[![Technical Debt](https://sonar-php.pulse.mg/api/project_badges/measure?project=SelfcareKM-back&metric=sqale_index&token=ea073f7c49fa2ae4d77f5b81d48159e2f15eacf1)](https://sonar-php.pulse.mg/dashboard?id=SelfcareKM-back)

---

# SELFCARE COMORES

Selfcare B2B KM is a management application dedicated to the supervision and administration of mobile telephony offers for professional fleets. It enables users to autonomously manage (selfcare) subscriptions, usage, and options related to SMS and voice services, while providing a clear interface for management teams.

## Installation

Requirements (lts version recommended): 

- php 8.4 or higher (don't forget to upgrade your composer to latest version : 2.8 or higher)
- mariadb 11.4 or higher
- docker & docker compose (optional : include phpMyadmin, adminer, mariadb)

1. Step 1 : 
 Clone project into your local using this url :  https://github.com/AxianGroupSF/selfcareKM-back.git

2. Step 2 : 
 Inside project folder make cli : 
 - touch .env
 - touch .env.local (contact an administrator for db config and jwt credentials)
 - composer dump-env local
 - composer i

## Usage

To launch db admin and mariadb if u use docker run command  : 
docker compose up -d

Generate keypair for lexik auth :
php bin/console lexik:jwt:generate-keypair

Update database schema :
php bin/console d:s:u --force

Clear symfony cache:
php bin/console cache:clear

Launch a server symfony : 
symfony serve



# 🛠 Project Automation – Makefile Commands

This project uses a **modular Makefile system** to simplify common development and operational tasks such as managing Docker containers, JWT key generation, and LDAP user operations.

Each group of commands is defined in its own `.mk` file under the `make/` directory.

---

## 📖 Getting Started

To view all available commands, simply run:

```bash
make help
```

You will see a list like this, grouped by category:

```
📦 Available commands:

🔷 Docker
  docker-down          Stop the LDAP containers (docker-compose down)
  docker-logs          Show logs of the LDAP container
  docker-up            Start the LDAP containers (docker-compose up -d)

🔷 JWT
  jwt-generate         Generate JWT keys into $(JWT_DIR)
  jwt-reset            Delete and regenerate JWT keys

🔷 LDAP
  ldap-add-user        Add a user via LDIF (default: add-user.ldif)
  ldap-list-users      List LDAP users (inetOrgPerson)
```

---

## 🐳 Docker Commands

| Command             | Description                                      |
|---------------------|--------------------------------------------------|
| `make docker-up`    | Start the LDAP Docker containers                 |
| `make docker-down`  | Stop the LDAP Docker containers                  |
| `make docker-logs`  | View logs from the LDAP container                |

> ⚠️ The `docker-compose.yml` file is located at the root of the project, even though the commands live in `make/docker.mk`.

---

## 🔐 JWT Commands

| Command              | Description                                     |
|----------------------|-------------------------------------------------|
| `make jwt-generate`  | Generate a new JWT key pair                     |
| `make jwt-reset`     | Delete and regenerate the JWT keys              |

> 🗝️ Keys are stored in the folder defined by `JWT_DIR` (configured in your `.env` file).

---

## 📇 LDAP Commands

| Command                                | Description                                                        |
|----------------------------------------|--------------------------------------------------------------------|
| `make ldap-add-user`                   | Add a user from the default LDIF file (`add-user.ldif`)            |
| `make ldap-add-user file=custom.ldif`  | Add a user from a specific LDIF file                               |
| `make ldap-list-users`                 | List LDAP users with object class `inetOrgPerson`                  |

> 🔐 LDAP connection requires environment variables defined in your `.env` file (see below).

---

## ⚙️ Environment Variables

The following variables are required and should be defined in your `.env` or `.env.local` file:

```env
# LDAP configuration

# JWT configuration
JWT_DIR=config/jwt

# DATABASE
DATABASE_URL="mysql://root:root@127.0.0.1:3307/selfcareKM"

# MESSENGER
MESSENGER_TRANSPORT_DSN=doctrine://default?auto_setup=0

# LDAP configuration
LDAP_BASE_DN="dc=pulse,dc=local"
LDAP_SEARCH_DN="cn=admin,dc=pulse,dc=local"
LDAP_SEARCH_PASSWORD="admin"
LDAP_HOST=127.0.0.1
LDAP_PORT=389

# JWT configuration
JWT_SECRET_KEY="%kernel.project_dir%/config/jwt/private.pem"
JWT_PUBLIC_KEY="%kernel.project_dir%/config/jwt/public.pem"
JWT_PASSPHRASE=sdfjksfjlkfdsjkl

# CORS
CORS_ALLOW_ORIGIN="localhost:3000"

```

---

## 📁 Make Project Structure

```
.
├── Makefile
├── docker-compose.yml
├── .env
├── make/
│   ├── help.mk
│   ├── docker.mk
│   ├── jwt.mk
│   ├── ldap.mk
│   └── ldif/
│       └── add-user.ldif
|       └── add-new-user.ldif
```

---

## 🧪 Usage Examples

Add a user using the default LDIF file:

```bash
make ldap-add-user
```

Add a user using a custom LDIF file:

```bash
make ldap-add-user file=add-new-user.ldif
```

Generate JWT keys:

```bash
make jwt-generate
```

Restart LDAP containers:

```bash
make docker-down
make docker-up
```

---

## ✅ Tips

- All `.mk` files must follow the format:
  ```make
  command-name: ## [Category] Description here
  ```
- These lines will be automatically parsed and shown in `make help`.
- You can easily extend this setup with new categories (e.g., `db.mk`, `assets.mk`).

---
