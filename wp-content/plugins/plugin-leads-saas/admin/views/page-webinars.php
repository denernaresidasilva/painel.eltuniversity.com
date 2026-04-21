<?php
/**
 * Webinars page view.
 *
 * @package Plugin_Leads_SaaS
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="pls-webinars-page">

    <!-- Webinar list view -->
    <div id="webinars-list-view">
        <div class="pls-toolbar">
            <div class="pls-toolbar-left">
                <h2><?php esc_html_e( 'Webinars', 'plugin-leads-saas' ); ?></h2>
            </div>
            <div class="pls-toolbar-right">
                <button class="pls-btn pls-btn-primary" id="btn-new-webinar">+ <?php esc_html_e( 'Novo Webinar', 'plugin-leads-saas' ); ?></button>
            </div>
        </div>

        <div class="pls-card">
            <div class="pls-table-wrapper">
                <table class="pls-table" id="webinars-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e( 'Nome', 'plugin-leads-saas' ); ?></th>
                            <th><?php esc_html_e( 'Vídeo', 'plugin-leads-saas' ); ?></th>
                            <th><?php esc_html_e( 'Status', 'plugin-leads-saas' ); ?></th>
                            <th><?php esc_html_e( 'Automação', 'plugin-leads-saas' ); ?></th>
                            <th><?php esc_html_e( 'Criado em', 'plugin-leads-saas' ); ?></th>
                            <th><?php esc_html_e( 'Ações', 'plugin-leads-saas' ); ?></th>
                        </tr>
                    </thead>
                    <tbody id="webinars-tbody">
                        <tr><td colspan="6" class="pls-text-center"><?php esc_html_e( 'Carregando...', 'plugin-leads-saas' ); ?></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Webinar editor -->
    <div id="webinar-editor" style="display:none;">
        <div class="pls-toolbar">
            <div class="pls-toolbar-left">
                <button class="pls-btn" id="btn-back-webinars">← <?php esc_html_e( 'Voltar', 'plugin-leads-saas' ); ?></button>
                <span class="pls-text-muted" id="webinar-editor-title"><?php esc_html_e( 'Novo Webinar', 'plugin-leads-saas' ); ?></span>
            </div>
            <div class="pls-toolbar-right">
                <button class="pls-btn pls-btn-primary" id="btn-save-webinar"><?php esc_html_e( 'Salvar Webinar', 'plugin-leads-saas' ); ?></button>
            </div>
        </div>

        <input type="hidden" id="webinar-id" value="0" />

        <div class="pls-form-grid-2col">
            <!-- Left column -->
            <div>
                <div class="pls-card pls-mb-4">
                    <h3>📋 <?php esc_html_e( 'Dados Básicos', 'plugin-leads-saas' ); ?></h3>
                    <div class="pls-field">
                        <label><?php esc_html_e( 'Nome do Webinar', 'plugin-leads-saas' ); ?> <span class="pls-required">*</span></label>
                        <input type="text" id="webinar-name" class="pls-input" placeholder="<?php esc_attr_e( 'Ex: Webinar de Lançamento', 'plugin-leads-saas' ); ?>" />
                    </div>
                    <div class="pls-field">
                        <label><?php esc_html_e( 'Descrição', 'plugin-leads-saas' ); ?></label>
                        <textarea id="webinar-description" class="pls-input" rows="3"></textarea>
                    </div>
                    <div class="pls-field">
                        <label><?php esc_html_e( 'Status', 'plugin-leads-saas' ); ?></label>
                        <select id="webinar-status" class="pls-select">
                            <option value="draft"><?php esc_html_e( 'Rascunho', 'plugin-leads-saas' ); ?></option>
                            <option value="active"><?php esc_html_e( 'Ativo', 'plugin-leads-saas' ); ?></option>
                            <option value="paused"><?php esc_html_e( 'Pausado', 'plugin-leads-saas' ); ?></option>
                        </select>
                    </div>
                </div>

                <div class="pls-card pls-mb-4">
                    <h3>🎥 <?php esc_html_e( 'Configuração de Vídeo', 'plugin-leads-saas' ); ?></h3>
                    <div class="pls-field">
                        <label><?php esc_html_e( 'Tipo de Vídeo', 'plugin-leads-saas' ); ?></label>
                        <select id="webinar-video-type" class="pls-select">
                            <option value="youtube">YouTube</option>
                            <option value="vimeo">Vimeo</option>
                            <option value="html5">HTML5 (MP4)</option>
                        </select>
                    </div>
                    <div class="pls-field">
                        <label id="webinar-video-url-label"><?php esc_html_e( 'URL / ID do Vídeo', 'plugin-leads-saas' ); ?></label>
                        <input type="text" id="webinar-video-url" class="pls-input" placeholder="https://..." />
                        <p class="pls-help-text" id="video-url-hint">
                            <?php esc_html_e( 'YouTube: cole o link completo ou só o ID. Vimeo: link completo. HTML5: URL direta do arquivo .mp4.', 'plugin-leads-saas' ); ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right column -->
            <div>
                <div class="pls-card pls-mb-4">
                    <h3>🎯 <?php esc_html_e( 'Configuração de Oferta', 'plugin-leads-saas' ); ?></h3>
                    <p class="pls-text-muted pls-mb-3"><?php esc_html_e( 'A oferta aparecerá em momentos diferentes para transmissões ao vivo e replays.', 'plugin-leads-saas' ); ?></p>
                    <div class="pls-field">
                        <label><?php esc_html_e( 'Título da Oferta', 'plugin-leads-saas' ); ?></label>
                        <input type="text" id="webinar-offer-title" class="pls-input" placeholder="<?php esc_attr_e( 'Ex: Garanta sua vaga agora!', 'plugin-leads-saas' ); ?>" />
                    </div>
                    <div class="pls-field">
                        <label><?php esc_html_e( 'URL da Oferta (link do botão)', 'plugin-leads-saas' ); ?></label>
                        <input type="url" id="webinar-offer-url" class="pls-input" placeholder="https://..." />
                    </div>
                    <div class="pls-field">
                        <label><?php esc_html_e( 'Texto do Botão', 'plugin-leads-saas' ); ?></label>
                        <input type="text" id="webinar-offer-button" class="pls-input" placeholder="<?php esc_attr_e( 'Quero participar!', 'plugin-leads-saas' ); ?>" />
                    </div>
                    <div class="pls-form-grid">
                        <div class="pls-field">
                            <label><?php esc_html_e( 'Tempo da oferta — Ao Vivo (segundos)', 'plugin-leads-saas' ); ?></label>
                            <input type="number" id="webinar-offer-time-live" class="pls-input" min="0" placeholder="0" />
                            <p class="pls-help-text"><?php esc_html_e( 'Ex: 1800 = aparecer aos 30 minutos do vídeo ao vivo.', 'plugin-leads-saas' ); ?></p>
                        </div>
                        <div class="pls-field">
                            <label><?php esc_html_e( 'Tempo da oferta — Replay (segundos)', 'plugin-leads-saas' ); ?></label>
                            <input type="number" id="webinar-offer-time-replay" class="pls-input" min="0" placeholder="0" />
                            <p class="pls-help-text"><?php esc_html_e( 'Ex: 900 = aparecer aos 15 minutos no replay.', 'plugin-leads-saas' ); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Webinar lists (visible after save) -->
        <div class="pls-card pls-mt-4" id="webinar-lists-section" style="display:none;">
            <h3>📋 <?php esc_html_e( 'Listas do Webinar', 'plugin-leads-saas' ); ?></h3>
            <p class="pls-text-muted"><?php esc_html_e( 'Listas criadas automaticamente para este webinar. A lista INBOX deve ser mantida sempre vazia — os contatos são roteados para as demais listas conforme os eventos do webinar.', 'plugin-leads-saas' ); ?></p>
            <div class="pls-table-wrapper">
                <table class="pls-table" id="webinar-lists-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e( 'Tipo', 'plugin-leads-saas' ); ?></th>
                            <th><?php esc_html_e( 'Nome', 'plugin-leads-saas' ); ?></th>
                            <th><?php esc_html_e( 'Assinantes', 'plugin-leads-saas' ); ?></th>
                        </tr>
                    </thead>
                    <tbody id="webinar-lists-tbody">
                        <tr><td colspan="3" class="pls-text-center"><?php esc_html_e( 'Salve o webinar para ver as listas.', 'plugin-leads-saas' ); ?></td></tr>
                    </tbody>
                </table>
            </div>
            <div class="pls-mt-3">
                <a href="#" id="webinar-automation-link" class="pls-btn" target="_blank" style="display:none;">
                    ⚡ <?php esc_html_e( 'Editar Automação do Webinar', 'plugin-leads-saas' ); ?>
                </a>
            </div>
        </div>
    </div><!-- /#webinar-editor -->

</div><!-- /.pls-webinars-page -->

<script>
(function () {
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
        PLS.ajax('pls_list_webinars', {}, function (res) {
            var tbody = document.getElementById('webinars-tbody');
            if (!res.success || !res.data.items.length) {
                tbody.innerHTML = '<tr><td colspan="6" class="pls-text-center"><?php echo esc_js( __( 'Nenhum webinar cadastrado.', 'plugin-leads-saas' ) ); ?></td></tr>';
                return;
            }
            tbody.innerHTML = res.data.items.map(function (w) {
                var statusLabel = { draft: '<?php echo esc_js( __( 'Rascunho', 'plugin-leads-saas' ) ); ?>', active: '<?php echo esc_js( __( 'Ativo', 'plugin-leads-saas' ) ); ?>', paused: '<?php echo esc_js( __( 'Pausado', 'plugin-leads-saas' ) ); ?>' }[w.status] || w.status;
                var autLink = w.automation_id ? '<a href="' + pls.site_url + '/wp-admin/admin.php?page=pls-automations&automation_id=' + w.automation_id + '" class="pls-btn pls-btn-sm">⚡</a>' : '—';
                return '<tr>' +
                    '<td><strong>' + PLS.escHtml(w.name) + '</strong></td>' +
                    '<td>' + PLS.escHtml(w.video_type.toUpperCase()) + '</td>' +
                    '<td><span class="pls-badge pls-badge-' + (w.status === 'active' ? 'success' : 'info') + '">' + statusLabel + '</span></td>' +
                    '<td>' + autLink + '</td>' +
                    '<td>' + PLS.escHtml(w.created_at) + '</td>' +
                    '<td><button class="pls-btn pls-btn-sm" onclick="PLS.editWebinar(' + w.id + ')"><?php echo esc_js( __( 'Editar', 'plugin-leads-saas' ) ); ?></button> ' +
                    '<button class="pls-btn pls-btn-sm pls-btn-danger" onclick="PLS.deleteWebinar(' + w.id + ')"><?php echo esc_js( __( 'Excluir', 'plugin-leads-saas' ) ); ?></button></td>' +
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
            document.getElementById('webinar-editor-title').textContent = '<?php echo esc_js( __( 'Novo Webinar', 'plugin-leads-saas' ) ); ?>';
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
        PLS.ajax('pls_get_webinar_lists', { webinar_id: webinarId }, function (res) {
            var tbody = document.getElementById('webinar-lists-tbody');
            if (!res.success || !res.data.lists.length) {
                tbody.innerHTML = '<tr><td colspan="3" class="pls-text-center"><?php echo esc_js( __( 'Nenhuma lista encontrada.', 'plugin-leads-saas' ) ); ?></td></tr>';
                return;
            }
            var typeLabels = {
                inbox: 'INBOX',
                assistiu_oferta: '<?php echo esc_js( __( 'Assistiu Oferta', 'plugin-leads-saas' ) ); ?>',
                nao_viu_oferta: '<?php echo esc_js( __( 'Não Viu Oferta', 'plugin-leads-saas' ) ); ?>',
                replay: 'Replay',
                converteu: '<?php echo esc_js( __( 'Converteu', 'plugin-leads-saas' ) ); ?>'
            };
            tbody.innerHTML = res.data.lists.map(function (l) {
                var badge = l.list_type === 'inbox'
                    ? '<span class="pls-badge pls-badge-warning">INBOX</span>'
                    : '<span class="pls-badge pls-badge-info">' + (typeLabels[l.list_type] || l.list_type) + '</span>';
                return '<tr><td>' + badge + '</td><td>' + PLS.escHtml(l.name) + '</td><td>' + (l.subscriber_count || 0) + '</td></tr>';
            }).join('');
        });

        if (automationId) {
            var link = document.getElementById('webinar-automation-link');
            link.href = pls.site_url + '/wp-admin/admin.php?page=pls-automations&automation_id=' + automationId;
            link.style.display = 'inline-flex';
        }
    }

    function saveWebinar() {
        var id   = parseInt(document.getElementById('webinar-id').value, 10);
        var name = document.getElementById('webinar-name').value.trim();
        if (!name) { alert('<?php echo esc_js( __( 'O nome do webinar é obrigatório.', 'plugin-leads-saas' ) ); ?>'); return; }

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

        PLS.ajax('pls_save_webinar', data, function (res) {
            if (res.success) {
                var newId = res.data.id;
                document.getElementById('webinar-id').value = newId;
                document.getElementById('webinar-editor-title').textContent = name;
                document.getElementById('webinar-lists-section').style.display = 'block';
                loadWebinarLists(newId, res.data.automation_id);
                PLS.showNotice('<?php echo esc_js( __( 'Webinar salvo com sucesso!', 'plugin-leads-saas' ) ); ?>', 'success');
            } else {
                PLS.showNotice(res.data && res.data.message ? res.data.message : '<?php echo esc_js( __( 'Erro ao salvar webinar.', 'plugin-leads-saas' ) ); ?>', 'error');
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
            youtube: '<?php echo esc_js( __( 'Cole o link completo ou o ID do vídeo do YouTube.', 'plugin-leads-saas' ) ); ?>',
            vimeo:   '<?php echo esc_js( __( 'Cole o link completo do Vimeo (ex: https://vimeo.com/123456789).', 'plugin-leads-saas' ) ); ?>',
            html5:   '<?php echo esc_js( __( 'Cole a URL direta do arquivo .mp4 hospedado.', 'plugin-leads-saas' ) ); ?>',
        };
        if (hint) hint.textContent = hints[type] || '';
    }

    PLS.editWebinar = function (id) {
        PLS.ajax('pls_get_webinar', { id: id }, function (res) {
            if (res.success) openEditor(res.data.webinar);
        });
    };

    PLS.deleteWebinar = function (id) {
        if (!confirm('<?php echo esc_js( __( 'Tem certeza que deseja excluir este webinar? Esta ação excluirá também todas as listas e automações associadas.', 'plugin-leads-saas' ) ); ?>')) return;
        PLS.ajax('pls_delete_webinar', { id: id }, function (res) {
            if (res.success) {
                PLS.showNotice('<?php echo esc_js( __( 'Webinar excluído.', 'plugin-leads-saas' ) ); ?>', 'success');
                loadWebinars();
            }
        });
    };
}());
</script>
