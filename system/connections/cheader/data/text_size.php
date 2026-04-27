<?php if (user('ID') > 0) : ?>
<?php if (settings('TEXT_SIZE') == 1) : ?>
<style> body { font-size: 12px; } </style>
<?php endif ?>
<?php if (settings('TEXT_SIZE') == 2) : ?>
<style> body { font-size: 14px; } </style>
<?php endif ?>
<?php if (settings('TEXT_SIZE') == 3) : ?>
<style> body { font-size: 18px; } .panel-top-right { font-size: 14px; } </style>
<?php endif ?>
<?php endif ?>