<div>
    afdeling: 
    <a class="button_link" href="<?= $this->dir_backs ?>cells/A">‎ A‎ </a>
    <a class="button_link" href="<?= $this->dir_backs ?>cells/B">‎ B‎ </a>
    <a class="button_link" href="<?= $this->dir_backs ?>cells/C">‎ C‎ </a>
</div>
<?php foreach($cells as $cell): ?>
    <div class="floater card">
        <div>id: <?= $cell['id'] ?></div>
        <div>bezet: <?= ($cell['in_use'] == 0) ? "vrij" : "bezet" ?></div>
    </div>
<?php endforeach; ?>