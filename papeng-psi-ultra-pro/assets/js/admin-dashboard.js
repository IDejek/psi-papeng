/**
 * PSI Papeng Premium — Admin Dashboard JS
 * @version 1.0.0
 */
(function($) {
    'use strict';

    /* ── Confirm Delete Actions ────────────────────────────── */
    $(document).on('click', '.psi-action-red', function() {
        if (!confirm(psiAdmin.i18n.confirm_delete)) return false;
    });

    /* ── Smooth hover effects ──────────────────────────────── */
    $(document).ready(function() {
        /* Animate stat numbers on load */
        $('.psi-stat-number').each(function() {
            const $this = $(this);
            const target = parseInt($this.text().replace(/[^0-9]/g, '')) || 0;
            if (target === 0) return;
            $({ count: 0 }).animate({ count: target }, {
                duration: 800,
                easing: 'swing',
                step: function() {
                    $this.text(Math.floor(this.count).toLocaleString('id-ID'));
                },
                complete: function() {
                    $this.text(target.toLocaleString('id-ID'));
                }
            });
        });
    });

})(jQuery);
