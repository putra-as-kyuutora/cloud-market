<?php /** Variabel: $flashSuccess, $flashError (string|null) */ ?>
<?php if (!empty($flashSuccess)): ?>
  <div class="alert alert-ok auto-dismiss" role="alert">
    <svg viewBox="0 0 20 20" width="18" height="18" fill="currentColor" style="flex-shrink:0"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
    <span class="alert-text"><?= e($flashSuccess) ?></span>
    <button type="button" class="alert-close" onclick="this.closest('.alert').remove()" aria-label="Tutup">&times;</button>
  </div>
<?php endif; ?>
<?php if (!empty($flashError)): ?>
  <div class="alert alert-bad auto-dismiss" role="alert">
    <svg viewBox="0 0 20 20" width="18" height="18" fill="currentColor" style="flex-shrink:0"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
    <span class="alert-text"><?= e($flashError) ?></span>
    <button type="button" class="alert-close" onclick="this.closest('.alert').remove()" aria-label="Tutup">&times;</button>
  </div>
<?php endif; ?>
<script>
(function() {
  var alerts = document.querySelectorAll('.alert.auto-dismiss');
  alerts.forEach(function(el) {
    setTimeout(function() {
      el.classList.add('alert-fading');
      setTimeout(function() { if (el && el.parentNode) el.remove(); }, 350);
    }, 2000); // 2.0 detik langsung hilang secara halus
  });
})();
</script>
