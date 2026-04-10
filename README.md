# Roket CRM Automation Pro

Plugin WordPress de automação de marketing completo para gestão de leads, webinars, email (SMTP), WhatsApp e automações com múltiplos gatilhos.

---

## 📍 Localização no Repositório

O plugin está em:

```
wp-content/plugins/roket-crm-automation-pro/
```

---

## 🚀 Instalação

1. Certifique-se de que o WordPress está instalado e configurado.
2. O plugin já está no diretório `wp-content/plugins/roket-crm-automation-pro/`.
3. Acesse o painel WordPress → **Plugins → Plugins Instalados**.
4. Localize **Roket CRM Automation Pro** e clique em **Ativar**.
5. As tabelas do banco de dados são criadas automaticamente na ativação.
6. O menu **Roket CRM** aparecerá na barra lateral do administrador.

### Requisitos

| Componente    | Versão mínima |
|--------------|---------------|
| WordPress    | 5.8           |
| PHP          | 7.4           |
| MySQL        | 5.7           |

---

## ⚙️ Configuração

### Acessar Configurações

`wp-admin → Roket CRM → Configurações`

---

### 💬 WhatsApp

#### Opção 1 — Evolution API (instância própria)

1. Em **Provedor**, selecione **Evolution API**.
2. Preencha:
   - **URL Base da API**: endereço da sua instância Evolution (ex: `https://evolution.seudominio.com.br`).
   - **API Key (Global)**: chave global configurada na Evolution API.
   - **Nome da Instância**: nome único para a instância (ex: `roket-marketing`).
3. Clique em **Salvar Configurações**.
4. O plugin criará a instância automaticamente na Evolution API.
5. Um **QR Code** será exibido — escaneie com o WhatsApp para conectar.
6. Para reconectar, clique no botão **Atualizar QR Code**.

#### Opção 2 — WhatsApp Cloud API (Meta)

1. Em **Provedor**, selecione **WhatsApp Cloud API (Meta)**.
2. Preencha:
   - **Token de Acesso Permanente**: token gerado no Meta Business Suite.
   - **ID do Número de Telefone**: Phone Number ID da sua conta do WhatsApp Business.
3. Clique em **Salvar Configurações**.

---

### 📧 SMTP

1. Em **Configurações de Email (SMTP)**, preencha:
   - **Host SMTP**, **Porta**, **Criptografia** (TLS recomendado).
   - **Usuário** e **Senha** SMTP.
2. Clique em **Salvar Configurações**.
3. Use o botão **Enviar Email de Teste** para validar a configuração.

> Deixe o campo **Host SMTP** em branco para usar o `sendmail` padrão do WordPress.

---

## 📋 Funcionalidades

### Contatos / Listas / Tags

- CRUD completo de contatos com campos personalizados, UTM, score.
- Listas e tags com histórico de eventos.
- Importação via webhook.

### 🎥 Webinars

1. Acesse `Roket CRM → Webinars → Novo Webinar`.
2. Configure:
   - **Tipo de vídeo**: YouTube, Vimeo ou HTML5 (MP4).
   - **Oferta**: título, URL, botão e **tempos diferentes** para exibição ao vivo vs. replay.
3. Ao salvar, o plugin cria automaticamente 5 listas:
   - **INBOX** — entrada (mantida sempre vazia após roteamento).
   - **Assistiu Oferta** — viu a oferta ao vivo.
   - **Não Viu Oferta** — não viu a oferta ao vivo.
   - **Replay** — encaminhados para replay.
   - **Converteu** — clicou na oferta.
4. Uma **automação dedicada** é criada para o webinar.

#### Comportamento "INBOX sempre vazia"

Quando um contato entra na INBOX e o player dispara um evento (via REST API), o plugin:
1. Move o contato para a lista de destino correta.
2. Remove da INBOX (mantendo-a vazia para novos leads).

#### Endpoint do Player (REST)

```
POST /wp-json/wpla/v1/webinar/event
Content-Type: application/json

{
  "webinar_id": 5,
  "event":      "offer_shown",   // offer_shown | offer_clicked | offer_not_shown | replay_requested
  "contact_id": 12,              // opcional
  "email":      "user@email.com" // opcional (para resolver contato)
}
```

---

### ⚡ Automações

`Roket CRM → Automações`

**Gatilhos disponíveis:**

