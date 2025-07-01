# Support Ticket Manager

![Sistema de Tickets](https://img.shields.io/badge/Sistema-Tickets%20de%20Suporte-blue)
![PHP](https://img.shields.io/badge/PHP-8.2-777BB4)
![MySQL](https://img.shields.io/badge/MySQL-Database-orange)
![Docker](https://img.shields.io/badge/Docker-Containerized-blue)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.x-purple)

## 📋 Descrição

O **Support Ticket Manager** é um sistema completo de gerenciamento de tickets de suporte desenvolvido em PHP. O sistema permite que empresas gerenciem solicitações de suporte de forma eficiente, com controle de usuários, empresas, clientes e serviços.

## ✨ Funcionalidades Principais

### 🎫 Gerenciamento de Tickets

-   **Criação de Tickets**: Abertura de tickets com assunto, descrição e categoria
-   **Status Dinâmico**: Controle de status (Aberto, Aguardando Suporte, Respondido, Fechado)
-   **Sistema de Mensagens**: Comunicação bidirecional entre cliente e suporte
-   **Histórico Completo**: Rastreamento de todas as interações
-   **Dashboard Intuitivo**: Visão geral com gráficos e estatísticas

### 👥 Gestão de Usuários e Permissões

-   **Múltiplos Grupos**: Sistema de permissões por grupos de usuários
-   **Controle de Acesso**: Diferentes níveis de acesso (Admin, Suporte, Cliente)
-   **Autenticação Segura**: Sistema de login

### 🏢 Gestão Empresarial

-   **Cadastro de Empresas**: Gerenciamento completo de empresas
-   **Vinculação de Clientes**: Associação de clientes às empresas
-   **Serviços por Empresa**: Categorização de serviços oferecidos
-   **Controle de Usuários**: Associação de usuários às empresas

### 📊 Relatórios e Dashboard

-   **Dashboard Interativo**: Gráficos de distribuição de tickets
-   **Estatísticas em Tempo Real**: Contadores de tickets por status
-   **Relatórios por Usuário**: Tickets por responsável
-   **Métricas de Desempenho**: Análise de serviços mais solicitados

## 🚀 Tecnologias Utilizadas

### Backend

-   **PHP 8.2**: Linguagem principal do sistema
-   **MySQL**: Banco de dados relacional
-   **PDO**: Abstração de banco de dados
-   **Session Management**: Controle de sessões

### Frontend

-   **Bootstrap 5**: Framework CSS responsivo
-   **jQuery**: Biblioteca JavaScript
-   **Bootstrap Icons**: Ícones do sistema
-   **Chart.js**: Gráficos interativos
-   **Select2**: Componente de seleção avançada
-   **Noty**: Sistema de notificações

### Infraestrutura

-   **Docker**: Containerização da aplicação
-   **Apache**: Servidor web
-   **EasyPanel**: Gerenciamento do servidor
-   **Digital Ocean**: Hospedagem VPS

## 🏗️ Arquitetura do Sistema

### Estrutura de Diretórios

```
Support-Ticket-Manager/
├── 📁 api/                    # Endpoints da API
│   └── logs.php               # Log de requisições
├── 📁 config/                 # Classes de configuração e modelos
│   ├── Cliente.php
│   ├── Database.php
│   ├── Empresa.php
│   └── ...
├── 📁 controller/             # Controladores da aplicação
│   ├── 📁 cliente/
│   │       ├── cadastrar.php
│   │       ├── editar.php
│   │       ├── excluir.php
│   │       └── ...
│   ├── 📁 empresa/
│   └── ...
├── 📁 css/                    # Estilos CSS
├── 📁 database/               # Scripts do banco de dados
├── 📁 js/                     # Scripts JavaScript
│   ├── 📁 Utils/
│   │   ├── Noty.js
│   │   ├── Select2.js
│   │   ├── Validador.js
│   │   └── ...
│   ├── cliente.js
│   ├── empresa.js
│   ├── suporte.js
│   └── ...
├── 📁 libs/                   # Bibliotecas externas
│   ├── 📁 Bootstrap/
│   ├── 📁 Chart.js/
│   ├── 📁 jQuery/
│   ├── 📁 Select2/
│   ├── 📁 Noty/
│   └── ...
├── 📁 logs/                   # Logs da aplicação
│   ├── 📁 2025-06-29_a_2025-07-05/
│   │   ├── all.json
│   │   ├── all.log
│   │   └── error.log
│   ├── 📁 2025-07-06_a_2025-07-12/
│   └── ...
├── 📁 pages/                  # Páginas da aplicação
│   ├── 📁 empresa/
│   │   ├── cadastro.php
│   │   ├── edicao.php
│   │   ├── index.php
│   │   └── ...
│   ├── 📁 suporte/
│   └── ...
├── 🐳 docker-compose.yml      # Configuração Docker
├── 🐳 Dockerfile              # Imagem Docker
├── 📄 index.php               # Página inicial
├── 📄 LICENSE.txt             # Licença do projeto
└── 📚 README.md               # Esta documentação
```

### Modelos de Dados

-   **Empresa**: Empresas do sistema
-   **Serviço**: Categorização de serviços
-   **Cliente**: Gestão de clientes (pessoa física/jurídica)
-   **Usuário**: Controle de usuários
-   **Grupos**: Controle de grupos de usuários
-   **Suporte**: Tickets de suporte
-   **SuporteMensagem**: Mensagens dos tickets

## 🌐 Ambientes de Desenvolvimento

### Branches

-   **main**: Código de produção estável
-   **desenvolvimento**: Versão em desenvolvimento
-   **producao**: Versão para deploy em produção

### Deploy

O sistema está rodando em uma VPS da Digital Ocean, gerenciado pelo EasyPanel, com:

-   **Container Docker**: Aplicação containerizada
-   **Banco de dados exclusivo**: MySQL dedicado
-   **SSL/HTTPS**: Certificado de segurança
-   **Backup automatizado**: Rotinas de backup

## 👥 Tipos de Usuário

### 1. Administrador (Grupo 1)

-   Acesso total ao sistema
-   Gestão de empresas, serviços, clientes, usuários e grupos
-   Visualização de todos os tickets
-   Relatórios

### 2. Cliente (Grupo 2)

-   Abertura de tickets de suporte
-   Visualização dos próprios tickets
-   Comunicação com o suporte
-   Dashboard personalizado

### 3. Suporte (Grupos 3-...)

-   Atendimento de tickets
-   Comunicação com clientes
-   Fechamento de tickets
-   Acesso restrito de empresa, serviço e cliente
-   Relatórios

## 📱 Interface do Sistema

### Páginas Principais

-   **Login**: Autenticação com seleção de empresa
-   **Dashboard**: Visão geral com gráficos e estatísticas
-   **Tickets**: Listagem e gerenciamento de tickets
-   **Meus Tickets**: Tickets do usuário logado
-   **Cadastros**: Gestão de empresas, serviços, clientes, usuários e grupos

### Características da Interface

-   **Design Responsivo**: Compatível com dispositivos móveis
-   **Tema Moderno**: Interface clean e profissional
-   **Notificações**: Sistema de alertas e confirmações
-   **Navegação Intuitiva**: Sidebar colapsível e breadcrumb

## 🔒 Segurança

### Medidas Implementadas

-   **Prepared Statements**: Proteção contra SQL Injection
-   **Validação de Entrada**: Sanitização de dados
-   **Controle de Sessão**: Gerenciamento seguro de sessões
-   **Permissões**: Sistema de grupos e permissões
-   **HTTPS**: Conexão segura (em produção)

## 🛠️ Desenvolvimento

### Padrões de Código

-   **PSR-4**: Autoloading de classes
-   **Separação de Responsabilidades**: MVC pattern
-   **Validação**: Classes dedicadas para validação
-   **Documentação**: Comentários em métodos importantes

## 📊 Monitoramento

### Métricas Disponíveis

-   Total de tickets por status
-   Tickets por usuário responsável
-   Distribuição por serviços
-   Tempo de resposta médio
-   Tickets por empresa/cliente

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

## 📞 Suporte

Para suporte técnico ou dúvidas sobre o sistema:

-   **Email**: caioh.alvino22@gmail.com
-   **Horário**: Dias úteis, 8h às 18h
-   **Documentação**: Disponível no sistema

---

## 🎯 Próximas Funcionalidades

-   [ ] Integração com e-mail
-   [ ] Sistema de anexos
-   [ ] Notificações push
-   [ ] Integração com ferramentas externas

---

**Desenvolvido com ❤️ para otimizar o atendimento ao cliente**
