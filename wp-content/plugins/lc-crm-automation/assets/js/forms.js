/**
 * LC CRM AUTOMATION — Frontend Forms JS
 *
 * @package LC_CRM_Automation
 */

(function () {
    'use strict';

    // Capture UTM params from URL.
    function getUTMParams() {
        var params = new URLSearchParams(window.location.search);
        return {
            utm_source: params.get('utm_source') || '',
            utm_medium: params.get('utm_medium') || '',
            utm_campaign: params.get('utm_campaign') || '',
            utm_content: params.get('utm_content') || '',
            utm_term: params.get('utm_term') || '',
        };
    }

    // Fill hidden UTM fields.
    document.addEventListener('DOMContentLoaded', function () {
        var utms = getUTMParams();
        var forms = document.querySelectorAll('.wpla-form');

        forms.forEach(function (form) {
            Object.keys(utms).forEach(function (key) {
                var input = form.querySelector('input[name="' + key + '"]');
                if (input) {
                    input.value = utms[key];
                }
            });

            form.addEventListener('submit', function (e) {
                e.preventDefault();
                submitForm(form);
            });
        });
    });

    function submitForm(form) {
        var btn = form.querySelector('.wpla-submit');
        var msg = form.querySelector('.wpla-form-message');
        var formData = new FormData(form);

        formData.append('action', 'wpla_form_submit');
        formData.append('nonce', (typeof wpla_form_vars !== 'undefined') ? wpla_form_vars.nonce : '');

        btn.disabled = true;
        btn.textContent = '...';
        msg.style.display = 'none';

        fetch((typeof wpla_form_vars !== 'undefined') ? wpla_form_vars.ajax_url : '/wp-admin/admin-ajax.php', {
            method: 'POST',
            body: formData,
        })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                msg.style.display = 'block';
                if (res.success) {
                    msg.className = 'wpla-form-message wpla-msg-success';
                    msg.textContent = res.data.message || 'Success!';
                    form.reset();
                } else {
                    msg.className = 'wpla-form-message wpla-msg-error';
                    msg.textContent = (res.data && res.data.message) ? res.data.message : 'Error submitting form.';
                }
            })
            .catch(function () {
                msg.style.display = 'block';
                msg.className = 'wpla-form-message wpla-msg-error';
                msg.textContent = 'Network error. Please try again.';
            })
            .finally(function () {
                btn.disabled = false;
                btn.textContent = btn.getAttribute('data-original-text') || 'Subscribe';
            });
    }
})();
