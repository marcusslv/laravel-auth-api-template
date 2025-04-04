# 🛠️ Laravel Auth API Template

> Template base para projetos Laravel com autenticação via API (Sanctum), RBAC com Spatie Laravel Permission e logging de acesso.

---

## 🚀 Visão Geral

Este repositório serve como ponto de partida para aplicações Laravel que exigem:

- API autenticada via token com Laravel Sanctum
- Gerenciamento de usuários, roles e permissões (RBAC)
- Logs de acesso (login, logout, falhas)
- Arquitetura limpa e modular para projetos escaláveis

---

## 🧱 Stack Utilizada

- **Laravel** 11.x
- **PHP** ^8.2
- **Laravel Sanctum** (autenticação via token)
- **Spatie Laravel Permission** (controle de acesso RBAC)
- **MySQL/PostgreSQL** (compatível)
- **Pest/PHPUnit** (testes)

---

## 🔐 Funcionalidades

✅ Autenticação via token (login/logout)

✅ CRUD de usuários via API

✅ Controle de acesso por roles/permissões

✅ Middleware para autorização

✅ Registro de logs de login/logout/falhas

✅ Seeders automáticos de roles, permissões e admin

---

## 📁 Estrutura das Tabelas

[Diagrama ER - Base](https://github.com/marcusslv/laravel-auth-api-template/blob/main/docs/Diagrama-ER.md)

---

## 📦 Instalação

```bash
git clone https://github.com/seu-usuario/laravel-auth-api-template.git
cd laravel-auth-api-template

composer install
cp .env.example .env
php artisan key:generate

php artisan migrate --seed
php artisan serve
```
