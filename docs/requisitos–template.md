# 📄 Documento de Requisitos – Template Base Laravel com API, RBAC e Logs de Acesso

## 📘 Visão Geral
Este projeto tem como objetivo fornecer um template base em Laravel, voltado para aplicações que exigem autenticação via API, gerenciamento de usuários com controle de acesso baseado em papéis e permissões (RBAC), e registro de logs de acesso.

O template serve como ponto de partida reutilizável para novos projetos, garantindo segurança, organização e padronização desde o início do desenvolvimento.

---

## 🎯 Objetivos

- Implementar autenticação via API com Laravel Sanctum.
- Estruturar controle de acesso com roles e permissões via Spatie Laravel Permission.
- Incluir CRUD de usuários, roles e permissões.
- Registrar logs de login, logout e falhas de autenticação.
- Fornecer uma base escalável e reutilizável.

---

## 🧱 Stack Tecnológica

| Tecnologia                 | Versão Sugerida |
|---------------------------|------------------|
| Laravel                   | 11.x             |
| PHP                       | ^8.2             |
| Sanctum                   | ^4.0             |
| Spatie Laravel Permission | ^6.0             |
| MySQL ou PostgreSQL       | 8.x              |

---

## 📋 Requisitos Funcionais

| Código | Descrição                                                                |
|--------|--------------------------------------------------------------------------|
| RF01   | O sistema deve permitir autenticação via token com Laravel Sanctum.      |
| RF02   | O sistema deve registrar logs de login, logout e falhas de autenticação. |
| RF03   | O administrador deve poder gerenciar usuários via API.                   |
| RF04   | O sistema deve permitir o gerenciamento de roles.                        |
| RF05   | O sistema deve proteger rotas com base nas funções atribuídas.           |

---

## 👥 Histórias de Usuário

| Código | História                                                                                | Requisito Relacionado |
|--------|-----------------------------------------------------------------------------------------|------------------------|
| HU01   | Como usuário autenticado, desejo acessar recursos protegidos usando um token de acesso. | RF01                   |
| HU02   | Como administrador, desejo visualizar logs de login e logout para fins de auditoria.    | RF02                   |
| HU03   | Como administrador, desejo criar, atualizar e excluir usuários pela API.                | RF03                   |
| HU04   | Como administrador, desejo controlar o acesso aos recursos com base em roles.           | RF04, RF05             |

---

## ⚙️ Funcionalidades Incluídas

- Autenticação com Laravel Sanctum (login, logout, token)
- CRUD completo de usuários
- CRUD completo de roles (via Spatie)
- Proteção de rotas com middleware de roles
- Comando para setup dos dados iniciais: roles e super usuário
- Logs de acesso em banco (login/logout/falha)

---

## 🔐 Segurança e Middleware

- Middleware `auth:sanctum` para rotas protegidas
- Middleware para controle de acesso baseado em funções (`role`)
- Rate limiting e CORS configuráveis
- Proteção contra acesso não autorizado via middleware personalizado

---

## ⚙️ Seeders e Dados Iniciais

- **Roles**: `role_administrator`, `user_administrator`
- **Super Usuário** padrão com acesso total
- Seeders automatizados executáveis via `php artisan db:seed`

---

## 🔗 Referências Técnicas

- [Laravel Sanctum](https://laravel.com/docs/sanctum)
- [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission)
- [Laravel Logging](https://laravel.com/docs/logging)
