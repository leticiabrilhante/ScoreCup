SCORECUP - ENTREGA CORRIGIDA

O que foi corrigido:
1. CRUD de Jogos finalizado:
   - jogos_listar.php agora lista os jogos.
   - jogos_editar.php foi movido para a raiz do projeto e edita time A, time B, data/hora e status.
   - jogos_excluir.php foi movido para a raiz do projeto e exclui jogos pelo botão da listagem.

2. CSS do dashboard corrigido:
   - Removido CSS quebrado.
   - Criado visual novo para cards, ranking, próximos jogos e botões.

3. PDO revisado:
   - db.php configurado com PDO, utf8mb4, ERRMODE_EXCEPTION, FETCH_ASSOC e prepare/execute nas páginas principais.
   - Login, usuários e jogos usando PDO.

4. Caminhos corrigidos:
   - Arquivos PHP removidos da pasta css.
   - Includes usando require_once __DIR__ . '/db.php'.
   - Bootstrap local usado por padrão em css/bootstrap.min.css.
   - Caminhos de imagens, CSS e ações dos formulários revisados.

5. Banco exportado:
   - Arquivo scorecup.sql incluído na raiz do projeto.
   - Banco: scorecup.
   - Tabelas: users, jogos e palpites.

Como importar o banco no phpMyAdmin:
1. Abra http://localhost/phpmyadmin
2. Clique em Importar.
3. Selecione o arquivo scorecup.sql.
4. Clique em Executar.

Credenciais de teste:
E-mail: admin@scorecup.com
Senha: 123456

Como testar:
1. Coloque a pasta scorecup dentro de htdocs.
2. Importe o scorecup.sql no phpMyAdmin.
3. Acesse http://localhost/scorecup/login.php
4. Faça login com admin@scorecup.com / 123456.
5. Entre em Gerenciar Jogos.
6. Teste cadastrar, editar e excluir um jogo.
7. Volte ao dashboard e veja se os cards, próximos jogos e ranking aparecem


### 👥 Equipe Desenvolvedora
* Ana Eduarda
* Paulo Coutinho
* Daniel Freire