| Gatilho                         | Descrição                               |
|--------------------------------|----------------------------------------|
| `contact_created`              | Contato criado                         |
| `contact_updated`              | Contato atualizado                     |
| `tag_added`                    | Tag adicionada                         |
| `tag_removed`                  | Tag removida                           |
| `list_subscribed`              | Inscrito em lista                      |
| `form_submitted`               | Formulário enviado (Elementor)         |
| `webhook_received`             | Webhook inbound recebido               |
| `email_opened`                 | Email aberto                           |
| `link_clicked`                 | Link de email clicado                  |
| `webinar_inbox`                | Contato entrou na INBOX do webinar     |
| `webinar_assistiu_oferta`      | Viu a oferta ao vivo                   |
| `webinar_nao_viu_oferta`       | Não viu a oferta ao vivo               |
| `webinar_replay`               | Encaminhado para replay                |
| `webinar_converteu`            | Clicou na oferta                       |

**Ações disponíveis:**

| Ação               | Descrição                     |
|-------------------|------------------------------|
| `add_tag`          | Adicionar tag                |
| `remove_tag`       | Remover tag                  |
| `subscribe_list`   | Inscrever em lista           |
| `unsubscribe_list` | Remover de lista             |
| `route_webinar`    | Rotear no webinar            |
| `send_email`       | Enviar email (fila SMTP)     |
| `send_whatsapp`    | Enviar WhatsApp              |
| `update_field`     | Atualizar campo do contato   |
| `update_score`     | Incrementar lead score       |
| `webhook`          | Disparar webhook (POST)      |
| `delay`            | Aguardar (minutos/horas/dias)|

---

### 🔗 Webhook Inbound

Endpoint para receber leads/eventos de plataformas externas (Hotmart, Eduzz, Kiwify, etc.):

```
POST /wp-json/wpla/v1/webhook/inbound
Headers:
  X-API-Key: <sua-chave-api>
  Content-Type: application/json

Body:
{
  "email":      "lead@email.com",
  "first_name": "João",
  "last_name":  "Silva",
  "phone":      "+5511999999999",
  "tags":       ["hotmart", "comprou"],
  "lists":      [5, 12],
  "event":      "purchase_approved"
}
```

A chave API está em: `Roket CRM → Configurações → Chave API`.

---

### 💬 Variáveis para WhatsApp e Email

Use em mensagens e templates. Clique na variável no painel para copiar:

| Variável          | Descrição             |
|------------------|-----------------------|
| `{nome}`          | Nome completo         |
| `{primeiro_nome}` | Primeiro nome         |
| `{sobrenome}`     | Sobrenome             |
| `{email}`         | E-mail                |
| `{telefone}`      | Telefone              |
| `{empresa}`       | Empresa               |
| `{webinar_nome}`  | Nome do webinar       |
| `{webinar_link}`  | Link do webinar       |
| `{oferta_titulo}` | Título da oferta      |
| `{oferta_link}`   | Link da oferta        |
| `{lista}`         | Lista atual           |
| `{utm_source}`    | UTM Source            |
| `{utm_campaign}`  | UTM Campaign          |

---

### 🔌 Integração com Elementor

O plugin captura leads via **Elementor Forms** nativamente. Para mapear campos:

1. No formulário Elementor, adicione uma **ação**: `Roket CRM — Capturar Lead`.
2. Mapeie os campos do formulário para: email, nome, telefone, etc.
3. Configure a lista de destino e tags.

---

## 📊 Tabelas do Banco de Dados

| Tabela                        | Descrição                        |
|------------------------------|----------------------------------|
| `wp_wpla_contacts`           | Contatos                         |
| `wp_wpla_lists`              | Listas (inclui `webinar_id`)     |
| `wp_wpla_tags`               | Tags                             |
| `wp_wpla_contact_tags`       | Pivot contato ↔ tag              |
| `wp_wpla_contact_lists`      | Pivot contato ↔ lista            |
| `wp_wpla_automations`        | Automações                       |
| `wp_wpla_automation_steps`   | Passos de cada automação         |
| `wp_wpla_events`             | Log de eventos                   |
| `wp_wpla_message_queue`      | Fila de emails/WhatsApp          |
| `wp_wpla_automation_logs`    | Log de execução de automações    |
| `wp_wpla_webinars`           | Webinars                         |

---

## 🐛 Resolução de Problemas

### O QR Code não aparece
- Verifique se a URL, API Key e nome da instância estão corretos.
- Clique em **Atualizar QR Code**.
- Confirme que sua Evolution API está acessível publicamente.

### Emails não chegam
- Use o botão **Enviar Email de Teste** nas Configurações.
- Verifique host/porta/criptografia SMTP.
- Consulte os logs em `wp-content/debug.log` com `WP_DEBUG` ativado.

### Automação não dispara
- Confirme que a automação está com status **Ativo**.
- Verifique se o cron do WordPress está funcionando (`WP-Cron`).
- O cron processa a fila a cada minuto.

---

## 📝 Licença

GPL-2.0+  
© Dener Naresi — [denernaresi.com](https://denernaresi.com)
