## 🗄️ Estrutura da Base de Dados

### 🔹 Tabela: `users`

| Campo              | Tipo           | Restrições                    |
|--------------------|----------------|-------------------------------|
| id                 | BIGINT         | PK, Auto Increment            |
| name               | VARCHAR(255)   | NOT NULL                      |
| email              | VARCHAR(255)   | UNIQUE, NOT NULL              |
| password           | VARCHAR(255)   | NOT NULL                      |
| email_verified_at  | TIMESTAMP      | NULLABLE                      |
| remember_token     | VARCHAR(100)   | NULLABLE                      |
| created_at         | TIMESTAMP      | Auto                          |
| updated_at         | TIMESTAMP      | Auto                          |

**Relacionamentos:**  
- Muitos-para-muitos com `roles` (via `model_has_roles`)  
- Um-para-muitos com `access_logs`

---

### 🔹 Tabela: `personal_access_tokens` (Sanctum)

| Campo          | Tipo        | Restrições                  |
|----------------|-------------|-----------------------------|
| id             | BIGINT      | PK, Auto Increment          |
| tokenable_id   | BIGINT      |                             |
| tokenable_type | STRING      |                             |
| name           | STRING      |                             |
| token          | TEXT        |                             |
| abilities      | TEXT        | NULLABLE                    |
| last_used_at   | TIMESTAMP   | NULLABLE                    |
| created_at     | TIMESTAMP   | Auto                        |
| updated_at     | TIMESTAMP   | Auto                        |

---

### 🔹 Tabela: `roles` (Spatie)

| Campo      | Tipo         | Restrições       |
|------------|--------------|------------------|
| id         | BIGINT       | PK               |
| name       | VARCHAR      | NOT NULL         |
| guard_name | VARCHAR      | DEFAULT: 'web'   |
| created_at | TIMESTAMP    | Auto             |
| updated_at | TIMESTAMP    | Auto             |

---

### 🔹 Tabela: `permissions` (Spatie)

| Campo      | Tipo         | Restrições       |
|------------|--------------|------------------|
| id         | BIGINT       | PK               |
| name       | VARCHAR      | NOT NULL         |
| guard_name | VARCHAR      | DEFAULT: 'web'   |
| created_at | TIMESTAMP    | Auto             |
| updated_at | TIMESTAMP    | Auto             |

---

### 🔹 Tabela: `model_has_roles` (Spatie)

| Campo        | Tipo     | Descrição                            |
|--------------|----------|---------------------------------------|
| role_id      | BIGINT   | FK para `roles.id`                    |
| model_type   | STRING   | Ex: App\Models\User                   |
| model_id     | BIGINT   | ID do model associado (ex: `users`)   |

---

### 🔹 Tabela: `model_has_permissions` (Spatie)

| Campo           | Tipo     | Descrição                            |
|------------------|----------|---------------------------------------|
| permission_id    | BIGINT   | FK para `permissions.id`              |
| model_type       | STRING   | Ex: App\Models\User                   |
| model_id         | BIGINT   | ID do model associado (ex: `users`)   |

---

### 🔹 Tabela: `role_has_permissions` (Spatie)

| Campo           | Tipo     | Descrição                            |
|------------------|----------|---------------------------------------|
| permission_id    | BIGINT   | FK para `permissions.id`              |
| role_id          | BIGINT   | FK para `roles.id`                    |

---

### 🔹 Tabela: `access_logs`

| Campo       | Tipo         | Restrições / Descrição                            |
|-------------|--------------|---------------------------------------------------|
| id          | BIGINT       | PK                                                |
| user_id     | BIGINT       | FK para `users.id` (nullable para falhas)         |
| event       | ENUM         | 'login', 'logout', 'failed_login'                 |
| ip_address  | VARCHAR(45)  | IP de origem                                      |
| user_agent  | TEXT         | Agente de navegação                               |
| created_at  | TIMESTAMP    | Data/hora do evento                               |

---

### 🔁 Relacionamentos Resumidos

| Entidade         | Relacionamento             | Entidade Relacionada      |
|------------------|----------------------------|----------------------------|
| `users`          | muitos-para-muitos         | `roles` via `model_has_roles` |
| `users`          | muitos-para-muitos         | `permissions` via `model_has_permissions` |
| `roles`          | muitos-para-muitos         | `permissions` via `role_has_permissions` |
| `users`          | um-para-muitos             | `access_logs`             |

