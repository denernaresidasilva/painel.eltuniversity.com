/**
 * Roket CRM Automation Pro — Admin JavaScript
 * Powers all admin UI interactions, AJAX calls, and flow builder.
 *
 * @package Roket_CRM_Automation_Pro
 */

(function () {
    'use strict';

    /* ───────────────────────────────────
     * Global WPLA namespace
     * ─────────────────────────────────── */
    window.WPLA = window.WPLA || {};

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
        var app = document.getElementById('wpla-app');
        if (!app) return;

        state.currentPage = app.dataset.page || 'wpla-dashboard';

        initThemeToggle();
        initSidebarToggle();
        initModals();
        loadPage();
    });

    /* ───────────────────────────────────
     * Theme Toggle (Dark / Light)
     * ─────────────────────────────────── */
    function initThemeToggle() {
        var btn = document.querySelector('.wpla-theme-toggle');
        var app = document.getElementById('wpla-app');
        if (!btn || !app) return;

        var isDark = localStorage.getItem('wpla_dark') === '1';
        if (isDark) {
            app.classList.add('wpla-dark');
        }

        btn.addEventListener('click', function () {
            app.classList.toggle('wpla-dark');
            var dark = app.classList.contains('wpla-dark');
            localStorage.setItem('wpla_dark', dark ? '1' : '0');
        });
    }

    /* ───────────────────────────────────
     * Sidebar Toggle
     * ─────────────────────────────────── */
    function initSidebarToggle() {
        var btn = document.querySelector('.wpla-sidebar-toggle');
        var sidebar = document.querySelector('.wpla-sidebar');
        var backdrop = document.getElementById('wpla-sidebar-backdrop');
        if (!btn || !sidebar) return;

        var isMobile = function () {
            return window.innerWidth <= 1024;
        };

        // Restore desktop collapsed state
        var wasCollapsed = localStorage.getItem('wpla_sidebar_collapsed') === '1';
        if (wasCollapsed && !isMobile()) {
            sidebar.classList.add('wpla-collapsed');
        }

        btn.addEventListener('click', function () {
            if (isMobile()) {
                // Mobile: slide in/out full sidebar
                var isOpen = sidebar.classList.contains('wpla-open');
                if (isOpen) {
                    sidebar.classList.remove('wpla-open');
                    if (backdrop) backdrop.classList.remove('wpla-active');
                } else {
                    sidebar.classList.add('wpla-open');
                    if (backdrop) backdrop.classList.add('wpla-active');
                }
            } else {
                // Desktop: toggle collapsed icon-only mode
                sidebar.classList.toggle('wpla-collapsed');
                localStorage.setItem('wpla_sidebar_collapsed', sidebar.classList.contains('wpla-collapsed') ? '1' : '0');
            }
        });

        // Close sidebar when clicking backdrop on mobile
        if (backdrop) {
            backdrop.addEventListener('click', function () {
                sidebar.classList.remove('wpla-open');
                backdrop.classList.remove('wpla-active');
            });
        }

        // Handle window resize: reset mobile state if switching to desktop
        var resizeTimer;
        window.addEventListener('resize', function () {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function () {
                if (!isMobile()) {
                    sidebar.classList.remove('wpla-open');
                    if (backdrop) backdrop.classList.remove('wpla-active');
                }
            }, 150);
        });
    }

    /* ───────────────────────────────────
     * Modal helpers
     * ─────────────────────────────────── */
    function initModals() {
        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('wpla-modal-overlay') || e.target.classList.contains('wpla-modal-close')) {
                var modal = e.target.closest('.wpla-modal');
                if (modal) modal.style.display = 'none';
            }
        });
    }

    WPLA.openModal = function (id) {
        var el = document.getElementById(id);
        if (el) el.style.display = 'flex';
    };

    WPLA.closeModal = function (id) {
        var el = document.getElementById(id);
        if (el) el.style.display = 'none';
    };

    /* ───────────────────────────────────
     * AJAX helper
     * ─────────────────────────────────── */
    function ajax(action, data, callback) {
        if (typeof wpla === 'undefined') return;

        var formData = new FormData();
        formData.append('action', action);
        formData.append('nonce', wpla.nonce);

        if (data) {
            Object.keys(data).forEach(function (key) {
                formData.append(key, data[key]);
            });
        }

        fetch(wpla.ajax_url, { method: 'POST', body: formData })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                if (callback) callback(res);
            })
            .catch(function (err) {
                console.error('WPLA Ajax error:', err);
                showToast('Network error.', 'error');
            });
    }

    function restGet(endpoint, callback) {
        if (typeof wpla === 'undefined') return;

        fetch(wpla.rest_url + endpoint, {
            headers: { 'X-WP-Nonce': wpla.rest_nonce },
        })
            .then(function (r) { return r.json(); })
            .then(callback)
            .catch(function (err) {
                console.error('WPLA REST error:', err);
            });
    }

    /* ───────────────────────────────────
     * Toast notifications
     * ─────────────────────────────────── */
    function showToast(message, type) {
        type = type || 'success';
        var toast = document.createElement('div');
        toast.className = 'wpla-toast wpla-toast-' + type;
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(function () {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s';
            setTimeout(function () { toast.remove(); }, 300);
        }, 3000);
    }

    WPLA.showToast = showToast;

    /* ───────────────────────────────────
     * Copy to clipboard
     * ─────────────────────────────────── */
    WPLA.copyToClipboard = function (text) {
        navigator.clipboard.writeText(text).then(function () {
            showToast('Copied!', 'success');
        });
    };

    WPLA.copyShortcode = function (btn) {
        var row = btn.closest('tr');
        var code = row ? row.querySelector('.wpla-copyable') : null;
        if (code) {
            var text = code.getAttribute('data-copy') || code.textContent.trim();
            WPLA.copyToClipboard(text);
        }
    };

    /* ───────────────────────────────────
     * Page Router
     * ─────────────────────────────────── */
    function loadPage() {
        switch (state.currentPage) {
            case 'wpla-dashboard':
                loadDashboard();
                break;
            case 'wpla-contacts':
                initContacts();
                break;
            case 'wpla-lists':
                initLists();
                break;
            case 'wpla-tags':
                initTags();
                break;
            case 'wpla-automations':
                initAutomations();
                break;
            case 'wpla-whatsapp':
                initWhatsApp();
                break;
            case 'wpla-email':
                initEmail();
                break;
            case 'wpla-settings':
                initSettings();
                break;
        }
    }

    /* ───────────────────────────────────
     * Dashboard
     * ─────────────────────────────────── */
    function loadDashboard() {
        ajax('wpla_get_dashboard_stats', {}, function (res) {
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

            // Recent events
            var tbody = document.getElementById('recent-events-body');
            if (tbody && d.recent_events) {
                if (d.recent_events.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="3" class="wpla-text-center">No events yet.</td></tr>';
                } else {
                    tbody.innerHTML = d.recent_events.map(function (ev) {
                        var name = (ev.first_name || '') + ' ' + (ev.last_name || '');
                        var contact = name.trim() || ev.email || 'ID #' + ev.contact_id;
                        return '<tr><td><span class="wpla-badge wpla-badge-primary">' + esc(ev.event_type) + '</span></td><td>' + esc(contact) + '</td><td>' + esc(ev.created_at) + '</td></tr>';
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
                document.getElementById('contact-modal-title').textContent = 'Add Contact';
                WPLA.openModal('contact-modal');
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

                ajax('wpla_save_contact', data, function (res) {
                    if (res.success) {
                        WPLA.closeModal('contact-modal');
                        showToast('Contact saved!');
                        loadContacts();
                    } else {
                        showToast(res.data.message || 'Error saving contact.', 'error');
                    }
                });
            });
        }
    }

    function loadContacts() {
        restGet('contacts?search=' + encodeURIComponent(state.contacts.search) + '&status=' + state.contacts.status + '&page=' + state.contacts.page + '&per_page=' + state.contacts.perPage, function (data) {
            state.contacts.items = data.items || [];
            state.contacts.total = data.total || 0;
            renderContacts();
        });
    }

    function renderContacts() {
        var tbody = document.getElementById('contacts-tbody');
        if (!tbody) return;

        if (state.contacts.items.length === 0) {
            tbody.innerHTML = '<tr><td colspan="8" class="wpla-text-center">No contacts found.</td></tr>';
            return;
        }

        tbody.innerHTML = state.contacts.items.map(function (c) {
            var name = esc((c.first_name || '') + ' ' + (c.last_name || '')).trim() || '—';
            var statusClass = 'wpla-status-' + (c.status || 'active');
            return '<tr>' +
                '<td><strong>' + name + '</strong></td>' +
                '<td>' + esc(c.email) + '</td>' +
                '<td>' + esc(c.phone || '—') + '</td>' +
                '<td><span class="' + statusClass + '">' + esc(c.status) + '</span></td>' +
                '<td>' + (c.lead_score || 0) + '</td>' +
                '<td>—</td>' +
                '<td>' + esc(c.created_at || '') + '</td>' +
                '<td>' +
                    '<button class="wpla-btn wpla-btn-sm" onclick="WPLA.editContact(' + c.id + ')">Edit</button> ' +
                    '<button class="wpla-btn wpla-btn-sm wpla-btn-danger" onclick="WPLA.deleteContact(' + c.id + ')">Delete</button>' +
                '</td></tr>';
        }).join('');

        renderPagination('contacts-pagination', state.contacts.total, state.contacts.perPage, state.contacts.page, function (page) {
            state.contacts.page = page;
            loadContacts();
        });
    }

    WPLA.editContact = function (id) {
        restGet('contacts/' + id, function (c) {
            document.getElementById('contact-id').value = c.id;
            document.getElementById('contact-email').value = c.email || '';
            document.getElementById('contact-fname').value = c.first_name || '';
            document.getElementById('contact-lname').value = c.last_name || '';
            document.getElementById('contact-phone').value = c.phone || '';
            document.getElementById('contact-company').value = c.company || '';
            document.getElementById('contact-status').value = c.status || 'active';
            document.getElementById('contact-modal-title').textContent = 'Edit Contact';
            WPLA.openModal('contact-modal');
        });
    };

    WPLA.deleteContact = function (id) {
        if (!confirm('Delete this contact?')) return;
        ajax('wpla_delete_contact', { id: id }, function (res) {
            if (res.success) {
                showToast('Contact deleted.');
                loadContacts();
            }
        });
    };

    /* ───────────────────────────────────
     * Lists
     * ─────────────────────────────────── */
    function initLists() {
        loadLists();

        var addBtn = document.getElementById('btn-add-list');
        if (addBtn) {
            addBtn.addEventListener('click', function () {
                document.getElementById('list-id').value = '';
                document.getElementById('list-name').value = '';
                document.getElementById('list-description').value = '';
                document.getElementById('list-modal-title').textContent = 'New List';
                WPLA.openModal('list-modal');
            });
        }

        var saveBtn = document.getElementById('btn-save-list');
        if (saveBtn) {
            saveBtn.addEventListener('click', function () {
                ajax('wpla_save_list', {
                    id: document.getElementById('list-id').value,
                    name: document.getElementById('list-name').value,
                    description: document.getElementById('list-description').value,
                }, function (res) {
                    if (res.success) {
                        WPLA.closeModal('list-modal');
                        showToast('List saved!');
                        loadLists();
                    }
                });
            });
        }
    }

    function loadLists() {
        restGet('lists', function (lists) {
            var tbody = document.getElementById('lists-tbody');
            if (!tbody) return;

            if (!lists || lists.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="wpla-text-center">No lists yet.</td></tr>';
                return;
            }

            tbody.innerHTML = lists.map(function (l) {
                var shortcode = '[wpla_form list="' + l.id + '"]';
                return '<tr>' +
                    '<td><strong>' + esc(l.name) + '</strong></td>' +
                    '<td>' + esc(l.description || '—') + '</td>' +
                    '<td>—</td>' +
                    '<td><code class="wpla-code">' + esc(shortcode) + '</code></td>' +
                    '<td>' + esc(l.created_at) + '</td>' +
                    '<td>' +
                        '<button class="wpla-btn wpla-btn-sm" onclick="WPLA.editList(' + l.id + ',\'' + escAttr(l.name) + '\',\'' + escAttr(l.description || '') + '\')">Edit</button> ' +
                        '<button class="wpla-btn wpla-btn-sm wpla-btn-danger" onclick="WPLA.deleteList(' + l.id + ')">Delete</button>' +
                    '</td></tr>';
            }).join('');
        });
    }

    WPLA.editList = function (id, name, desc) {
        document.getElementById('list-id').value = id;
        document.getElementById('list-name').value = name;
        document.getElementById('list-description').value = desc;
        document.getElementById('list-modal-title').textContent = 'Edit List';
        WPLA.openModal('list-modal');
    };

    WPLA.deleteList = function (id) {
        if (!confirm('Delete this list?')) return;
        ajax('wpla_delete_list', { id: id }, function (res) {
            if (res.success) {
                showToast('List deleted.');
                loadLists();
            }
        });
    };

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
                document.getElementById('tag-modal-title').textContent = 'New Tag';
                WPLA.openModal('tag-modal');
            });
        }

        var saveBtn = document.getElementById('btn-save-tag');
        if (saveBtn) {
            saveBtn.addEventListener('click', function () {
                ajax('wpla_save_tag', {
                    id: document.getElementById('tag-id').value,
                    name: document.getElementById('tag-name').value,
                    color: document.getElementById('tag-color').value,
                }, function (res) {
                    if (res.success) {
                        WPLA.closeModal('tag-modal');
                        showToast('Tag saved!');
                        loadTags();
                    }
                });
            });
        }
    }

    function loadTags() {
        restGet('tags', function (tags) {
            var grid = document.getElementById('tags-grid');
            if (!grid) return;

            if (!tags || tags.length === 0) {
                grid.innerHTML = '<p class="wpla-text-center">No tags yet.</p>';
                return;
            }

            grid.innerHTML = tags.map(function (t) {
                return '<div class="wpla-tag-card">' +
                    '<div class="wpla-tag-card-info">' +
                        '<span class="wpla-tag-dot" style="background:' + esc(t.color) + '"></span>' +
                        '<div>' +
                            '<div class="wpla-tag-card-name">' + esc(t.name) + '</div>' +
                        '</div>' +
                    '</div>' +
                    '<div>' +
                        '<button class="wpla-btn wpla-btn-sm" onclick="WPLA.editTag(' + t.id + ',\'' + escAttr(t.name) + '\',\'' + escAttr(t.color) + '\')">Edit</button> ' +
                        '<button class="wpla-btn wpla-btn-sm wpla-btn-danger" onclick="WPLA.deleteTag(' + t.id + ')">×</button>' +
                    '</div>' +
                '</div>';
            }).join('');
        });
    }

    WPLA.editTag = function (id, name, color) {
        document.getElementById('tag-id').value = id;
        document.getElementById('tag-name').value = name;
        document.getElementById('tag-color').value = color;
        document.getElementById('tag-modal-title').textContent = 'Edit Tag';
        WPLA.openModal('tag-modal');
    };

    WPLA.deleteTag = function (id) {
        if (!confirm('Delete this tag?')) return;
        ajax('wpla_delete_tag', { id: id }, function (res) {
            if (res.success) {
                showToast('Tag deleted.');
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
        restGet('automations', function (automations) {
            var tbody = document.getElementById('automations-tbody');
            if (!tbody) return;

            if (!automations || automations.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="wpla-text-center">No automations yet.</td></tr>';
                return;
            }

            tbody.innerHTML = automations.map(function (a) {
                var statusClass = 'wpla-status-' + (a.status || 'draft');
                return '<tr>' +
                    '<td><strong>' + esc(a.name) + '</strong></td>' +
                    '<td><span class="wpla-badge wpla-badge-info">' + esc(a.trigger_type) + '</span></td>' +
                    '<td><span class="' + statusClass + '">' + esc(a.status) + '</span></td>' +
                    '<td>' + esc(a.created_at) + '</td>' +
                    '<td>' +
                        '<button class="wpla-btn wpla-btn-sm" onclick="WPLA.editAutomation(' + a.id + ')">Edit</button> ' +
                        '<button class="wpla-btn wpla-btn-sm wpla-btn-danger" onclick="WPLA.deleteAutomation(' + a.id + ')">Delete</button>' +
                    '</td></tr>';
            }).join('');
        });
    }

    function showBuilder() {
        document.getElementById('automations-list-view').style.display = 'none';
        document.getElementById('automation-builder').style.display = 'block';
    }

    function hideBuilder() {
        document.getElementById('automations-list-view').style.display = 'block';
        document.getElementById('automation-builder').style.display = 'none';
    }

    WPLA.editAutomation = function (id) {
        restGet('automations', function (automations) {
            var auto = automations.find(function (a) { return parseInt(a.id) === id; });
            if (!auto) return;

            state.automationId = id;
            document.getElementById('automation-name').value = auto.name || '';
            document.getElementById('automation-trigger').value = auto.trigger_type || 'contact_created';
            document.getElementById('automation-status').value = auto.status || 'draft';

            restGet('automations/' + id + '/steps', function (steps) {
                state.automationSteps = (steps || []).map(function (s) {
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

    WPLA.deleteAutomation = function (id) {
        if (!confirm('Delete this automation?')) return;
        ajax('wpla_delete_automation', { id: id }, function (res) {
            if (res.success) {
                showToast('Automation deleted.');
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

        ajax('wpla_save_automation', data, function (res) {
            if (res.success) {
                state.automationId = res.data.id;
                showToast('Automation saved!');
            } else {
                showToast('Error saving.', 'error');
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
                WPLA.openModal('step-modal');
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

        var confirmBtn = document.getElementById('btn-confirm-step');
        if (confirmBtn) {
            confirmBtn.addEventListener('click', addStep);
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
                html = '<div class="wpla-field"><label>Tag Name</label><input type="text" id="action-tag-name" class="wpla-input" placeholder="e.g. lead" /></div>';
                break;
            case 'subscribe_list':
                html = '<div class="wpla-field"><label>List ID</label><input type="number" id="action-list-id" class="wpla-input" placeholder="1" /></div>';
                break;
            case 'send_email':
                html = '<div class="wpla-field"><label>Subject</label><input type="text" id="action-email-subject" class="wpla-input" /></div>' +
                       '<div class="wpla-field"><label>Body (HTML)</label><textarea id="action-email-body" class="wpla-input" rows="4"></textarea></div>';
                break;
            case 'send_whatsapp':
                html = '<div class="wpla-field"><label>Message</label><textarea id="action-wa-message" class="wpla-input" rows="3"></textarea></div>';
                break;
            case 'update_field':
                html = '<div class="wpla-field"><label>Field</label><input type="text" id="action-field-name" class="wpla-input" placeholder="first_name" /></div>' +
                       '<div class="wpla-field"><label>Value</label><input type="text" id="action-field-value" class="wpla-input" /></div>';
                break;
            case 'update_score':
                html = '<div class="wpla-field"><label>Score Delta (+/-)</label><input type="number" id="action-score-delta" class="wpla-input" value="5" /></div>';
                break;
            case 'webhook':
                html = '<div class="wpla-field"><label>URL</label><input type="url" id="action-webhook-url" class="wpla-input" placeholder="https://..." /></div>';
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
            step.config = {
                condition_type: document.getElementById('condition-type').value,
                value: document.getElementById('condition-value').value,
            };
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
                    step.config.list_id = getVal('action-list-id');
                    break;
                case 'send_email':
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
        WPLA.closeModal('step-modal');
    }

    function renderFlowNodes() {
        var container = document.getElementById('flow-nodes');
        if (!container) return;

        if (state.automationSteps.length === 0) {
            container.innerHTML = '<p class="wpla-text-center" style="color:var(--wpla-text-secondary);padding:40px;">No steps yet. Click "+ Add Step" to build your flow.</p>';
            return;
        }

        container.innerHTML = state.automationSteps.map(function (step, index) {
            var nodeClass = 'wpla-node-' + step.step_type;
            var title = getStepTitle(step);
            var desc = getStepDescription(step);
            var typeLabel = step.step_type.toUpperCase();
            var icons = { trigger: '🟢', condition: '🔀', action: '⚡', delay: '⏱', branch: '🔀' };
            var icon = icons[step.step_type] || '📦';

            var connector = index < state.automationSteps.length - 1 ? '<div class="wpla-flow-connector"></div>' : '';

            return '<div class="wpla-flow-node ' + nodeClass + '" data-index="' + index + '">' +
                '<button class="wpla-flow-node-remove" onclick="WPLA.removeStep(' + index + ')">×</button>' +
                '<div class="wpla-flow-node-header">' +
                    '<span class="wpla-flow-node-type">' + icon + ' ' + typeLabel + '</span>' +
                '</div>' +
                '<div class="wpla-flow-node-title">' + esc(title) + '</div>' +
                '<div class="wpla-flow-node-desc">' + esc(desc) + '</div>' +
            '</div>' + connector;
        }).join('');
    }

    WPLA.removeStep = function (index) {
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
                return 'Wait ' + (step.config.amount || '?') + ' ' + (step.config.unit || 'hours');
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
                if (c.list_id) return 'List: ' + c.list_id;
                if (c.subject) return 'Subject: ' + c.subject;
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
                ajax('wpla_send_test_whatsapp', {
                    phone: document.getElementById('test-wa-phone').value,
                    message: document.getElementById('test-wa-message').value,
                }, function (res) {
                    showToast(res.success ? 'Test message sent!' : 'Failed to send.', res.success ? 'success' : 'error');
                });
            });
        }
    }

    /* ───────────────────────────────────
     * Email Page
     * ─────────────────────────────────── */
    function initEmail() {
        loadMessages('email', 'email-tbody');

        var form = document.getElementById('test-email-form');
        if (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                ajax('wpla_send_test_email', {
                    to: document.getElementById('test-email-to').value,
                    subject: document.getElementById('test-email-subject').value,
                    body: document.getElementById('test-email-body').value,
                }, function (res) {
                    showToast(res.success ? 'Test email sent!' : 'Failed to send.', res.success ? 'success' : 'error');
                });
            });
        }
    }

    function loadMessages(channel, tbodyId) {
        // We use a simple approach: fetch via REST or use admin-ajax.
        // For now, we'll show a placeholder. In production, add a REST endpoint.
        var tbody = document.getElementById(tbodyId);
        if (tbody) {
            tbody.innerHTML = '<tr><td colspan="5" class="wpla-text-center">Message history loads from the queue.</td></tr>';
        }
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

                ajax('wpla_save_settings', formData, function (res) {
                    if (res.success) {
                        showToast(res.data.message || 'Settings saved!');
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
            html += '<button onclick="this._cb()">← Prev</button>';
        }

        var start = Math.max(1, currentPage - 2);
        var end = Math.min(totalPages, currentPage + 2);

        for (var i = start; i <= end; i++) {
            var active = i === currentPage ? ' active' : '';
            html += '<button class="' + active + '" data-page="' + i + '">' + i + '</button>';
        }

        if (currentPage < totalPages) {
            html += '<button data-page="' + (currentPage + 1) + '">Next →</button>';
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
