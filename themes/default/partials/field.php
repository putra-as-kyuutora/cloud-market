<?php
/** Satu baris input form. Variabel: $name, $label, $type(=text), $value(=old), $placeholder, $extra, $errors */
$type = $type ?? 'text';
$val = $value ?? old($name);
?>
<div class="field <?= isset($errors[$name]) ? 'has-err' : '' ?>">
  <label for="f-<?= e($name) ?>"><?= e($label) ?></label>
  <?php if ($type === 'textarea'): ?>
    <textarea id="f-<?= e($name) ?>" name="<?= e($name) ?>" rows="4" placeholder="<?= e($placeholder ?? '') ?>"><?= e($val) ?></textarea>
  <?php else: ?>
    <input id="f-<?= e($name) ?>" type="<?= e($type) ?>" name="<?= e($name) ?>" value="<?= $type === 'password' ? '' : e($val) ?>" placeholder="<?= e($placeholder ?? '') ?>" <?= $extra ?? '' ?>>
  <?php endif; ?>
  <?php if (isset($errors[$name])): ?><div class="err"><?= e($errors[$name]) ?></div><?php endif; ?>
</div>
