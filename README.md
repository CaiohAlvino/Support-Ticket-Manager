# Support Ticket Manager

## Descrição
Sistema de gerenciamento de chamados (tickets) desenvolvido em PHP puro, sem frameworks, para centralizar e agilizar o suporte ao cliente. Permite:
- Abertura e acompanhamento de tickets  
- Atualização de status (Novo, Em Atendimento, Resolvido etc.)  
- Atribuição de tickets a agentes de suporte  
- Histórico completo de comentários  
- Notificações em tempo real  

## Tecnologias
- **Axios**: requisições AJAX no front-end  
- **Bootstrap**: layout responsivo e componentes de interface  
- **jQuery**: manipulação do DOM e eventos  
- **Noty**: alertas e notificações customizadas  
- **Select2**: campos `<select>` com busca e múltipla seleção  

## Pré‑requisitos
- PHP ≥ 7.4  
- MySQL ou MariaDB  
- Servidor web (Apache, Nginx ou built‑in do PHP)  

## Instalação

1. Clone o repositório  
   git clone https://github.com/seu-usuario/support-ticket-manager.git  
   cd support-ticket-manager

2. Configure o banco de dados  
   - Crie o banco `support_tickets`  
   - Importe o script SQL em `database/schema.sql`

3. Ajuste as credenciais em `config/config.php`  
   ```php
   <?php
   return [
     'db_host' => 'localhost',
     'db_name' => 'support_tickets',
     'db_user' => 'root',
     'db_pass' => '',
   ];
   ```

## Uso

- Acesse http://localhost:8000  
- Crie uma conta de cliente ou suporte  
- Abra, atualize e atribua tickets pela interface  
- Receba notificações via Noty a cada ação realizada  

## Estrutura de Pastas

public/  
├─ css/           # Bootstrap e estilos personalizados  
├─ js/            # Axios, jQuery, Noty, Select2 e scripts  
└─ index.php      # Ponto de entrada  

src/               # Lógica de backend (classes, controllers)  
database/          # Scripts SQL  
└─ schema.sql  

config/            # Arquivo de configuração do DB  

## Contribuição
1. Faça um fork  
2. Crie uma branch: `git checkout -b feature/nome-da-feature`  
3. Faça commit das suas alterações: `git commit -m "Descrição da feature"`  
4. Envie para o repositório original: `git push origin feature/nome-da-feature`  
5. Abra um Pull Request  

## Licença
Este projeto está licenciado sob a [MIT License](LICENSE).
