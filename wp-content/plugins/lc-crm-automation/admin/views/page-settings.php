<?php
/**
 * Settings page view.
 *
 * @package LC_CRM_Automation
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="wpla-settings-page">
    <form id="settings-form">

        <!-- WhatsApp Settings -->
        <div class="wpla-card wpla-mb-4">
            <h3>💬 <?php esc_html_e( 'Configurações WhatsApp', 'lc-crm' ); ?></h3>

            <div class="wpla-field">
                <label><?php esc_html_e( 'Provedor', 'lc-crm' ); ?></label>
                <select name="wpla_whatsapp_provider" id="wpla_whatsapp_provider" class="wpla-select">
                    <option value="evolution" <?php selected( get_option( 'wpla_whatsapp_provider' ), 'evolution' ); ?>><?php esc_html_e( 'Evolution API (instância própria)', 'lc-crm' ); ?></option>
                    <option value="meta" <?php selected( get_option( 'wpla_whatsapp_provider' ), 'meta' ); ?>><?php esc_html_e( 'WhatsApp Cloud API (Meta)', 'lc-crm' ); ?></option>
                </select>
            </div>

            <fieldset class="wpla-fieldset" id="evolution-settings">
                <legend><?php esc_html_e( 'Evolution API', 'lc-crm' ); ?></legend>
                <p class="wpla-text-muted wpla-mb-3"><?php esc_html_e( 'Preencha os campos abaixo e clique em "Salvar Configurações" para criar a instância automaticamente e exibir o QR Code de conexão.', 'lc-crm' ); ?></p>
                <div class="wpla-form-grid">
                    <div class="wpla-field">
                        <label><?php esc_html_e( 'URL Base da API', 'lc-crm' ); ?></label>
                        <input type="url" name="wpla_evolution_api_url" id="wpla_evolution_api_url" class="wpla-input" value="<?php echo esc_attr( get_option( 'wpla_evolution_api_url' ) ); ?>" placeholder="https://sua-evolution.com.br" />
                    </div>
                    <div class="wpla-field">
                        <label><?php esc_html_e( 'API Key (Global)', 'lc-crm' ); ?></label>
                        <input type="text" name="wpla_evolution_api_key" id="wpla_evolution_api_key" class="wpla-input" value="<?php echo esc_attr( get_option( 'wpla_evolution_api_key' ) ); ?>" placeholder="sua-api-key-global" />
                    </div>
                </div>
                <div class="wpla-field">
                    <label><?php esc_html_e( 'Nome da Instância', 'lc-crm' ); ?></label>
                    <input type="text" name="wpla_evolution_instance" id="wpla_evolution_instance" class="wpla-input" value="<?php echo esc_attr( get_option( 'wpla_evolution_instance' ) ); ?>" placeholder="minha-instancia" />
                    <p class="wpla-help-text"><?php esc_html_e( 'Use apenas letras, números e hífens. Ex: lc-marketing', 'lc-crm' ); ?></p>
                </div>

                <!-- QR Code section -->
                <div id="evolution-qr-section" class="wpla-mt-3" <?php if ( ! get_option( 'wpla_evolution_instance' ) ) echo 'style="display:none"'; ?>>
                    <div id="evolution-qr-wrapper" class="wpla-qr-wrapper">
                        <?php if ( get_option( 'wpla_evolution_qr_code' ) ) : ?>
                            <img src="<?php echo esc_attr( get_option( 'wpla_evolution_qr_code' ) ); ?>" alt="QR Code" class="wpla-qr-image" id="evolution-qr-img" />
                            <p class="wpla-help-text wpla-mt-2"><?php esc_html_e( 'Escaneie o QR Code com o WhatsApp para conectar a instância.', 'lc-crm' ); ?></p>
                        <?php else : ?>
                            <p class="wpla-text-muted" id="evolution-qr-placeholder"><?php esc_html_e( 'Salve as configurações para gerar o QR Code de conexão.', 'lc-crm' ); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="wpla-mt-2">
                        <button type="button" class="wpla-btn" id="btn-refresh-qr">🔄 <?php esc_html_e( 'Atualizar QR Code', 'lc-crm' ); ?></button>
                        <span id="evolution-status-badge" class="wpla-badge wpla-badge-info wpla-ml-2"></span>
                    </div>
                </div>
            </fieldset>

            <fieldset class="wpla-fieldset" id="meta-settings">
                <legend><?php esc_html_e( 'WhatsApp Cloud API (Meta)', 'lc-crm' ); ?></legend>
                <p class="wpla-text-muted wpla-mb-3"><?php esc_html_e( 'Configure seu token e número de telefone do WhatsApp Business via Meta.', 'lc-crm' ); ?></p>
                <div class="wpla-form-grid">
                    <div class="wpla-field">
                        <label><?php esc_html_e( 'Token de Acesso Permanente', 'lc-crm' ); ?></label>
                        <input type="text" name="wpla_meta_whatsapp_token" class="wpla-input" value="<?php echo esc_attr( get_option( 'wpla_meta_whatsapp_token' ) ); ?>" placeholder="EAAxxxxx..." />
                    </div>
                    <div class="wpla-field">
                        <label><?php esc_html_e( 'ID do Número de Telefone', 'lc-crm' ); ?></label>
                        <input type="text" name="wpla_meta_phone_number_id" class="wpla-input" value="<?php echo esc_attr( get_option( 'wpla_meta_phone_number_id' ) ); ?>" placeholder="1234567890" />
                    </div>
                </div>
            </fieldset>
        </div>

        <!-- SMTP Settings -->
        <div class="wpla-card wpla-mb-4">
            <h3>📧 <?php esc_html_e( 'Configurações de Email (SMTP)', 'lc-crm' ); ?></h3>
            <p class="wpla-text-muted wpla-mb-3"><?php esc_html_e( 'Configure o servidor SMTP para envio de emails confiável. Deixe em branco para usar o sendmail padrão do WordPress.', 'lc-crm' ); ?></p>

            <div class="wpla-form-grid">
                <div class="wpla-field">
                    <label><?php esc_html_e( 'Nome do Remetente', 'lc-crm' ); ?></label>
                    <input type="text" name="wpla_email_from_name" class="wpla-input" value="<?php echo esc_attr( get_option( 'wpla_email_from_name', get_bloginfo( 'name' ) ) ); ?>" />
                </div>
                <div class="wpla-field">
                    <label><?php esc_html_e( 'Email do Remetente', 'lc-crm' ); ?></label>
                    <input type="email" name="wpla_email_from_address" class="wpla-input" value="<?php echo esc_attr( get_option( 'wpla_email_from_address', get_bloginfo( 'admin_email' ) ) ); ?>" />
                </div>
            </div>

            <fieldset class="wpla-fieldset wpla-mt-3">
                <legend><?php esc_html_e( 'Servidor SMTP', 'lc-crm' ); ?></legend>
                <div class="wpla-form-grid">
                    <div class="wpla-field">
                        <label><?php esc_html_e( 'Host SMTP', 'lc-crm' ); ?></label>
                        <input type="text" name="wpla_smtp_host" class="wpla-input" value="<?php echo esc_attr( get_option( 'wpla_smtp_host' ) ); ?>" placeholder="smtp.seuservidor.com.br" />
                    </div>
                    <div class="wpla-field">
                        <label><?php esc_html_e( 'Porta SMTP', 'lc-crm' ); ?></label>
                        <input type="number" name="wpla_smtp_port" class="wpla-input" value="<?php echo esc_attr( get_option( 'wpla_smtp_port', '587' ) ); ?>" placeholder="587" />
                    </div>
                </div>
                <div class="wpla-form-grid">
                    <div class="wpla-field">
                        <label><?php esc_html_e( 'Criptografia', 'lc-crm' ); ?></label>
                        <select name="wpla_smtp_encryption" class="wpla-select">
                            <option value="" <?php selected( get_option( 'wpla_smtp_encryption' ), '' ); ?>><?php esc_html_e( 'Nenhuma', 'lc-crm' ); ?></option>
                            <option value="tls" <?php selected( get_option( 'wpla_smtp_encryption' ), 'tls' ); ?>>TLS (recomendado)</option>
                            <option value="ssl" <?php selected( get_option( 'wpla_smtp_encryption' ), 'ssl' ); ?>>SSL</option>
                        </select>
                    </div>
                    <div class="wpla-field">
                        <label><?php esc_html_e( 'Autenticação', 'lc-crm' ); ?></label>
                        <select name="wpla_smtp_auth" class="wpla-select">
                            <option value="1" <?php selected( get_option( 'wpla_smtp_auth', '1' ), '1' ); ?>><?php esc_html_e( 'Sim (recomendado)', 'lc-crm' ); ?></option>
                            <option value="0" <?php selected( get_option( 'wpla_smtp_auth', '1' ), '0' ); ?>><?php esc_html_e( 'Não', 'lc-crm' ); ?></option>
                        </select>
                    </div>
                </div>
                <div class="wpla-form-grid">
                    <div class="wpla-field">
                        <label><?php esc_html_e( 'Usuário SMTP', 'lc-crm' ); ?></label>
                        <input type="text" name="wpla_smtp_user" class="wpla-input" value="<?php echo esc_attr( get_option( 'wpla_smtp_user' ) ); ?>" placeholder="usuario@seudominio.com.br" autocomplete="off" />
                    </div>
                    <div class="wpla-field">
                        <label><?php esc_html_e( 'Senha SMTP', 'lc-crm' ); ?></label>
                        <input type="password" name="wpla_smtp_pass" class="wpla-input" value="<?php echo esc_attr( get_option( 'wpla_smtp_pass' ) ); ?>" autocomplete="new-password" />
                    </div>
                </div>
            </fieldset>

            <!-- Test SMTP -->
            <div class="wpla-mt-3">
                <h4><?php esc_html_e( 'Testar Envio de Email', 'lc-crm' ); ?></h4>
                <div class="wpla-form-grid wpla-mt-2">
                    <div class="wpla-field">
                        <input type="email" id="smtp-test-to" class="wpla-input" placeholder="<?php esc_attr_e( 'Enviar teste para...', 'lc-crm' ); ?>" value="<?php echo esc_attr( get_bloginfo( 'admin_email' ) ); ?>" />
                    </div>
                    <div class="wpla-field">
                        <button type="button" class="wpla-btn" id="btn-test-smtp"><?php esc_html_e( 'Enviar Email de Teste', 'lc-crm' ); ?></button>
                    </div>
                </div>
                <div id="smtp-test-result" class="wpla-mt-2"></div>
            </div>
        </div>

        <!-- API Key -->
        <div class="wpla-card wpla-mb-4">
            <h3>🔑 <?php esc_html_e( 'Chave API (Webhooks)', 'lc-crm' ); ?></h3>
            <p class="wpla-text-muted wpla-mb-2"><?php esc_html_e( 'Use esta chave para autenticar webhooks inbound de plataformas externas (Hotmart, Eduzz, Kiwify, etc).', 'lc-crm' ); ?></p>
            <div class="wpla-field">
                <label><?php esc_html_e( 'Chave API Atual', 'lc-crm' ); ?></label>
                <div class="wpla-input-group">
                    <input type="text" class="wpla-input" value="<?php echo esc_attr( get_option( 'wpla_api_key' ) ); ?>" readonly id="current-api-key" />
                    <button type="button" class="wpla-btn" onclick="WPLA.copyToClipboard(document.getElementById('current-api-key').value)"><?php esc_html_e( 'Copiar', 'lc-crm' ); ?></button>
                </div>
            </div>
            <p class="wpla-text-muted wpla-mt-1">
                <?php esc_html_e( 'Endpoint de webhook:', 'lc-crm' ); ?>
                <code><?php echo esc_html( rest_url( 'wpla/v1/webhook/inbound' ) ); ?></code>
            </p>
            <label class="wpla-checkbox wpla-mt-2">
                <input type="checkbox" name="regenerate_api_key" value="1" />
                <?php esc_html_e( 'Regenerar chave API ao salvar', 'lc-crm' ); ?>
            </label>
        </div>

        <button type="submit" class="wpla-btn wpla-btn-primary wpla-btn-lg" id="btn-save-settings">
            <?php esc_html_e( 'Salvar Configurações', 'lc-crm' ); ?>
        </button>
    </form>
</div>

<script>
(function () {
    document.addEventListener('DOMContentLoaded', function () {
        var providerSel = document.getElementById('wpla_whatsapp_provider');
        var evolutionFs = document.getElementById('evolution-settings');
        var metaFs      = document.getElementById('meta-settings');

        function toggleProvider() {
            var v = providerSel ? providerSel.value : 'evolution';
            if (evolutionFs) evolutionFs.style.display = v === 'evolution' ? '' : 'none';
            if (metaFs)      metaFs.style.display      = v === 'meta'      ? '' : 'none';
        }

        if (providerSel) {
            providerSel.addEventListener('change', toggleProvider);
            toggleProvider();
        }

        var btnRefreshQr = document.getElementById('btn-refresh-qr');
        if (btnRefreshQr) {
            btnRefreshQr.addEventListener('click', fetchEvolutionQr);
        }

        var btnTestSmtp = document.getElementById('btn-test-smtp');
        if (btnTestSmtp) {
            btnTestSmtp.addEventListener('click', function () {
                var to = document.getElementById('smtp-test-to').value.trim();
                if (!to) { alert('Informe o email de destino.'); return; }
                WPLA.ajax('wpla_send_test_email', {
                    to:      to,
                    subject: 'Teste SMTP — LC CRM',
                    body:    '<p>Email de teste enviado com sucesso via LC CRM!</p>',
                }, function (res) {
                    var el = document.getElementById('smtp-test-result');
                    if (res.success && res.data.sent) {
                        el.innerHTML = '<span class="wpla-badge wpla-badge-success">Email enviado com sucesso!</span>';
                    } else {
                        el.innerHTML = '<span class="wpla-badge wpla-badge-danger">Falha ao enviar. Verifique as configurações SMTP.</span>';
                    }
                });
            });
        }

        var settingsForm = document.getElementById('settings-form');
        if (settingsForm) {
            settingsForm.addEventListener('submit', function (e) {
                e.preventDefault();
                saveSettings();
            });
        }
    });

    function saveSettings() {
        var form     = document.getElementById('settings-form');
        var formData = new FormData(form);
        var data     = {};
        formData.forEach(function (val, key) { data[key] = val; });

        WPLA.ajax('wpla_save_settings', data, function (res) {
            if (res.success) {
                WPLA.showNotice('Configurações salvas!', 'success');
                if (res.data && res.data.api_key) {
                    var el = document.getElementById('current-api-key');
                    if (el) el.value = res.data.api_key;
                }
                var provider  = document.getElementById('wpla_whatsapp_provider');
                var apiUrl    = document.getElementById('wpla_evolution_api_url');
                var apiKey    = document.getElementById('wpla_evolution_api_key');
                var instanceF = document.getElementById('wpla_evolution_instance');
                if (provider && provider.value === 'evolution' && apiUrl && apiKey && instanceF) {
                    var u = apiUrl.value.trim();
                    var k = apiKey.value.trim();
                    var i = instanceF.value.trim();
                    if (u && k && i) {
                        createEvolutionInstance(u, k, i);
                    }
                }
            } else {
                WPLA.showNotice('Erro ao salvar configurações.', 'error');
            }
        });
    }

    function createEvolutionInstance(apiUrl, apiKey, instanceName) {
        WPLA.ajax('wpla_evolution_create_instance', {
            api_url:  apiUrl,
            api_key:  apiKey,
            instance: instanceName,
        }, function (res) {
            var section = document.getElementById('evolution-qr-section');
            if (section) section.style.display = '';
            if (res.success) {
                if (res.data && res.data.qr_code) {
                    updateQrImage(res.data.qr_code);
                } else {
                    fetchEvolutionQr();
                }
                setStatusBadge(res.data && res.data.status ? res.data.status : 'connecting');
            } else {
                fetchEvolutionQr();
            }
        });
    }

    function fetchEvolutionQr() {
        WPLA.ajax('wpla_evolution_get_qr', {}, function (res) {
            if (res.success && res.data && res.data.qr_code) {
                updateQrImage(res.data.qr_code);
                setStatusBadge(res.data.status || 'connecting');
            } else if (res.success) {
                setStatusBadge('open');
            } else {
                setStatusBadge('close');
            }
        });
    }

    function updateQrImage(src) {
        var wrapper = document.getElementById('evolution-qr-wrapper');
        if (!wrapper) return;
        var placeholder = document.getElementById('evolution-qr-placeholder');
        if (placeholder) placeholder.remove();
        var img = document.getElementById('evolution-qr-img');
        if (!img) {
            img = document.createElement('img');
            img.id = 'evolution-qr-img';
            img.className = 'wpla-qr-image';
            img.alt = 'QR Code';
            wrapper.appendChild(img);
            var hint = document.createElement('p');
            hint.className = 'wpla-help-text wpla-mt-2';
            hint.textContent = 'Escaneie o QR Code com o WhatsApp para conectar a instância.';
            wrapper.appendChild(hint);
        }
        img.src = src;
    }

    function setStatusBadge(status) {
        var badge = document.getElementById('evolution-status-badge');
        if (!badge) return;
        var labels = { open: 'Conectado ✅', connecting: 'Aguardando QR ⏳', close: 'Desconectado ❌' };
        badge.textContent = labels[status] || status;
        badge.className = 'wpla-badge wpla-ml-2 ' + (status === 'open' ? 'wpla-badge-success' : 'wpla-badge-warning');
    }
}());
</script>
