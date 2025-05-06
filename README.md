# InovaLinks

Um projeto 100% brasileiro para gerenciar todos os seus links em um só lugar. InovaLinks é uma alternativa nacional ao Linktree, permitindo que você crie uma página personalizada com todos os seus links importantes.

![InovaLinks](/public/logo.png)

## 🌟 Funcionalidades

- Crie uma página personalizada com seus links importantes
- Customize sua página com avatar e cores
- Organize seus links facilmente com drag-and-drop
- Interface responsiva para desktop e mobile
- Métricas de cliques (em breve)

## 🔧 Tecnologias

- Laravel 10
- Vue.js 3 (Composition API)
- Inertia.js
- Tailwind CSS
- SQLite (configurável para MySQL/PostgreSQL)

## 📋 Requisitos

- PHP 8.1 ou superior
- Composer
- Node.js & NPM
- SQLite (ou MySQL/PostgreSQL)

## 🚀 Instalação

1. Clone o repositório:
   ```bash
   git clone https://github.com/seu-usuario/inovalinks.git
   cd inovalinks
   ```

2. Instale as dependências PHP:
   ```bash
   composer install
   ```

3. Instale as dependências JavaScript:
   ```bash
   npm install
   ```

4. Copie o arquivo de ambiente:
   ```bash
   cp .env.example .env
   ```

5. Gere a chave da aplicação:
   ```bash
   php artisan key:generate
   ```

6. Crie o banco de dados SQLite:
   ```bash
   touch database/database.sqlite
   ```

7. Execute as migrações:
   ```bash
   php artisan migrate
   ```

8. Inicie o servidor de desenvolvimento:
   ```bash
   npm run dev
   ```
   Em outro terminal:
   ```bash
   php artisan serve
   ```

9. Acesse [http://localhost:8000](http://localhost:8000) no seu navegador

## 🖥️ Uso

1. Registre-se na plataforma
2. Faça login e acesse o dashboard
3. Adicione seu avatar e personalize seu perfil
4. Comece a adicionar seus links
5. Compartilhe sua URL personalizada com seus seguidores

## 🤝 Contribuindo

Contribuições são bem-vindas! Sinta-se à vontade para abrir issues e pull requests.

1. Fork este repositório
2. Crie sua branch de funcionalidade (`git checkout -b feature/nova-funcionalidade`)
3. Commit suas mudanças (`git commit -m 'Adiciona nova funcionalidade'`)
4. Push para a branch (`git push origin feature/nova-funcionalidade`)
5. Abra um Pull Request

## 📄 Licença

Este projeto está licenciado sob a licença MIT - veja o arquivo [LICENSE.md](LICENSE.md) para detalhes.

## 📞 Contato

Para feedbacks, sugestões ou dúvidas:
- E-mail: [seu-email@exemplo.com](mailto:seu-email@exemplo.com)
- Twitter: [@seu_usuario](https://twitter.com/seu_usuario)

## Configuração Docker

Este projeto está configurado para rodar com Docker. Siga os passos abaixo para configurar o ambiente de desenvolvimento:

### Pré-requisitos
- Docker
- Docker Compose

### Configuração de desenvolvimento
1. Clone o repositório
2. Execute o build dos containers Docker:
   ```bash
   docker-compose build
   ```
3. Inicie os containers:
   ```bash
   docker-compose up -d
   ```
4. Acesse o aplicativo em `http://localhost:8000`
5. O frontend de desenvolvimento estará disponível em `http://localhost:5173`

### Comandos úteis
- Para acessar o container principal:
  ```bash
  docker-compose exec app bash
  ```
- Para parar os containers:
  ```bash
  docker-compose down
  ```
- Para rodar migrações:
  ```bash
  docker-compose exec app php artisan migrate
  ```

## Configuração no Railway

Para que o projeto funcione corretamente no Railway, siga estas etapas:

1. Conecte seu repositório ao Railway.

2. O projeto inclui um arquivo `.railway.toml` que automatiza a maior parte do deploy.

3. Configure as seguintes variáveis de ambiente no Railway:
   - `APP_ENV=production`
   - `APP_KEY=` (Railway vai gerar automaticamente)
   - `APP_DEBUG=false`
   - `APP_URL=` (URL fornecida pelo Railway)
   - `DB_CONNECTION=sqlite`
   - `SESSION_DRIVER=database`
   - `CACHE_DRIVER=database`
   - `QUEUE_CONNECTION=database`

4. O Railway usará o Dockerfile para construir a imagem e rodar o projeto.

5. As migrações serão executadas automaticamente durante o deploy.

## Solução de problemas

Se encontrar erros, verifique os logs do Railway para identificar os problemas específicos.

---
Feito com 💚💛 no Brasil 