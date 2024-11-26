# **Sistema de Autenticação e Comentários - Laravel** 🚀

Bem-vindo ao repositório do **Sistema de Autenticação e Comentários**, desenvolvido utilizando o **Laravel**.  
Este projeto foi projetado para oferecer uma solução robusta para autenticação de usuários e gerenciamento de comentários com uma API segura, organizada e expansível.

---

## **✨ Funcionalidades**

### 🔐 **Sistema de Autenticação**
- **Registro de Usuários**:  
  Permite que novos usuários se registrem, com:
  - Validação de campos obrigatórios.
  - Senhas armazenadas de forma segura com `Hash::make`.
  - Prevenção de duplicidade de e-mails.

- **Login de Usuários**:  
  - Autenticação via e-mail e senha.
  - Geração de tokens de autenticação usando o Laravel Sanctum.
  - Retorno de mensagens apropriadas em caso de erro.

- **Logout de Usuários**:  
  - Invalidação do token, encerrando a sessão do usuário.

### 📝 **Sistema de Comentários**
- **CRUD de Comentários**:
  - **Criar**: Usuários autenticados podem adicionar novos comentários.
  - **Ler**: Comentários disponíveis publicamente ou filtrados por autor.
  - **Editar**: Apenas o autor ou administrador pode editá-lo.
  - **Excluir**: Apenas o autor ou admininistrador pode deletar seus comentários.

- **Validação e Segurança**:
  - Validação de entradas, garantindo dados consistentes.
  - Middleware protege as rotas, limitando o acesso a usuários autenticados.

---

## **🛠️ Estrutura do Banco de Dados**
O projeto utiliza **migrations** para criar e configurar as tabelas necessárias:

### **Tabela `users`**
| Campo       | Tipo       | Descrição                    |
|-------------|------------|------------------------------|
| `id`        | Inteiro    | Identificador único          |
| `name`      | String     | Nome do usuário              |
| `email`     | String     | E-mail (único)              |
| `is_admin`  | Bool       |    Administrador            |
| `password`  | String     | Senha encriptada            |
| `created_at`| Timestamp  | Data de criação             |
| `updated_at`| Timestamp  | Data de última atualização  |

### **Tabela `comments`**
| Campo       | Tipo       | Descrição                    |
|-------------|------------|------------------------------|
| `id`        | Inteiro    | Identificador único          |
| `user_id`   | Inteiro    | Referência ao autor          |
| `content`   | Texto      | Conteúdo do comentário       |
| `created_at`| Timestamp  | Data de criação             |
| `updated_at`| Timestamp  | Data de última atualização  |


### **Tabela `comment_history`**
| Campo          | Tipo       | Descrição                              |
|-----------------|------------|----------------------------------------|
| `id`           | Inteiro    | Identificador único                    |
| `comment_id`   | Inteiro    | Referência ao comentário original      |
| `previous_content` | Texto  | Conteúdo anterior do comentário        |
| `edited_at`    | Timestamp  | Data e hora da edição                  |

---

## **🌟 Estrutura Tecnológica**
- **Framework**: Laravel - Base para desenvolvimento do back-end e APIs.
- **Autenticação**: Laravel Sanctum - Para geração de tokens e segurança do acesso.
- **Banco de Dados**: MySQL - Para armazenar usuários, comentários e histórico.
- **Migrations**: Gerenciamento da estrutura do banco de dados.
- **Middleware**: Proteção de rotas e autenticação.

---

## **🛑 Limitações do Projeto**
Embora o projeto implemente a maior parte das funcionalidades planejadas, **não foi possível configurar o PHPUnit** devido a erros persistentes em bibliotecas relacionadas.  
Os problemas encontrados impediam a execução adequada dos testes, e optou-se por focar na estabilidade das APIs. Essa limitação será revisada em versões futuras.

---

## **🎯 Próximos Passos**
- Resolver as inconsistências na configuração do PHPUnit.
- Adicionar testes automatizados para validação das funcionalidades existentes.
- Implementar paginação nos endpoints para melhorar a escalabilidade.
- Criar endpoints adicionais para funcionalidades futuras, como respostas a comentários.

---

## **🧾 Licença**
Este projeto está licenciado sob a **MIT License**. Consulte o arquivo `LICENSE` para mais informações.

---

## **📬 Contato**
Caso tenha dúvidas ou sugestões, entre em contato:
- **E-mail**: almeidamyrelalima@gmail.com
- **LinkedIn**:https://www.linkedin.com/in/myrela-almeida-ab3852225/

---

Obrigada por conferir este projeto! 😊  
Desenvolvido com ❤️ e dedicação.
