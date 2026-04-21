<?php
/**
 * Settings page view.
 *
 * @package Plugin_Leads_SaaS
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="pls-settings-page">
    <form id="settings-form">

        <!-- WhatsApp Settings -->
        <div class="pls-card pls-mb-4">
            <h3>💬 <?php esc_html_e( 'Configurações WhatsApp', 'plugin-leads-saas' ); ?></h3>

            <div class="pls-field">
                <label><?php esc_html_e( 'Provedor', 'plugin-leads-saas' ); ?></label>
                <select name="pls_whatsapp_provider" id="pls_whatsapp_provider" class="pls-select">
                    <option value="evolution" <?php selected( get_option( 'pls_whatsapp_provider' ), 'evolution' ); ?>><?php esc_html_e( 'Evolution API (instância própria)', 'plugin-leads-saas' ); ?></option>
                    <option value="meta" <?php selected( get_option( 'pls_whatsapp_provider' ), 'meta' ); ?>><?php esc_html_e( 'WhatsApp Cloud API (Meta)', 'plugin-leads-saas' ); ?></option>
                </select>
            </div>

            <fieldset class="pls-fieldset" id="evolution-settings">
                <legend><?php esc_html_e( 'Evolution API', 'plugin-leads-saas' ); ?></legend>
                <p class="pls-text-muted pls-mb-3"><?php esc_html_e( 'Preencha os campos abaixo e clique em "Salvar Configurações" para criar a instância automaticamente e exibir o QR Code de conexão.', 'plugin-leads-saas' ); ?></p>
                <div class="pls-form-grid">
                    <div class="pls-field">
                        <label><?php esc_html_e( 'URL Base da API', 'plugin-leads-saas' ); ?></label>
                        <input type="url" name="pls_evolution_api_url" id="pls_evolution_api_url" class="pls-input" value="<?php echo esc_attr( get_option( 'pls_evolution_api_url' ) ); ?>" placeholder="https://sua-evolution.com.br" />
                    </div>
                    <div class="pls-field">
                        <label><?php esc_html_e( 'API Key (Global)', 'plugin-leads-saas' ); ?></label>
                        <input type="text" name="pls_evolution_api_key" id="pls_evolution_api_key" class="pls-input" value="<?php echo esc_attr( get_option( 'pls_evolution_api_key' ) ); ?>" placeholder="sua-api-key-global" />
                    </div>
                </div>
                <div class="pls-field">
                    <label><?php esc_html_e( 'Nome da Instância', 'plugin-leads-saas' ); ?></label>
                    <input type="text" name="pls_evolution_instance" id="pls_evolution_instance" class="pls-input" value="<?php echo esc_attr( get_option( 'pls_evolution_instance' ) ); ?>" placeholder="minha-instancia" />
                    <p class="pls-help-text"><?php esc_html_e( 'Use apenas letras, números e hífens. Ex: leads-marketing', 'plugin-leads-saas' ); ?></p>
                </div>

                <!-- QR Code section -->
                <div id="evolution-qr-section" class="pls-mt-3" <?php if ( ! get_option( 'pls_evolution_instance' ) ) echo 'style="display:none"'; ?>>
                    <div id="evolution-qr-wrapper" class="pls-qr-wrapper">
                        <?php if ( get_option( 'pls_evolution_qr_code' ) ) : ?>
                            <img src="<?php echo esc_attr( get_option( 'pls_evolution_qr_code' ) ); ?>" alt="QR Code" class="pls-qr-image" id="evolution-qr-img" />
                            <p class="pls-help-text pls-mt-2"><?php esc_html_e( 'Escaneie o QR Code com o WhatsApp para conectar a instância.', 'plugin-leads-saas' ); ?></p>
                        <?php else : ?>
                            <p class="pls-text-muted" id="evolution-qr-placeholder"><?php esc_html_e( 'Salve as configurações para gerar o QR Code de conexão.', 'plugin-leads-saas' ); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="pls-mt-2">
                        <button type="button" class="pls-btn" id="btn-refresh-qr">🔄 <?php esc_html_e( 'Atualizar QR Code', 'plugin-leads-saas' ); ?></button>
                        <span id="evolution-status-badge" class="pls-badge pls-badge-info pls-ml-2"></span>
                    </div>
                </div>
            </fieldset>

            <fieldset class="pls-fieldset" id="meta-settings">
                <legend><?php esc_html_e( 'WhatsApp Cloud API (Meta)', 'plugin-leads-saas' ); ?></legend>
                <p class="pls-text-muted pls-mb-3"><?php esc_html_e( 'Configure seu token e número de telefone do WhatsApp Business via Meta.', 'plugin-leads-saas' ); ?></p>
                <div class="pls-form-grid">
                    <div class="pls-field">
                        <label><?php esc_html_e( 'Token de Acesso Permanente', 'plugin-leads-saas' ); ?></label>
                        <input type="text" name="pls_meta_whatsapp_token" class="pls-input" value="<?php echo esc_attr( get_option( 'pls_meta_whatsapp_token' ) ); ?>" placeholder="EAAxxxxx..." />
                    </div>
                    <div class="pls-field">
                        <label><?php esc_html_e( 'ID do Número de Telefone', 'plugin-leads-saas' ); ?></label>
                        <input type="text" name="pls_meta_phone_number_id" class="pls-input" value="<?php echo esc_attr( get_option( 'pls_meta_phone_number_id' ) ); ?>" placeholder="1234567890" />
                    </div>
                </div>
            </fieldset>
        </div>

        <!-- Email / SMTP Settings -->
        <div class="pls-card pls-mb-4">
            <h3>📧 <?php esc_html_e( 'Configurações de Email (SMTP)', 'plugin-leads-saas' ); ?></h3>
            <p class="pls-text-muted pls-mb-3"><?php esc_html_e( 'Configure o servidor SMTP para envio de emails confiável. Deixe em branco para usar o sendmail padrão do WordPress.', 'plugin-leads-saas' ); ?></p>

            <div class="pls-form-grid">
                <div class="pls-field">
                    <label><?php esc_html_e( 'Nome do Remetente', 'plugin-leads-saas' ); ?></label>
                    <input type="text" name="pls_email_from_name" class="pls-input" value="<?php echo esc_attr( get_option( 'pls_email_from_name', get_bloginfo( 'name' ) ) ); ?>" />
                </div>
                <div class="pls-field">
                    <label><?php esc_html_e( 'Email do Remetente', 'plugin-leads-saas' ); ?></label>
                    <input type="email" name="pls_email_from_address" class="pls-input" value="<?php echo esc_attr( get_option( 'pls_email_from_address', get_bloginfo( 'admin_email' ) ) ); ?>" />
                </div>
            </div>

            <fieldset class="pls-fieldset pls-mt-3">
                <legend><?php esc_html_e( 'Servidor SMTP', 'plugin-leads-saas' ); ?></legend>
                <div class="pls-form-grid">
                    <div class="pls-field">
                        <label><?php esc_html_e( 'Host SMTP', 'plugin-leads-saas' ); ?></label>
                        <input type="text" name="pls_smtp_host" class="pls-input" value="<?php echo esc_attr( get_option( 'pls_smtp_host' ) ); ?>" placeholder="smtp.seuservidor.com.br" />
                    </div>
                    <div class="pls-field">
                        <label><?php esc_html_e( 'Porta SMTP', 'plugin-leads-saas' ); ?></label>
                        <input type="number" name="pls_smtp_port" class="pls-input" value="<?php echo esc_attr( get_option( 'pls_smtp_port', '587' ) ); ?>" placeholder="587" />
                    </div>
                </div>
                <div class="pls-form-grid">
                    <div class="pls-field">
                        <label><?php esc_html_e( 'Criptografia', 'plugin-leads-saas' ); ?></label>
                        <select name="pls_smtp_encryption" class="pls-select">
                            <option value="" <?php selected( get_option( 'pls_smtp_encryption' ), '' ); ?>><?php esc_html_e( 'Nenhuma', 'plugin-leads-saas' ); ?></option>
                            <option value="tls" <?php selected( get_option( 'pls_smtp_encryption' ), 'tls' ); ?>>TLS (<?php esc_html_e( 'recomendado', 'plugin-leads-saas' ); ?>)</option>
                            <option value="ssl" <?php selected( get_option( 'pls_smtp_encryption' ), 'ssl' ); ?>>SSL</option>
                        </select>
                    </div>
                    <div class="pls-field">
                        <label><?php esc_html_e( 'Autenticação', 'plugin-leads-saas' ); ?></label>
                        <select name="pls_smtp_auth" class="pls-select">
                            <option value="1" <?php selected( get_option( 'pls_smtp_auth', '1' ), '1' ); ?>><?php esc_html_e( 'Sim (recomendado)', 'plugin-leads-saas' ); ?></option>
                            <option value="0" <?php selected( get_option( 'pls_smtp_auth', '1' ), '0' ); ?>><?php esc_html_e( 'Não', 'plugin-leads-saas' ); ?></option>
                        </select>
                    </div>
                </div>
                <div class="pls-form-grid">
                    <div class="pls-field">
                        <label><?php esc_html_e( 'Usuário SMTP', 'plugin-leads-saas' ); ?></label>
                        <input type="text" name="pls_smtp_user" class="pls-input" value="<?php echo esc_attr( get_option( 'pls_smtp_user' ) ); ?>" placeholder="usuario@seudominio.com.br" autocomplete="off" />
                    </div>
                    <div class="pls-field">
                        <label><?php esc_html_e( 'Senha SMTP', 'plugin-leads-saas' ); ?></label>
                        <input type="password" name="pls_smtp_pass" class="pls-input" value="<?php echo esc_attr( get_option( 'pls_smtp_pass' ) ); ?>" autocomplete="new-password" />
                    </div>
                </div>
            </fieldset>

            <!-- Test SMTP -->
            <div class="pls-mt-3">
                <h4><?php esc_html_e( 'Testar Envio de Email', 'plugin-leads-saas' ); ?></h4>
                <div class="pls-form-grid pls-mt-2">
                    <div class="pls-field">
                        <input type="email" id="smtp-test-to" class="pls-input" placeholder="<?php esc_attr_e( 'Enviar teste para...', 'plugin-leads-saas' ); ?>" value="<?php echo esc_attr( get_bloginfo( 'admin_email' ) ); ?>" />
                    </div>
                    <div class="pls-field">
                        <button type="button" class="pls-btn" id="btn-test-smtp"><?php esc_html_e( 'Enviar Email de Teste', 'plugin-leads-saas' ); ?></button>
                    </div>
                </div>
                <div id="smtp-test-result" class="pls-mt-2"></div>
            </div>
        </div>

        <!-- API Key -->
        <div class="pls-card pls-mb-4">
            <h3>🔑 <?php esc_html_e( 'Chave API (Webhooks)', 'plugin-leads-saas' ); ?></h3>
            <p class="pls-text-muted pls-mb-2"><?php esc_html_e( 'Use esta chave para autenticar webhooks inbound de plataformas externas (Hotmart, Eduzz, Kiwify, etc).', 'plugin-leads-saas' ); ?></p>
            <div class="pls-field">
                <label><?php esc_html_e( 'Chave API Atual', 'plugin-leads-saas' ); ?></label>
                <div class="pls-input-group">
                    <input type="text" class="pls-input" value="<?php echo esc_attr( get_option( 'pls_api_key' ) ); ?>" readonly id="current-api-key" />
                    <button type="button" class="pls-btn" onclick="PLS.copyToClipboard(document.getElementById('current-api-key').value)"><?php esc_html_e( 'Copiar', 'plugin-leads-saas' ); ?></button>
                </div>
            </div>
            <p class="pls-text-muted pls-mt-1">
                <?php esc_html_e( 'Endpoint de webhook:', 'plugin-leads-saas' ); ?>
                <code><?php echo esc_html( rest_url( 'pls/v1/webhook/inbound' ) ); ?></code>
            </p>
            <label class="pls-checkbox pls-mt-2">
                <input type="checkbox" name="regenerate_api_key" value="1" />
                <?php esc_html_e( 'Regenerar chave API ao salvar', 'plugin-leads-saas' ); ?>
            </label>
        </div>

        <button type="submit" class="pls-btn pls-btn-primary pls-btn-lg" id="btn-save-settings">
            <?php esc_html_e( 'Salvar Configurações', 'plugin-leads-saas' ); ?>
        </button>
    </form>
</div>

<script>
(function () {
    document.addEventListener('DOMContentLoaded', function () {
        var providerSel = document.getElementById('pls_whatsapp_provider');
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
                PLS.ajax('pls_send_test_email', {
                    to:      to,
                    subject: 'Teste SMTP — Leads SaaS',
                    body:    '<p>Email de teste enviado com sucesso via Leads SaaS!</p>',
                }, function (res) {
                    var el = document.getElementById('smtp-test-result');
                    if (res.success && res.data.sent) {
                        el.innerHTML = '<span class="pls-badge pls-badge-success">Email enviado com sucesso!</span>';
                    } else {
                        el.innerHTML = '<span class="pls-badge pls-badge-danger">Falha ao enviar. Verifique as configurações SMTP.</span>';
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

        PLS.ajax('pls_save_settings', data, function (res) {
            if (res.success) {
                PLS.showNotice('Configurações salvas!', 'success');
                if (res.data && res.data.api_key) {
                    var el = document.getElementById('current-api-key');
                    if (el) el.value = res.data.api_key;
                }
                var provider  = document.getElementById('pls_whatsapp_provider');
                var apiUrl    = document.getElementById('pls_evolution_api_url');
                var apiKey    = document.getElementById('pls_evolution_api_key');
                var instanceF = document.getElementById('pls_evolution_instance');
                if (provider && provider.value === 'evolution' && apiUrl && apiKey && instanceF) {
                    var u = apiUrl.value.trim();
                    var k = apiKey.value.trim();
                    var i = instanceF.value.trim();
                    if (u && k && i) {
                        createEvolutionInstance(u, k, i);
                    }
                }
            } else {
                PLS.showNotice('Erro ao salvar configurações.', 'error');
            }
        });
    }

    function createEvolutionInstance(apiUrl, apiKey, instanceName) {
        PLS.ajax('pls_evolution_create_instance', {
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
        PLS.ajax('pls_evolution_get_qr', {}, function (res) {
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
            img.className = 'pls-qr-image';
            img.alt = 'QR Code';
            wrapper.appendChild(img);
            var hint = document.createElement('p');
            hint.className = 'pls-help-text pls-mt-2';
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
        badge.className = 'pls-badge pls-ml-2 ' + (status === 'open' ? 'pls-badge-success' : 'pls-badge-warning');
    }
}());
</script>
