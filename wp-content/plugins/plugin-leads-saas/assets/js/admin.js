/**
 * LC CRM AUTOMATION — Admin JavaScript
 * Powers all admin UI interactions, AJAX calls, and flow builder.
 *
 * @package LC_CRM_Automation
 */

(function () {
    'use strict';

    /* ───────────────────────────────────
     * Global PLS namespace
     * ─────────────────────────────────── */
    window.PLS = window.PLS || {};

    var state = {
        currentPage: '',
        contacts: { items: [], total: 0, page: 1, perPage: 20, search: '', status: '' },
        automationId: 0,
        automationSteps: [],
    };

    /* ───────────────────────────────────
     * Init
     * ─────────────────────────────────── */
    document.addEventListener('DOMContentLoaded', function () {
        var app = document.getElementById('pls-app');
        if (!app) return;

        state.currentPage = app.dataset.page || 'pls-dashboard';

        initThemeToggle();
        initModals();
        loadPage();
    });

    /* ───────────────────────────────────
     * Theme Toggle (Dark / Light)
     * ─────────────────────────────────── */
    function initThemeToggle() {
        var btn = document.querySelector('.pls-theme-toggle');
        var app = document.getElementById('pls-app');
        if (!btn || !app) return;

        var isDark = localStorage.getItem('pls_dark') === '1';
        if (isDark) {
            app.classList.add('pls-dark');
        }

        btn.addEventListener('click', function () {
            app.classList.toggle('pls-dark');
            var dark = app.classList.contains('pls-dark');
            localStorage.setItem('pls_dark', dark ? '1' : '0');
        });
    }

    /* ───────────────────────────────────
     * Modal helpers
     * ─────────────────────────────────── */
    function initModals() {
        // Close on overlay click or close button.
        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('pls-modal-overlay') || e.target.classList.contains('pls-modal-close')) {
                var modal = e.target.closest('.pls-modal');
                if (modal) closeModal(modal);
            }
        });

        // Close on ESC key.
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' || e.keyCode === 27) {
                var openModals = document.querySelectorAll('.pls-modal[style*="flex"]');
                if (openModals.length) {
                    closeModal(openModals[openModals.length - 1]);
                }
            }
        });
    }

    function closeModal(el) {
        el.style.display = 'none';
        // Release body scroll if no other modals are open.
        var remaining = document.querySelectorAll('.pls-modal[style*="flex"]');
        if (!remaining.length) {
            document.body.classList.remove('pls-modal-open');
        }
    }

    PLS.openModal = function (id) {
        var el = document.getElementById(id);
        if (el) {
            el.style.display = 'flex';
            document.body.classList.add('pls-modal-open');
        }
    };

    PLS.closeModal = function (id) {
        var el = document.getElementById(id);
        if (el) closeModal(el);
    };

    /* ───────────────────────────────────
     * AJAX helper
     * ─────────────────────────────────── */
    function ajax(action, data, callback) {
        if (typeof pls === 'undefined') return;

        var formData = new FormData();
        formData.append('action', action);
        formData.append('nonce', pls.nonce);

        if (data) {
            Object.keys(data).forEach(function (key) {
                formData.append(key, data[key]);
            });
        }

        fetch(pls.ajax_url, { method: 'POST', body: formData })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                if (callback) callback(res);
            })
            .catch(function (err) {
                console.error("PLS Ajax error":', err);
                showToast('Erro de rede.', 'error');
            });
    }

    function restGet(endpoint, callback, errorCallback) {
        if (typeof pls === 'undefined') return;

        fetch(pls.rest_url + endpoint, {
            headers: { 'X-WP-Nonce': pls.rest_nonce },
        })
            .then(function (r) {
                return r.json().then(function (body) {
                    if (!r.ok) {
                        throw body;
                    }
                    return body;
                });
            })
            .then(callback)
            .catch(function (err) {
                console.error("PLS REST error":', err);
                if (errorCallback) {
                    errorCallback(err);
                }
            });
    }

    /* ───────────────────────────────────
     * Toast notifications
     * ─────────────────────────────────── */
    function showToast(message, type) {
        type = type || 'success';
        var toast = document.createElement('div');
        toast.className = 'pls-toast pls-toast-' + type;
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(function () {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s';
            setTimeout(function () { toast.remove(); }, 300);
        }, 3000);
    }

    PLS.showToast = showToast;
    PLS.showNotice = showToast; // Alias used by dynamically loaded page views.
    PLS.ajax = ajax;            // Expose ajax helper for page views.
    PLS.escHtml = esc;          // Expose HTML escaping for page views.

    /* ───────────────────────────────────
     * Copy to clipboard
     * ─────────────────────────────────── */
    PLS.copyToClipboard = function (text) {
        navigator.clipboard.writeText(text).then(function () {
            showToast('Copiado!', 'success');
        });
    };

    PLS.copyShortcode = function (btn) {
        var row = btn.closest('tr');
        var code = row ? row.querySelector('.pls-copyable') : null;
        if (code) {
            var text = code.getAttribute('data-copy') || code.textContent.trim();
            PLS.copyToClipboard(text);
        }
    };

    /* ───────────────────────────────────
     * Page Router
     * ─────────────────────────────────── */
    function loadPage() {
        switch (state.currentPage) {
            case 'pls-dashboard':
                loadDashboard();
                break;
            case 'pls-contacts':
                initContacts();
                break;
            case 'pls-lists':
                initLists();
                break;
            case 'pls-tags':
                initTags();
                break;
            case 'pls-automations':
                initAutomations();
                break;
            case 'pls-whatsapp':
                initWhatsApp();
                break;
            case 'pls-email':
                initEmail();
                break;
            case 'pls-settings':
                initSettings();
                break;
        }
    }

    /* ───────────────────────────────────
     * Dashboard
     * ─────────────────────────────────── */
    function loadDashboard() {
        ajax('pls_get_dashboard_stats', {}, function (res) {
            if (!res.success) return;
            var d = res.data;

            setText('stat-contacts', d.contacts_total);
            setText('stat-active', d.contacts_active);
            setText('stat-lists', d.lists_total);
            setText('stat-automations', d.automations_active);
            setText('stat-tags', d.tags_total);
            setText('stat-new7d', d.new_contacts_7d);

            if (d.queue_stats) {
                setText('q-pending', d.queue_stats.pending);
                setText('q-processing', d.queue_stats.processing);
                setText('q-sent', d.queue_stats.sent);
                setText('q-failed', d.queue_stats.failed);
            }

            // Email stats (7-day).
            if (d.email_stats) {
                setText('dash-email-sent', d.email_stats.sent);
                setText('dash-email-opened', d.email_stats.opened);
                setText('dash-email-clicked', d.email_stats.clicked);
                setText('dash-email-failed', d.email_stats.failed);
            }

            // Recent events
            var tbody = document.getElementById('recent-events-body');
            if (tbody && d.recent_events) {
                if (d.recent_events.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="3" class="pls-text-center">Nenhum evento ainda.</td></tr>';
                } else {
                    tbody.innerHTML = d.recent_events.map(function (ev) {
                        var name = (ev.first_name || '') + ' ' + (ev.last_name || '');
                        var contact = name.trim() || ev.email || 'ID #' + ev.contact_id;
                        return '<tr><td><span class="pls-badge pls-badge-primary">' + esc(ev.event_type) + '</span></td><td>' + esc(contact) + '</td><td>' + esc(ev.created_at) + '</td></tr>';
                    }).join('');
                }
            }
        });
    }

    /* ───────────────────────────────────
     * Contacts
     * ─────────────────────────────────── */
    function initContacts() {
        loadContacts();

        var search = document.getElementById('contacts-search');
        if (search) {
            var debounce;
            search.addEventListener('input', function () {
                clearTimeout(debounce);
                debounce = setTimeout(function () {
                    state.contacts.search = search.value;
                    state.contacts.page = 1;
                    loadContacts();
                }, 300);
            });
        }

        var statusFilter = document.getElementById('contacts-status-filter');
        if (statusFilter) {
            statusFilter.addEventListener('change', function () {
                state.contacts.status = statusFilter.value;
                state.contacts.page = 1;
                loadContacts();
            });
        }

        var addBtn = document.getElementById('btn-add-contact');
        if (addBtn) {
            addBtn.addEventListener('click', function () {
                document.getElementById('contact-id').value = '';
                document.getElementById('contact-email').value = '';
                document.getElementById('contact-fname').value = '';
                document.getElementById('contact-lname').value = '';
                document.getElementById('contact-phone').value = '';
                document.getElementById('contact-company').value = '';
                document.getElementById('contact-status').value = 'active';
                document.getElementById('contact-modal-title').textContent = 'Adicionar Contato';
                PLS.openModal('contact-modal');
            });
        }

        var saveBtn = document.getElementById('btn-save-contact');
        if (saveBtn) {
            saveBtn.addEventListener('click', function () {
                var data = {
                    id: document.getElementById('contact-id').value,
                    email: document.getElementById('contact-email').value,
                    first_name: document.getElementById('contact-fname').value,
                    last_name: document.getElementById('contact-lname').value,
                    phone: document.getElementById('contact-phone').value,
                    company: document.getElementById('contact-company').value,
                    status: document.getElementById('contact-status').value,
                };

                ajax('pls_save_contact', data, function (res) {
                    if (res.success) {
                        PLS.closeModal('contact-modal');
                        showToast('Contato salvo!');
                        loadContacts();
                    } else {
                        showToast(res.data.message || 'Erro ao salvar contato.', 'error');
                    }
                });
            });
        }
    }

    function loadContacts() {
        restGet(
            'contacts?search=' + encodeURIComponent(state.contacts.search) + '&status=' + state.contacts.status + '&page=' + state.contacts.page + '&per_page=' + state.contacts.perPage,
            function (data) {
                state.contacts.items = (data && Array.isArray(data.items)) ? data.items : [];
                state.contacts.total = (data && data.total) ? data.total : 0;
                renderContacts();
            },
            function () {
                var tbody = document.getElementById('contacts-tbody');
                if (tbody) {
                    tbody.innerHTML = '<tr><td colspan="8" class="pls-text-center">Erro ao carregar contatos. Verifique as permissões da API REST.</td></tr>';
                }
            }
        );
    }

    function renderContacts() {
        var tbody = document.getElementById('contacts-tbody');
        if (!tbody) return;

        if (state.contacts.items.length === 0) {
            tbody.innerHTML = '<tr><td colspan="8" class="pls-text-center">Nenhum contato encontrado.</td></tr>';
            return;
        }

        tbody.innerHTML = state.contacts.items.map(function (c) {
            var name = esc((c.first_name || '') + ' ' + (c.last_name || '')).trim() || '—';
            var statusClass = 'pls-status-' + (c.status || 'active');
            return '<tr>' +
                '<td><strong>' + name + '</strong></td>' +
                '<td>' + esc(c.email) + '</td>' +
                '<td>' + esc(c.phone || '—') + '</td>' +
                '<td><span class="' + statusClass + '">' + esc(c.status) + '</span></td>' +
                '<td>' + (c.lead_score || 0) + '</td>' +
                '<td>—</td>' +
                '<td>' + esc(c.created_at || '') + '</td>' +
                '<td>' +
                    '<button class="pls-btn pls-btn-sm" onclick="PLS.editContact(' + c.id + ')">Editar</button> ' +
                    '<button class="pls-btn pls-btn-sm pls-btn-danger" onclick="PLS.deleteContact(' + c.id + ')">Excluir</button>' +
                '</td></tr>';
        }).join('');

        renderPagination('contacts-pagination', state.contacts.total, state.contacts.perPage, state.contacts.page, function (page) {
            state.contacts.page = page;
            loadContacts();
        });
    }

    PLS.editContact = function (id) {
        restGet('contacts/' + id, function (c) {
            document.getElementById('contact-id').value = c.id;
            document.getElementById('contact-email').value = c.email || '';
            document.getElementById('contact-fname').value = c.first_name || '';
            document.getElementById('contact-lname').value = c.last_name || '';
            document.getElementById('contact-phone').value = c.phone || '';
            document.getElementById('contact-company').value = c.company || '';
            document.getElementById('contact-status').value = c.status || 'active';
            document.getElementById('contact-modal-title').textContent = 'Editar Contato';
            PLS.openModal('contact-modal');
        });
    };

    PLS.deleteContact = function (id) {
        if (!confirm('Excluir este contato?')) return;
        ajax('pls_delete_contact', { id: id }, function (res) {
            if (res.success) {
                showToast('Contato excluído.');
                loadContacts();
            }
        });
    };

    /* ───────────────────────────────────
     * Lists
     * ─────────────────────────────────── */
    function initLists() {
        initListIntegrationBar();
        loadLists();

        var addBtn = document.getElementById('btn-add-list');
        if (addBtn) {
            addBtn.addEventListener('click', function () {
                document.getElementById('list-id').value = '';
                document.getElementById('list-name').value = '';
                document.getElementById('list-description').value = '';
                document.getElementById('list-modal-title').textContent = 'Nova Lista';
                PLS.openModal('list-modal');
            });
        }

        var saveBtn = document.getElementById('btn-save-list');
        if (saveBtn) {
            saveBtn.addEventListener('click', function () {
                ajax('pls_save_list', {
                    id: document.getElementById('list-id').value,
                    name: document.getElementById('list-name').value,
                    description: document.getElementById('list-description').value,
                }, function (res) {
                    if (res.success) {
                        PLS.closeModal('list-modal');
                        showToast('Lista salva!');
                        loadLists();
                    }
                });
            });
        }
    }

    function loadLists() {
        restGet(
            'lists',
            function (lists) {
                var tbody = document.getElementById('lists-tbody');
                if (!tbody) return;

                var arr = Array.isArray(lists) ? lists : [];
                updateListIntegrationOptions(arr);

                if (arr.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="6" class="pls-text-center">Nenhuma lista ainda.</td></tr>';
                    return;
                }

                tbody.innerHTML = arr.map(function (l) {
                    var shortcode = '[pls_form list="' + l.id + '"]';
                    return '<tr>' +
                        '<td><strong>' + esc(l.name) + '</strong></td>' +
                        '<td>' + esc(l.description || '—') + '</td>' +
                        '<td>—</td>' +
                        '<td><code class="pls-code">' + esc(shortcode) + '</code></td>' +
                        '<td>' + esc(l.created_at) + '</td>' +
                        '<td>' +
                            '<button class="pls-btn pls-btn-sm" onclick="PLS.editList(' + l.id + ',\'' + escAttr(l.name) + '\',\'' + escAttr(l.description || '') + '\')">Editar</button> ' +
                            '<button class="pls-btn pls-btn-sm pls-btn-danger" onclick="PLS.deleteList(' + l.id + ')">Excluir</button>' +
                        '</td></tr>';
                }).join('');
            },
            function () {
                var tbody = document.getElementById('lists-tbody');
                if (tbody) {
                    tbody.innerHTML = '<tr><td colspan="6" class="pls-text-center">Erro ao carregar listas. Verifique as permissões da API REST.</td></tr>';
                }
            }
        );
    }

    PLS.editList = function (id, name, desc) {
        document.getElementById('list-id').value = id;
        document.getElementById('list-name').value = name;
        document.getElementById('list-description').value = desc;
        document.getElementById('list-modal-title').textContent = 'Editar Lista';
        PLS.openModal('list-modal');
    };

    PLS.deleteList = function (id) {
        if (!confirm('Excluir esta lista?')) return;
        ajax('pls_delete_list', { id: id }, function (res) {
            if (res.success) {
                showToast('Lista excluída.');
                loadLists();
            }
        });
    };

    function initListIntegrationBar() {
        var select = document.getElementById('list-tools-select');
        if (!select) return;

        ['list-form-title', 'list-form-button', 'list-form-class', 'list-tools-select'].forEach(function (id) {
            var el = document.getElementById(id);
            if (el) {
                el.addEventListener('input', updateListIntegrationPreview);
                el.addEventListener('change', updateListIntegrationPreview);
            }
        });

        updateListIntegrationPreview();
    }

    function updateListIntegrationOptions(lists) {
        var select = document.getElementById('list-tools-select');
        if (!select) return;

        var currentValue = select.value || '';
        var options = ['<option value="">Selecione uma lista</option>'].concat(
            (lists || []).map(function (list) {
                return '<option value="' + escAttr(String(list.id)) + '">' + esc(list.name) + ' (ID ' + esc(String(list.id)) + ')</option>';
            })
        );
        select.innerHTML = options.join('');

        if (currentValue && (lists || []).some(function (list) { return String(list.id) === currentValue; })) {
            select.value = currentValue;
        }

        updateListIntegrationPreview();
    }

    function updateListIntegrationPreview() {
        var select = document.getElementById('list-tools-select');
        var title = document.getElementById('list-form-title');
        var button = document.getElementById('list-form-button');
        var cssClass = document.getElementById('list-form-class');
        var webhook = document.getElementById('list-webhook-url');
        var shortcode = document.getElementById('list-form-shortcode');
        if (!select || !webhook || !shortcode) return;

        var listId = select.value || '0';
        var baseWebhook = (typeof pls !== 'undefined' && pls.rest_url)
            ? pls.rest_url.replace(/\/$/, '') + '/webhook'
            : webhook.value.split('?')[0];

        webhook.value = baseWebhook + '?list_id=' + encodeURIComponent(listId);

        var parts = ['[pls_form', 'list="' + listId + '"'];
        if (title && title.value.trim()) {
            parts.push('title="' + escAttr(title.value.trim()) + '"');
        }
        if (button && button.value.trim()) {
            parts.push('button_text="' + escAttr(button.value.trim()) + '"');
        }
        if (cssClass && cssClass.value.trim()) {
            parts.push('class="' + escAttr(cssClass.value.trim()) + '"');
        }
        shortcode.value = parts.join(' ') + ']';
    }

    /* ───────────────────────────────────
     * Tags
     * ─────────────────────────────────── */
    function initTags() {
        loadTags();

        var addBtn = document.getElementById('btn-add-tag');
        if (addBtn) {
            addBtn.addEventListener('click', function () {
                document.getElementById('tag-id').value = '';
                document.getElementById('tag-name').value = '';
                document.getElementById('tag-color').value = '#6366f1';
                document.getElementById('tag-modal-title').textContent = 'Nova Tag';
                PLS.openModal('tag-modal');
            });
        }

        var saveBtn = document.getElementById('btn-save-tag');
        if (saveBtn) {
            saveBtn.addEventListener('click', function () {
                ajax('pls_save_tag', {
                    id: document.getElementById('tag-id').value,
                    name: document.getElementById('tag-name').value,
                    color: document.getElementById('tag-color').value,
                }, function (res) {
                    if (res.success) {
                        PLS.closeModal('tag-modal');
                        showToast('Tag salva!');
                        loadTags();
                    }
                });
            });
        }
    }

    function loadTags() {
        restGet(
            'tags',
            function (tags) {
                var grid = document.getElementById('tags-grid');
                if (!grid) return;

                var arr = Array.isArray(tags) ? tags : [];

                if (arr.length === 0) {
                    grid.innerHTML = '<p class="pls-text-center">Nenhuma tag ainda.</p>';
                    return;
                }

                grid.innerHTML = arr.map(function (t) {
                    return '<div class="pls-tag-card">' +
                        '<div class="pls-tag-card-info">' +
                            '<span class="pls-tag-dot" style="background:' + esc(t.color) + '"></span>' +
                            '<div>' +
                                '<div class="pls-tag-card-name">' + esc(t.name) + '</div>' +
                            '</div>' +
                        '</div>' +
                        '<div>' +
                            '<button class="pls-btn pls-btn-sm" onclick="PLS.editTag(' + t.id + ',\'' + escAttr(t.name) + '\',\'' + escAttr(t.color) + '\')">Editar</button> ' +
                            '<button class="pls-btn pls-btn-sm pls-btn-danger" onclick="PLS.deleteTag(' + t.id + ')">×</button>' +
                        '</div>' +
                    '</div>';
                }).join('');
            },
            function () {
                var grid = document.getElementById('tags-grid');
                if (grid) {
                    grid.innerHTML = '<p class="pls-text-center">Erro ao carregar tags.</p>';
                }
            }
        );
    }

    PLS.editTag = function (id, name, color) {
        document.getElementById('tag-id').value = id;
        document.getElementById('tag-name').value = name;
        document.getElementById('tag-color').value = color;
        document.getElementById('tag-modal-title').textContent = 'Editar Tag';
        PLS.openModal('tag-modal');
    };

    PLS.deleteTag = function (id) {
        if (!confirm('Excluir esta tag?')) return;
        ajax('pls_delete_tag', { id: id }, function (res) {
            if (res.success) {
                showToast('Tag excluída.');
                loadTags();
            }
        });
    };

    /* ───────────────────────────────────
     * Automations
     * ─────────────────────────────────── */
    function initAutomations() {
        loadAutomations();
        initFlowBuilder();

        var addBtn = document.getElementById('btn-add-automation');
        if (addBtn) {
            addBtn.addEventListener('click', function () {
                state.automationId = 0;
                state.automationSteps = [];
                document.getElementById('automation-name').value = '';
                document.getElementById('automation-trigger').value = 'contact_created';
                document.getElementById('automation-status').value = 'draft';
                renderFlowNodes();
                showBuilder();
            });
        }

        var backBtn = document.getElementById('btn-back-automations');
        if (backBtn) {
            backBtn.addEventListener('click', function () {
                hideBuilder();
                loadAutomations();
            });
        }

        var saveAutoBtn = document.getElementById('btn-save-automation');
        if (saveAutoBtn) {
            saveAutoBtn.addEventListener('click', saveAutomation);
        }
    }

    function loadAutomations() {
        restGet(
            'automations',
            function (automations) {
                var tbody = document.getElementById('automations-tbody');
                if (!tbody) return;

                var arr = Array.isArray(automations) ? automations : [];

                if (arr.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="5" class="pls-text-center">Nenhuma automação ainda.</td></tr>';
                    return;
                }

                tbody.innerHTML = arr.map(function (a) {
                    var statusClass = 'pls-status-' + (a.status || 'draft');
                    return '<tr>' +
                        '<td><strong>' + esc(a.name) + '</strong></td>' +
                        '<td><span class="pls-badge pls-badge-info">' + esc(a.trigger_type) + '</span></td>' +
                        '<td><span class="' + statusClass + '">' + esc(a.status) + '</span></td>' +
                        '<td>' + esc(a.created_at) + '</td>' +
                        '<td>' +
                            '<button class="pls-btn pls-btn-sm" onclick="PLS.editAutomation(' + a.id + ')">Editar</button> ' +
                            '<button class="pls-btn pls-btn-sm pls-btn-danger" onclick="PLS.deleteAutomation(' + a.id + ')">Excluir</button>' +
                        '</td></tr>';
                }).join('');
            },
            function () {
                var tbody = document.getElementById('automations-tbody');
                if (tbody) {
                    tbody.innerHTML = '<tr><td colspan="5" class="pls-text-center">Erro ao carregar automações.</td></tr>';
                }
            }
        );
    }

    function showBuilder() {
        document.getElementById('automations-list-view').style.display = 'none';
        document.getElementById('automation-builder').style.display = 'block';
    }

    function hideBuilder() {
        document.getElementById('automations-list-view').style.display = 'block';
        document.getElementById('automation-builder').style.display = 'none';
    }

    PLS.editAutomation = function (id) {
        restGet('automations', function (automations) {
            var arr = Array.isArray(automations) ? automations : [];
            var auto = arr.find(function (a) { return parseInt(a.id) === id; });
            if (!auto) return;

            state.automationId = id;
            document.getElementById('automation-name').value = auto.name || '';
            document.getElementById('automation-trigger').value = auto.trigger_type || 'contact_created';
            document.getElementById('automation-status').value = auto.status || 'draft';

            restGet('automations/' + id + '/steps', function (steps) {
                state.automationSteps = (Array.isArray(steps) ? steps : []).map(function (s) {
                    return {
                        step_type: s.step_type,
                        action_type: s.action_type || '',
                        config: s.config ? (typeof s.config === 'string' ? JSON.parse(s.config) : s.config) : {},
                        step_order: parseInt(s.step_order) || 0,
                        parent_id: parseInt(s.parent_id) || 0,
                        branch_label: s.branch_label || '',
                    };
                });
                renderFlowNodes();
                showBuilder();
            });
        });
    };

    PLS.deleteAutomation = function (id) {
        if (!confirm('Excluir esta automação?')) return;
        ajax('pls_delete_automation', { id: id }, function (res) {
            if (res.success) {
                showToast('Automação excluída.');
                loadAutomations();
            }
        });
    };

    function saveAutomation() {
        var data = {
            id: state.automationId || 0,
            name: document.getElementById('automation-name').value,
            trigger_type: document.getElementById('automation-trigger').value,
            status: document.getElementById('automation-status').value,
            steps: JSON.stringify(state.automationSteps),
        };

        ajax('pls_save_automation', data, function (res) {
            if (res.success) {
                state.automationId = res.data.id;
                showToast('Automação salva!');
            } else {
                showToast('Erro ao salvar.', 'error');
            }
        });
    }

    /* ───────────────────────────────────
     * Flow Builder UI
     * ─────────────────────────────────── */
    function initFlowBuilder() {
        var addStepBtn = document.getElementById('btn-add-step');
        if (addStepBtn) {
            addStepBtn.addEventListener('click', function () {
                document.getElementById('step-type').value = 'action';
                toggleStepFields('action');
                PLS.openModal('step-modal');
            });
        }

        var stepType = document.getElementById('step-type');
        if (stepType) {
            stepType.addEventListener('change', function () {
                toggleStepFields(stepType.value);
            });
        }

        var actionType = document.getElementById('action-type');
        if (actionType) {
            actionType.addEventListener('change', function () {
                renderActionConfig(actionType.value);
            });
        }

        var conditionType = document.getElementById('condition-type');
        if (conditionType) {
            conditionType.addEventListener('change', function () {
                updateConditionFields(conditionType.value);
            });
        }

        var confirmBtn = document.getElementById('btn-confirm-step');
        if (confirmBtn) {
            confirmBtn.addEventListener('click', addStep);
        }
    }

    function updateConditionFields(condType) {
        var fieldRow  = document.getElementById('condition-field-row');
        var valueLabel = document.getElementById('condition-value-label');
        var emailHours = ['email_opened', 'email_clicked', 'email_not_opened'];

        if (fieldRow) {
            fieldRow.style.display = (condType === 'field_equals') ? 'block' : 'none';
        }
        if (valueLabel) {
            if (emailHours.indexOf(condType) !== -1) {
                valueLabel.textContent = 'Número de Horas';
                var valueInput = document.getElementById('condition-value');
                if (valueInput) { valueInput.placeholder = '24'; valueInput.type = 'number'; }
            } else {
                valueLabel.textContent = 'Valor';
                var valueInput = document.getElementById('condition-value'); // eslint-disable-line no-redeclare
                if (valueInput) { valueInput.placeholder = ''; valueInput.type = 'text'; }
            }
        }
    }

    function toggleStepFields(type) {
        document.getElementById('step-condition-fields').style.display = type === 'condition' ? 'block' : 'none';
        document.getElementById('step-action-fields').style.display = type === 'action' ? 'block' : 'none';
        document.getElementById('step-delay-fields').style.display = type === 'delay' ? 'block' : 'none';

        if (type === 'action') {
            renderActionConfig(document.getElementById('action-type').value);
        }
    }

    function renderActionConfig(actionType) {
        var container = document.getElementById('action-config-fields');
        if (!container) return;

        var html = '';

        switch (actionType) {
            case 'add_tag':
            case 'remove_tag':
                html = '<div class="pls-field"><label>Nome da Tag</label><input type="text" id="action-tag-name" class="pls-input" placeholder="e.g. lead" /></div>';
                break;
            case 'subscribe_list':
            case 'unsubscribe_list':
                html = '<div class="pls-field"><label>ID da Lista</label><input type="number" id="action-list-id" class="pls-input" placeholder="1" /></div>';
                break;
            case 'send_email':
                html = '<div class="pls-field">' +
                           '<label>Modelo de Email (opcional)</label>' +
                           '<select id="action-email-template" class="pls-select"><option value="0">— Sem modelo (usar corpo abaixo) —</option></select>' +
                       '</div>' +
                       '<div class="pls-field"><label>Assunto</label><input type="text" id="action-email-subject" class="pls-input" placeholder="Olá {{first_name}}!" /></div>' +
                       '<div class="pls-field"><label>Corpo (HTML) — ignorado se modelo selecionado</label><textarea id="action-email-body" class="pls-input" rows="4"></textarea></div>';
                // Load templates into the select.
                ajax('pls_list_email_templates', {}, function (res) {
                    var sel = document.getElementById('action-email-template');
                    if (sel && res.success && res.data.items) {
                        res.data.items.forEach(function (t) {
                            var opt = document.createElement('option');
                            opt.value = t.id;
                            opt.textContent = t.name + ' — ' + t.subject;
                            sel.appendChild(opt);
                        });
                    }
                });
                break;
            case 'send_whatsapp':
                html = '<div class="pls-field"><label>Mensagem</label><textarea id="action-wa-message" class="pls-input" rows="3"></textarea></div>';
                break;
            case 'update_field':
                html = '<div class="pls-field"><label>Campo</label><input type="text" id="action-field-name" class="pls-input" placeholder="first_name" /></div>' +
                       '<div class="pls-field"><label>Valor</label><input type="text" id="action-field-value" class="pls-input" /></div>';
                break;
            case 'update_score':
                html = '<div class="pls-field"><label>Delta de Pontuação (+/-)</label><input type="number" id="action-score-delta" class="pls-input" value="5" /></div>';
                break;
            case 'webhook':
                html = '<div class="pls-field"><label>URL</label><input type="url" id="action-webhook-url" class="pls-input" placeholder="https://..." /></div>';
                break;
        }

        container.innerHTML = html;
    }

    function addStep() {
        var type = document.getElementById('step-type').value;
        var step = {
            step_type: type,
            action_type: '',
            config: {},
            step_order: state.automationSteps.length,
            parent_id: 0,
            branch_label: '',
        };

        if (type === 'condition') {
            var condType = document.getElementById('condition-type').value;
            step.config = {
                condition_type: condType,
                value: document.getElementById('condition-value').value,
            };
            if (condType === 'field_equals') {
                step.config.field = document.getElementById('condition-field') ? document.getElementById('condition-field').value : '';
            }
        } else if (type === 'action') {
            var actionType = document.getElementById('action-type').value;
            step.action_type = actionType;
            step.config = { action_type: actionType };

            switch (actionType) {
                case 'add_tag':
                case 'remove_tag':
                    step.config.tag_name = getVal('action-tag-name');
                    break;
                case 'subscribe_list':
                case 'unsubscribe_list':
                    step.config.list_id = getVal('action-list-id');
                    break;
                case 'send_email':
                    step.config.template_id = parseInt(getVal('action-email-template')) || 0;
                    step.config.subject = getVal('action-email-subject');
                    step.config.body = getVal('action-email-body');
                    break;
                case 'send_whatsapp':
                    step.config.message = getVal('action-wa-message');
                    break;
                case 'update_field':
                    step.config.field = getVal('action-field-name');
                    step.config.value = getVal('action-field-value');
                    break;
                case 'update_score':
                    step.config.score_delta = getVal('action-score-delta');
                    break;
                case 'webhook':
                    step.config.url = getVal('action-webhook-url');
                    break;
            }
        } else if (type === 'delay') {
            step.config = {
                amount: document.getElementById('delay-amount').value,
                unit: document.getElementById('delay-unit').value,
            };
        }

        state.automationSteps.push(step);
        renderFlowNodes();
        PLS.closeModal('step-modal');
    }

    function renderFlowNodes() {
        var container = document.getElementById('flow-nodes');
        if (!container) return;

        if (state.automationSteps.length === 0) {
            container.innerHTML = '<p class="pls-text-center" style="color:var(--pls-text-secondary);padding:40px;">Nenhuma etapa ainda. Clique em "+ Adicionar Etapa" para construir seu fluxo.</p>';
            return;
        }

        container.innerHTML = state.automationSteps.map(function (step, index) {
            var nodeClass = 'pls-node-' + step.step_type;
            var title = getStepTitle(step);
            var desc = getStepDescription(step);
            var typeLabel = step.step_type.toUpperCase();
            var icons = { trigger: '🟢', condition: '🔀', action: '⚡', delay: '⏱', branch: '🔀' };
            var icon = icons[step.step_type] || '📦';

            var connector = index < state.automationSteps.length - 1 ? '<div class="pls-flow-connector"></div>' : '';

            return '<div class="pls-flow-node ' + nodeClass + '" data-index="' + index + '">' +
                '<button class="pls-flow-node-remove" onclick="PLS.removeStep(' + index + ')">×</button>' +
                '<div class="pls-flow-node-header">' +
                    '<span class="pls-flow-node-type">' + icon + ' ' + typeLabel + '</span>' +
                '</div>' +
                '<div class="pls-flow-node-title">' + esc(title) + '</div>' +
                '<div class="pls-flow-node-desc">' + esc(desc) + '</div>' +
            '</div>' + connector;
        }).join('');
    }

    PLS.removeStep = function (index) {
        state.automationSteps.splice(index, 1);
        // Re-order.
        state.automationSteps.forEach(function (s, i) { s.step_order = i; });
        renderFlowNodes();
    };

    function getStepTitle(step) {
        switch (step.step_type) {
            case 'condition':
                return (step.config.condition_type || 'condition').replace(/_/g, ' ');
            case 'action':
                return (step.action_type || step.config.action_type || 'action').replace(/_/g, ' ');
            case 'delay':
                return 'Aguardar ' + (step.config.amount || '?') + ' ' + (step.config.unit || 'horas');
            default:
                return step.step_type;
        }
    }

    function getStepDescription(step) {
        var c = step.config || {};
        switch (step.step_type) {
            case 'condition':
                return (c.condition_type || '') + ' = ' + (c.value || '');
            case 'action':
                if (c.tag_name) return 'Tag: ' + c.tag_name;
                if (c.list_id) return 'Lista: ' + c.list_id;
                if (c.template_id && parseInt(c.template_id) > 0) return 'Modelo #' + c.template_id + (c.subject ? ' — ' + c.subject : '');
                if (c.subject) return 'Assunto: ' + c.subject;
                if (c.message) return c.message.substring(0, 50);
                if (c.url) return c.url;
                return '';
            case 'delay':
                return '';
            default:
                return '';
        }
    }

    /* ───────────────────────────────────
     * WhatsApp Page
     * ─────────────────────────────────── */
    function initWhatsApp() {
        restGet('stats', function () {}); // Trigger load.

        // Load recent messages.
        loadMessages('whatsapp', 'whatsapp-tbody');

        var form = document.getElementById('test-whatsapp-form');
        if (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                ajax('pls_send_test_whatsapp', {
                    phone: document.getElementById('test-wa-phone').value,
                    message: document.getElementById('test-wa-message').value,
                }, function (res) {
                    showToast(res.success ? 'Mensagem de teste enviada!' : 'Falha ao enviar.', res.success ? 'success' : 'error');
                });
            });
        }
    }

    /* ───────────────────────────────────
     * Email Page
     * ─────────────────────────────────── */
    function initEmail() {
        // Tab navigation.
        initTabNavigation();

        // Load email log.
        loadMessages('email', 'email-tbody');

        // Load templates.
        loadEmailTemplates();

        // Load email stats.
        loadEmailStats();

        // Test send form.
        var form = document.getElementById('test-email-form');
        if (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                ajax('pls_send_test_email', {
                    to: document.getElementById('test-email-to').value,
                    subject: document.getElementById('test-email-subject').value,
                    body: document.getElementById('test-email-body').value,
                }, function (res) {
                    showToast(res.success ? 'Email de teste enviado!' : 'Falha ao enviar.', res.success ? 'success' : 'error');
                });
            });
        }

        // New template button.
        var addBtn = document.getElementById('btn-add-email-template');
        if (addBtn) {
            addBtn.addEventListener('click', function () {
                openEmailTemplateModal(null);
            });
        }

        // Save template button.
        var saveBtn = document.getElementById('btn-save-email-template');
        if (saveBtn) {
            saveBtn.addEventListener('click', saveEmailTemplate);
        }

        // Preview button.
        var previewBtn = document.getElementById('btn-preview-template');
        if (previewBtn) {
            previewBtn.addEventListener('click', function () {
                var body = document.getElementById('tpl-body').value;
                var preview = document.getElementById('tpl-preview');
                if (preview) {
                    preview.innerHTML = body;
                    preview.style.display = preview.style.display === 'none' ? 'block' : 'none';
                }
            });
        }
    }

    /**
     * Simple tab navigation — works for any page that has .pls-tabs / .pls-tab-panel.
     */
    function initTabNavigation() {
        document.querySelectorAll('.pls-tab-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var target = btn.dataset.tab;
                document.querySelectorAll('.pls-tab-btn').forEach(function (b) { b.classList.remove('active'); });
                document.querySelectorAll('.pls-tab-panel').forEach(function (p) { p.style.display = 'none'; p.classList.remove('active'); });
                btn.classList.add('active');
                var panel = document.getElementById('tab-' + target);
                if (panel) { panel.style.display = 'block'; panel.classList.add('active'); }
            });
        });
    }

    function loadEmailTemplates() {
        var tbody = document.getElementById('email-templates-tbody');
        if (!tbody) return;

        ajax('pls_list_email_templates', {}, function (res) {
            if (!res.success || !res.data.items) {
                tbody.innerHTML = '<tr><td colspan="5" class="pls-text-center">Nenhum modelo encontrado.</td></tr>';
                return;
            }
            var items = res.data.items;
            if (!items.length) {
                tbody.innerHTML = '<tr><td colspan="5" class="pls-text-center">Nenhum modelo ainda. Clique em "+ Novo Modelo" para começar.</td></tr>';
                return;
            }
            tbody.innerHTML = items.map(function (t) {
                var badge = t.status === 'active' ? 'pls-badge-success' : 'pls-badge-warning';
                var statusLabel = t.status === 'active' ? 'Ativo' : 'Rascunho';
                return '<tr>' +
                    '<td>' + esc(t.name) + '</td>' +
                    '<td>' + esc(t.subject) + '</td>' +
                    '<td><span class="pls-badge ' + badge + '">' + statusLabel + '</span></td>' +
                    '<td>' + esc(t.created_at || '') + '</td>' +
                    '<td>' +
                        '<button class="pls-btn pls-btn-sm" onclick="PLS.editEmailTemplate(' + t.id + ')">Editar</button> ' +
                        '<button class="pls-btn pls-btn-sm pls-btn-danger" onclick="PLS.deleteEmailTemplate(' + t.id + ')">Excluir</button>' +
                    '</td>' +
                '</tr>';
            }).join('');
        });
    }

    function loadEmailStats() {
        ajax('pls_get_dashboard_stats', {}, function (res) {
            if (!res.success || !res.data.email_stats) return;
            var s = res.data.email_stats;
            setText('estat-sent', s.sent);
            setText('estat-opened', s.opened);
            setText('estat-clicked', s.clicked);
            setText('estat-failed', s.failed);
            setText('estat-unsub', s.unsubscribed);
        });
    }

    function openEmailTemplateModal(template) {
        document.getElementById('tpl-id').value = template ? template.id : '0';
        document.getElementById('tpl-name').value = template ? template.name : '';
        document.getElementById('tpl-subject').value = template ? template.subject : '';
        document.getElementById('tpl-body').value = template ? template.body : '';
        document.getElementById('tpl-status').value = template ? template.status : 'draft';
        document.getElementById('email-template-modal-title').textContent = template ? 'Editar Modelo' : 'Novo Modelo de Email';
        var preview = document.getElementById('tpl-preview');
        if (preview) { preview.style.display = 'none'; preview.innerHTML = ''; }
        PLS.openModal('email-template-modal');
    }

    function saveEmailTemplate() {
        var id      = document.getElementById('tpl-id').value;
        var name    = document.getElementById('tpl-name').value.trim();
        var subject = document.getElementById('tpl-subject').value.trim();
        var body    = document.getElementById('tpl-body').value.trim();
        var status  = document.getElementById('tpl-status').value;

        if (!name) { showToast('Informe o nome do modelo.', 'error'); return; }
        if (!subject) { showToast('Informe o assunto.', 'error'); return; }

        ajax('pls_save_email_template', { id: id, name: name, subject: subject, body: body, status: status }, function (res) {
            if (res.success) {
                showToast('Modelo salvo!');
                PLS.closeModal('email-template-modal');
                loadEmailTemplates();
            } else {
                showToast('Erro ao salvar modelo.', 'error');
            }
        });
    }

    PLS.editEmailTemplate = function (id) {
        ajax('pls_get_email_template', { id: id }, function (res) {
            if (res.success && res.data.template) {
                openEmailTemplateModal(res.data.template);
            }
        });
    };

    PLS.deleteEmailTemplate = function (id) {
        if (!confirm('Excluir este modelo de email?')) return;
        ajax('pls_delete_email_template', { id: id }, function (res) {
            if (res.success) {
                showToast('Modelo excluído.');
                loadEmailTemplates();
            }
        });
    };

    /**
     * Insert a merge variable at the cursor position in a textarea.
     */
    PLS.insertVar = function (textareaId, variable) {
        var el = document.getElementById(textareaId);
        if (!el) return;
        var start = el.selectionStart;
        var end   = el.selectionEnd;
        el.value = el.value.substring(0, start) + variable + el.value.substring(end);
        el.selectionStart = el.selectionEnd = start + variable.length;
        el.focus();
    };

    function loadMessages(channel, tbodyId) {
        var tbody = document.getElementById(tbodyId);
        if (!tbody) return;
        tbody.innerHTML = '<tr><td colspan="5" class="pls-text-center">Carregando...</td></tr>';

        restGet('email-stats', function () {}); // Pre-fetch to let the browser cache the endpoint.

        // Fetch recent messages via AJAX (pass channel as filter).
        ajax('pls_get_dashboard_stats', {}, function (res) {
            if (!res.success) return;
            // We use queue stats for the summary; detailed log shows a placeholder for now.
            tbody.innerHTML = '<tr><td colspan="5" class="pls-text-center pls-text-muted">Histórico detalhado disponível via REST: <code>' +
                (typeof pls !== 'undefined' ? pls.rest_url : '') + 'email-stats</code></td></tr>';
        });
    }

    /* ───────────────────────────────────
     * Settings
     * ─────────────────────────────────── */
    function initSettings() {
        var form = document.getElementById('settings-form');
        if (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                var formData = {};
                var inputs = form.querySelectorAll('input, select, textarea');
                inputs.forEach(function (el) {
                    if (el.type === 'checkbox') {
                        if (el.checked) formData[el.name] = el.value;
                    } else if (el.name) {
                        formData[el.name] = el.value;
                    }
                });

                ajax('pls_save_settings', formData, function (res) {
                    if (res.success) {
                        showToast(res.data.message || 'Configurações salvas!');
                    }
                });
            });
        }
    }

    /* ───────────────────────────────────
     * Pagination helper
     * ─────────────────────────────────── */
    function renderPagination(containerId, total, perPage, currentPage, onPageChange) {
        var container = document.getElementById(containerId);
        if (!container) return;

        var totalPages = Math.ceil(total / perPage);
        if (totalPages <= 1) {
            container.innerHTML = '';
            return;
        }

        var html = '';
        if (currentPage > 1) {
            html += '<button onclick="this._cb()">← Anterior</button>';
        }

        var start = Math.max(1, currentPage - 2);
        var end = Math.min(totalPages, currentPage + 2);

        for (var i = start; i <= end; i++) {
            var active = i === currentPage ? ' active' : '';
            html += '<button class="' + active + '" data-page="' + i + '">' + i + '</button>';
        }

        if (currentPage < totalPages) {
            html += '<button data-page="' + (currentPage + 1) + '">Próximo →</button>';
        }

        container.innerHTML = html;

        container.querySelectorAll('button').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var page = parseInt(btn.dataset.page);
                if (page) onPageChange(page);
            });
        });
    }

    /* ───────────────────────────────────
     * Utility
     * ─────────────────────────────────── */
    function esc(str) {
        if (!str) return '';
        var div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    function escAttr(str) {
        return (str || '').replace(/'/g, "\\'").replace(/"/g, '\\"');
    }

    function setText(id, val) {
        var el = document.getElementById(id);
        if (el) el.textContent = val !== undefined && val !== null ? val : '—';
    }

    function getVal(id) {
        var el = document.getElementById(id);
        return el ? el.value : '';
    }

})();
