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
-   **Autenticação Segura**: Sistema de login com validação de empresa

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
├── api/                    # Endpoints da API
├── assets/                 # Recursos estáticos
├── config/                 # Classes de configuração e modelos
├── controller/             # Controladores da aplicação
├── css/                    # Estilos CSS
├── database/               # Scripts do banco de dados
├── js/                     # Scripts JavaScript
├── libs/                   # Bibliotecas externas
├── pages/                  # Páginas da aplicação
├── docker-compose.yml      # Configuração Docker
├── Dockerfile             # Imagem Docker
└── index.php              # Página inicial
```

### Modelos de Dados

-   **Cliente**: Gestão de clientes (pessoa física/jurídica)
-   **Empresa**: Empresas do sistema
-   **Usuário**: Controle de usuários e permissões
-   **Suporte**: Tickets de suporte
-   **SuporteMensagem**: Mensagens dos tickets
-   **Serviço**: Categorização de serviços

## 🐳 Instalação e Configuração

### Pré-requisitos

-   Docker e Docker Compose
-   PHP 8.2 ou superior (para desenvolvimento local)
-   MySQL 5.7 ou superior

### Configuração com Docker

1. **Clone o repositório**

    ```bash
    git clone https://github.com/seu-usuario/Support-Ticket-Manager.git
    cd Support-Ticket-Manager
    ```

2. **Configure as variáveis de ambiente**
   Edite o arquivo `config/Database.php` com suas credenciais:

    ```php
    private $host = "seu-host-mysql";
    private $dbName = "support-ticket-manager";
    private $username = "seu-usuario";
    private $password = "sua-senha";
    ```

3. **Execute o Docker Compose**

    ```bash
    docker-compose up -d
    ```

4. **Importe o banco de dados**

    ```bash
    # Acesse o container MySQL e importe o arquivo SQL
    mysql -u usuario -p support-ticket-manager < database/support_ticket_manager.sql
    ```

5. **Acesse o sistema**
   Abra o navegador em `http://localhost:8080`

### Configuração Manual

1. **Configure o servidor web** (Apache/Nginx)
2. **Configure o PHP** (versão 8.2+)
3. **Configure o MySQL** e importe o arquivo `database/support_ticket_manager.sql`
4. **Configure as credenciais** no arquivo `config/Database.php`

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
-   Gestão de usuários, empresas e clientes
-   Visualização de todos os tickets
-   Relatórios completos

### 2. Cliente (Grupo 2)

-   Abertura de tickets de suporte
-   Visualização dos próprios tickets
-   Comunicação com o suporte
-   Dashboard personalizado

### 3. Suporte (Grupos 3-6)

-   Atendimento de tickets
-   Comunicação com clientes
-   Fechamento de tickets
-   Acesso restrito por empresa

## 📱 Interface do Sistema

### Páginas Principais

-   **Login**: Autenticação com seleção de empresa
-   **Dashboard**: Visão geral com gráficos e estatísticas
-   **Tickets**: Listagem e gerenciamento de tickets
-   **Meus Tickets**: Tickets do usuário logado
-   **Cadastros**: Gestão de empresas, clientes e usuários

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

### Estrutura de Desenvolvimento

```bash
# Iniciar ambiente de desenvolvimento
docker-compose up -d

# Acessar logs
docker-compose logs -f

# Parar ambiente
docker-compose down
```

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

## 🤝 Contribuição

### Para Contribuir

1. Faça um fork do projeto
2. Crie uma branch para sua feature (`git checkout -b feature/nova-funcionalidade`)
3. Commit suas alterações (`git commit -m 'Adiciona nova funcionalidade'`)
4. Push para a branch (`git push origin feature/nova-funcionalidade`)
5. Abra um Pull Request

### Padrões de Commit

-   `feat:` Nova funcionalidade
-   `fix:` Correção de bug
-   `docs:` Documentação
-   `style:` Formatação
-   `refactor:` Refatoração
-   `test:` Testes

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
