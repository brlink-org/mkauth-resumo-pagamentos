# README - Resumo de Pagamentos

Addon para o Sistema Mk-Auth que gera resumos de pagamentos diários e envia para o WhatsApp, facilitando o acompanhamento diário das transações de clientes.

## Estrutura de Arquivos
O Addon é composto pelos seguintes arquivos:

- `index.php`: Página inicial que inclui o cabeçalho e rodapé padrão do Mk-Auth e chama o arquivo resumo_pagamento.php gerar e exibir o resumo dos pagamentos.
- `resumo_pagamento.php`: Script principal que gera o resumo dos pagamentos filtrados por data, exibindo-os na interface do Mk-Auth. Também pode enviar os resumos via WhatsApp.
- `enviar_whatsapp.php`: Script responsável por enviar as mensagens de resumo para o WhatsApp, usando a API Evolution v2.
- `manifest.json`: Arquivo de manifesto do addon, contendo informações sobre o nome, versão e autor do addon.
- `addon.js`: Arquivo JavaScript que adiciona o link do addon no menu de clientes do Mk-Auth.

## Instruções de Uso
1. Configuração do Ambiente:
   - Certifique-se de que o Mk-Auth esteja instalado e configurado em seu servidor.
   - Faça upload dos arquivos do addon para o diretório `/opt/mk-auth/admin/addons/` dentro do sistema Mk-Auth.

2. Personalização do Código:
   - No arquivo `resumo_pagamento.php`, as configurações de conexão com o banco de dados do Mk-Auth já estão definidas (host, usuário, senha e banco de dados).
   - Personalize a URL, Token, número do grupo ou WhatsApp na API Evolution v2 no arquivo `enviar_whatsapp.php` para que as mensagens possam ser enviadas para o WhatsApp.

3. Acessando o Sistema:
   - Após configurar o ambiente e personalizar o código, acesse o sistema Mk-Auth.
   - No menu de clientes do Mk-Auth, você verá o link para o addon "Resumo Pagamentos".
   - Clique no link para abrir o formulário onde é possível selecionar uma data para consultar os pagamentos daquele dia.

4. Geração e Envio de Resumos:
   - Selecione uma data no formulário e clique em "Consultar pagamentos". O sistema exibirá o resumo dos pagamentos agrupados por forma de pagamento (PIX, Dinheiro, Bolemix, etc.), com subtotais e um total geral.
   - Na tela de resumo, você terá a opção de enviar o resumo por WhatsApp clicando no botão "Enviar via WhatsApp".
   - O envio de mensagens é feito através da API Evolution v2 configurada previamente.

## Observações

- Este projeto é uma extensão (Addon) para o Sistema Mk-Auth e requer conhecimento básico sobre o funcionamento do Mk-Auth e integração com APIs de terceiros (como a API Evolution v2).
- Verifique cuidadosamente as permissões de segurança no servidor para garantir que chaves de API e informações sensíveis estejam protegidas.

**Nota:** Importante: O envio de mensagens pelo WhatsApp requer uma conta e configuração correta da API. Certifique-se de usar um número de telefone válido e obter o token de autenticação da sua EvolutionAPI.

## Requisitos

- Mk-Auth instalado e configurado.
- Acesso ao banco de dados mkradius para consultar as transações financeiras.
- Conta e configuração da API Evolution v2 para envio de mensagens via WhatsApp.
