<?php
$scripts = $scripts ?? [];
?>
    <script>
      window.APP_BASE_URL = "<?= h(base_url('')) ?>";
    </script>
    <script src="<?= h(base_url('assets/js/global.js')) ?>"></script>
    <script src="<?= h(base_url('assets/js/whatsapp-chat.js')) ?>"></script>
<?php foreach ($scripts as $script): ?>
    <script src="<?= h(base_url($script)) ?>"></script>
<?php endforeach; ?>

    <script>
    // Lazy image loading fallback
    (function() {
      document.querySelectorAll('img[loading="lazy"]').forEach(function(img) {
        if (img.complete) img.classList.add('loaded');
        else img.addEventListener('load', function() { img.classList.add('loaded'); });
      });
    })();
    </script>
</body>
</html>
