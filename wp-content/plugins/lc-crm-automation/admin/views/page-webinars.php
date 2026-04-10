<?php
/**
 * Webinars admin page — lista + editor.
 *
 * @package LC_CRM_Automation
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="wpla-webinars-page">

    <!-- ── Lista de webinars ── -->
    <div id="webinars-list-view">
        <div class="wpla-toolbar">
            <div class="wpla-toolbar-left">
                <h2><?php esc_html_e( 'Webinars', 'lc-crm' ); ?></h2>
            </div>
            <div class="wpla-toolbar-right">
                <button class="wpla-btn wpla-btn-primary" id="btn-new-webinar">+ <?php esc_html_e( 'Novo Webinar', 'lc-crm' ); ?></button>
            </div>
        </div>

        <div class="wpla-card">
            <div class="wpla-table-wrapper">
                <table class="wpla-table" id="webinars-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e( 'Nome', 'lc-crm' ); ?></th>
                            <th><?php esc_html_e( 'Vídeo', 'lc-crm' ); ?></th>
                            <th><?php esc_html_e( 'Status', 'lc-crm' ); ?></th>
                            <th><?php esc_html_e( 'Automação', 'lc-crm' ); ?></th>
                            <th><?php esc_html_e( 'Criado em', 'lc-crm' ); ?></th>
                            <th><?php esc_html_e( 'Ações', 'lc-crm' ); ?></th>
                        </tr>
                    </thead>
                    <tbody id="webinars-tbody">
                        <tr><td colspan="6" class="wpla-text-center"><?php esc_html_e( 'Carregando...', 'lc-crm' ); ?></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ── Editor de webinar ── -->
    <div id="webinar-editor" style="display:none;">
        <div class="wpla-toolbar">
            <div class="wpla-toolbar-left">
                <button class="wpla-btn" id="btn-back-webinars">← <?php esc_html_e( 'Voltar', 'lc-crm' ); ?></button>
                <span class="wpla-text-muted" id="webinar-editor-title"><?php esc_html_e( 'Novo Webinar', 'lc-crm' ); ?></span>
            </div>
            <div class="wpla-toolbar-right">
                <button class="wpla-btn wpla-btn-primary" id="btn-save-webinar"><?php esc_html_e( 'Salvar Webinar', 'lc-crm' ); ?></button>
            </div>
        </div>

        <input type="hidden" id="webinar-id" value="0" />

        <div class="wpla-form-grid-2col">

            <!-- Coluna esquerda -->
            <div>
                <!-- Dados básicos -->
                <div class="wpla-card wpla-mb-4">
                    <h3>📋 <?php esc_html_e( 'Dados Básicos', 'lc-crm' ); ?></h3>

                    <div class="wpla-field">
                        <label><?php esc_html_e( 'Nome do Webinar', 'lc-crm' ); ?> <span class="wpla-required">*</span></label>
                        <input type="text" id="webinar-name" class="wpla-input" placeholder="<?php esc_attr_e( 'Ex: Webinar de Lançamento', 'lc-crm' ); ?>" />
                    </div>

                    <div class="wpla-field">
                        <label><?php esc_html_e( 'Descrição', 'lc-crm' ); ?></label>
                        <textarea id="webinar-description" class="wpla-input" rows="3"></textarea>
                    </div>

                    <div class="wpla-field">
                        <label><?php esc_html_e( 'Status', 'lc-crm' ); ?></label>
                        <select id="webinar-status" class="wpla-select">
                            <option value="draft"><?php esc_html_e( 'Rascunho', 'lc-crm' ); ?></option>
                            <option value="active"><?php esc_html_e( 'Ativo', 'lc-crm' ); ?></option>
                            <option value="paused"><?php esc_html_e( 'Pausado', 'lc-crm' ); ?></option>
                        </select>
                    </div>
                </div>

                <!-- Vídeo -->
                <div class="wpla-card wpla-mb-4">
                    <h3>🎥 <?php esc_html_e( 'Configuração de Vídeo', 'lc-crm' ); ?></h3>

                    <div class="wpla-field">
                        <label><?php esc_html_e( 'Tipo de Vídeo', 'lc-crm' ); ?></label>
                        <select id="webinar-video-type" class="wpla-select">
                            <option value="youtube">YouTube</option>
                            <option value="vimeo">Vimeo</option>
                            <option value="html5">HTML5 (MP4)</option>
                        </select>
                    </div>

                    <div class="wpla-field">
                        <label id="webinar-video-url-label"><?php esc_html_e( 'URL / ID do Vídeo', 'lc-crm' ); ?></label>
                        <input type="text" id="webinar-video-url" class="wpla-input" placeholder="https://..." />
                        <p class="wpla-help-text" id="video-url-hint">
                            <?php esc_html_e( 'YouTube: cole o link completo ou só o ID. Vimeo: link completo. HTML5: URL direta do arquivo .mp4.', 'lc-crm' ); ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Coluna direita -->
            <div>
                <!-- Oferta -->
                <div class="wpla-card wpla-mb-4">
                    <h3>🎯 <?php esc_html_e( 'Configuração de Oferta', 'lc-crm' ); ?></h3>
                    <p class="wpla-text-muted wpla-mb-3"><?php esc_html_e( 'A oferta aparecerá em momentos diferentes para transmissões ao vivo e replays.', 'lc-crm' ); ?></p>

                    <div class="wpla-field">
                        <label><?php esc_html_e( 'Título da Oferta', 'lc-crm' ); ?></label>
                        <input type="text" id="webinar-offer-title" class="wpla-input" placeholder="<?php esc_attr_e( 'Ex: Garanta sua vaga agora!', 'lc-crm' ); ?>" />
                    </div>

                    <div class="wpla-field">
                        <label><?php esc_html_e( 'URL da Oferta (link do botão)', 'lc-crm' ); ?></label>
                        <input type="url" id="webinar-offer-url" class="wpla-input" placeholder="https://..." />
                    </div>

                    <div class="wpla-field">
                        <label><?php esc_html_e( 'Texto do Botão', 'lc-crm' ); ?></label>
                        <input type="text" id="webinar-offer-button" class="wpla-input" placeholder="<?php esc_attr_e( 'Quero participar!', 'lc-crm' ); ?>" />
                    </div>

                    <div class="wpla-form-grid">
                        <div class="wpla-field">
                            <label><?php esc_html_e( 'Tempo da oferta — Ao Vivo (segundos)', 'lc-crm' ); ?></label>
                            <input type="number" id="webinar-offer-time-live" class="wpla-input" min="0" placeholder="0" />
                            <p class="wpla-help-text"><?php esc_html_e( 'Ex: 1800 = aparecer aos 30 minutos do vídeo ao vivo.', 'lc-crm' ); ?></p>
                        </div>
                        <div class="wpla-field">
                            <label><?php esc_html_e( 'Tempo da oferta — Replay (segundos)', 'lc-crm' ); ?></label>
                            <input type="number" id="webinar-offer-time-replay" class="wpla-input" min="0" placeholder="0" />
                            <p class="wpla-help-text"><?php esc_html_e( 'Ex: 900 = aparecer aos 15 minutos no replay.', 'lc-crm' ); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /form-grid-2col -->

        <!-- Listas do webinar (visível após salvar) -->
        <div class="wpla-card wpla-mt-4" id="webinar-lists-section" style="display:none;">
            <h3>📋 <?php esc_html_e( 'Listas do Webinar', 'lc-crm' ); ?></h3>
            <p class="wpla-text-muted"><?php esc_html_e( 'Listas criadas automaticamente para este webinar. A lista INBOX deve ser mantida sempre vazia — os contatos são roteados para as demais listas conforme os eventos do webinar.', 'lc-crm' ); ?></p>
            <div class="wpla-table-wrapper">
                <table class="wpla-table" id="webinar-lists-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e( 'Tipo', 'lc-crm' ); ?></th>
                            <th><?php esc_html_e( 'Nome', 'lc-crm' ); ?></th>
                            <th><?php esc_html_e( 'Assinantes', 'lc-crm' ); ?></th>
                        </tr>
                    </thead>
                    <tbody id="webinar-lists-tbody">
                        <tr><td colspan="3" class="wpla-text-center"><?php esc_html_e( 'Salve o webinar para ver as listas.', 'lc-crm' ); ?></td></tr>
                    </tbody>
                </table>
            </div>
            <div class="wpla-mt-3">
                <a href="#" id="webinar-automation-link" class="wpla-btn" target="_blank" style="display:none;">
                    ⚡ <?php esc_html_e( 'Editar Automação do Webinar', 'lc-crm' ); ?>
                </a>
            </div>
        </div>
    </div><!-- /#webinar-editor -->

</div><!-- /.wpla-webinars-page -->

<script>
(function () {
    /* ── Webinar page JS ── */
    document.addEventListener('DOMContentLoaded', function () {
        if (!document.getElementById('webinars-list-view')) return;
        loadWebinars();

        document.getElementById('btn-new-webinar').addEventListener('click', function () {
            openEditor(null);
        });

        document.getElementById('btn-back-webinars').addEventListener('click', function () {
            showListView();
        });

        document.getElementById('btn-save-webinar').addEventListener('click', function () {
            saveWebinar();
        });

        document.getElementById('webinar-video-type').addEventListener('change', updateVideoHint);
    });

    function loadWebinars() {
        WPLA.ajax('wpla_list_webinars', {}, function (res) {
            var tbody = document.getElementById('webinars-tbody');
            if (!res.success || !res.data.items.length) {
                tbody.innerHTML = '<tr><td colspan="6" class="wpla-text-center"><?php echo esc_js( __( 'Nenhum webinar cadastrado.', 'lc-crm' ) ); ?></td></tr>';
                return;
            }
            tbody.innerHTML = res.data.items.map(function (w) {
                var statusLabel = { draft: '<?php echo esc_js( __( 'Rascunho', 'lc-crm' ) ); ?>', active: '<?php echo esc_js( __( 'Ativo', 'lc-crm' ) ); ?>', paused: '<?php echo esc_js( __( 'Pausado', 'lc-crm' ) ); ?>' }[w.status] || w.status;
                var autLink = w.automation_id ? '<a href="' + wpla.site_url + '/wp-admin/admin.php?page=wpla-automations&automation_id=' + w.automation_id + '" class="wpla-btn wpla-btn-sm">⚡</a>' : '—';
                return '<tr>' +
                    '<td><strong>' + WPLA.escHtml(w.name) + '</strong></td>' +
                    '<td>' + WPLA.escHtml(w.video_type.toUpperCase()) + '</td>' +
                    '<td><span class="wpla-badge wpla-badge-' + (w.status === 'active' ? 'success' : 'info') + '">' + statusLabel + '</span></td>' +
                    '<td>' + autLink + '</td>' +
                    '<td>' + WPLA.escHtml(w.created_at) + '</td>' +
                    '<td><button class="wpla-btn wpla-btn-sm" onclick="WPLA.editWebinar(' + w.id + ')"><?php echo esc_js( __( 'Editar', 'lc-crm' ) ); ?></button> ' +
                    '<button class="wpla-btn wpla-btn-sm wpla-btn-danger" onclick="WPLA.deleteWebinar(' + w.id + ')"><?php echo esc_js( __( 'Excluir', 'lc-crm' ) ); ?></button></td>' +
                    '</tr>';
            }).join('');
        });
    }

    function openEditor(webinar) {
        document.getElementById('webinars-list-view').style.display = 'none';
        document.getElementById('webinar-editor').style.display = 'block';

        if (webinar) {
            document.getElementById('webinar-editor-title').textContent = webinar.name;
            document.getElementById('webinar-id').value = webinar.id;
            document.getElementById('webinar-name').value = webinar.name;
            document.getElementById('webinar-description').value = webinar.description || '';
            document.getElementById('webinar-status').value = webinar.status;
            document.getElementById('webinar-video-type').value = webinar.video_type;
            document.getElementById('webinar-video-url').value = webinar.video_url || '';
            document.getElementById('webinar-offer-title').value = webinar.offer_title || '';
            document.getElementById('webinar-offer-url').value = webinar.offer_url || '';
            document.getElementById('webinar-offer-button').value = webinar.offer_button_text || '';
            document.getElementById('webinar-offer-time-live').value = webinar.offer_time_live || 0;
            document.getElementById('webinar-offer-time-replay').value = webinar.offer_time_replay || 0;
            loadWebinarLists(webinar.id, webinar.automation_id);
            document.getElementById('webinar-lists-section').style.display = 'block';
        } else {
            document.getElementById('webinar-editor-title').textContent = '<?php echo esc_js( __( 'Novo Webinar', 'lc-crm' ) ); ?>';
            document.getElementById('webinar-id').value = 0;
            document.getElementById('webinar-name').value = '';
            document.getElementById('webinar-description').value = '';
            document.getElementById('webinar-status').value = 'draft';
            document.getElementById('webinar-video-type').value = 'youtube';
            document.getElementById('webinar-video-url').value = '';
            document.getElementById('webinar-offer-title').value = '';
            document.getElementById('webinar-offer-url').value = '';
            document.getElementById('webinar-offer-button').value = '';
            document.getElementById('webinar-offer-time-live').value = 0;
            document.getElementById('webinar-offer-time-replay').value = 0;
            document.getElementById('webinar-lists-section').style.display = 'none';
        }
        updateVideoHint();
    }

    function loadWebinarLists(webinarId, automationId) {
        WPLA.ajax('wpla_get_webinar_lists', { webinar_id: webinarId }, function (res) {
            var tbody = document.getElementById('webinar-lists-tbody');
            if (!res.success || !res.data.lists.length) {
                tbody.innerHTML = '<tr><td colspan="3" class="wpla-text-center"><?php echo esc_js( __( 'Nenhuma lista encontrada.', 'lc-crm' ) ); ?></td></tr>';
                return;
            }
            var typeLabels = {
                inbox: 'INBOX',
                assistiu_oferta: '<?php echo esc_js( __( 'Assistiu Oferta', 'lc-crm' ) ); ?>',
                nao_viu_oferta: '<?php echo esc_js( __( 'Não Viu Oferta', 'lc-crm' ) ); ?>',
                replay: 'Replay',
                converteu: '<?php echo esc_js( __( 'Converteu', 'lc-crm' ) ); ?>'
            };
            tbody.innerHTML = res.data.lists.map(function (l) {
                var badge = l.list_type === 'inbox'
                    ? '<span class="wpla-badge wpla-badge-warning">INBOX</span>'
                    : '<span class="wpla-badge wpla-badge-info">' + (typeLabels[l.list_type] || l.list_type) + '</span>';
                return '<tr><td>' + badge + '</td><td>' + WPLA.escHtml(l.name) + '</td><td>' + (l.subscriber_count || 0) + '</td></tr>';
            }).join('');
        });

        if (automationId) {
            var link = document.getElementById('webinar-automation-link');
            link.href = wpla.site_url + '/wp-admin/admin.php?page=wpla-automations&automation_id=' + automationId;
            link.style.display = 'inline-flex';
        }
    }

    function saveWebinar() {
        var id   = parseInt(document.getElementById('webinar-id').value, 10);
        var name = document.getElementById('webinar-name').value.trim();
        if (!name) { alert('<?php echo esc_js( __( 'O nome do webinar é obrigatório.', 'lc-crm' ) ); ?>'); return; }

        var data = {
            id: id,
            name: name,
            description: document.getElementById('webinar-description').value,
            status: document.getElementById('webinar-status').value,
            video_type: document.getElementById('webinar-video-type').value,
            video_url: document.getElementById('webinar-video-url').value,
            offer_title: document.getElementById('webinar-offer-title').value,
            offer_url: document.getElementById('webinar-offer-url').value,
            offer_button_text: document.getElementById('webinar-offer-button').value,
            offer_time_live: document.getElementById('webinar-offer-time-live').value,
            offer_time_replay: document.getElementById('webinar-offer-time-replay').value,
        };

        WPLA.ajax('wpla_save_webinar', data, function (res) {
            if (res.success) {
                var newId = res.data.id;
                document.getElementById('webinar-id').value = newId;
                document.getElementById('webinar-editor-title').textContent = name;
                document.getElementById('webinar-lists-section').style.display = 'block';
                loadWebinarLists(newId, res.data.automation_id);
                WPLA.showNotice('<?php echo esc_js( __( 'Webinar salvo com sucesso!', 'lc-crm' ) ); ?>', 'success');
            } else {
                WPLA.showNotice(res.data && res.data.message ? res.data.message : '<?php echo esc_js( __( 'Erro ao salvar webinar.', 'lc-crm' ) ); ?>', 'error');
            }
        });
    }

    function showListView() {
        document.getElementById('webinar-editor').style.display = 'none';
        document.getElementById('webinars-list-view').style.display = 'block';
        loadWebinars();
    }

    function updateVideoHint() {
        var type = document.getElementById('webinar-video-type').value;
        var hint = document.getElementById('video-url-hint');
        var hints = {
            youtube: '<?php echo esc_js( __( 'Cole o link completo ou o ID do vídeo do YouTube.', 'lc-crm' ) ); ?>',
            vimeo:   '<?php echo esc_js( __( 'Cole o link completo do Vimeo (ex: https://vimeo.com/123456789).', 'lc-crm' ) ); ?>',
            html5:   '<?php echo esc_js( __( 'Cole a URL direta do arquivo .mp4 hospedado.', 'lc-crm' ) ); ?>',
        };
        if (hint) hint.textContent = hints[type] || '';
    }

    /* ── Métodos expostos globalmente ── */
    WPLA.editWebinar = function (id) {
        WPLA.ajax('wpla_get_webinar', { id: id }, function (res) {
            if (res.success) openEditor(res.data.webinar);
        });
    };

    WPLA.deleteWebinar = function (id) {
        if (!confirm('<?php echo esc_js( __( 'Tem certeza que deseja excluir este webinar? Esta ação excluirá também todas as listas e automações associadas.', 'lc-crm' ) ); ?>')) return;
        WPLA.ajax('wpla_delete_webinar', { id: id }, function (res) {
            if (res.success) {
                WPLA.showNotice('<?php echo esc_js( __( 'Webinar excluído.', 'lc-crm' ) ); ?>', 'success');
                loadWebinars();
            }
        });
    };
}());
</script>
