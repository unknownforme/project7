<div class="inside_space colored spaced">zie dossier<a href="<?= $this->dir_backs ?>prisonerdossier/<?= $id ?>">dossier</a></div>
<?php if ($is_jailed) : ?>
    <div class="inside_space colored spaced">verplaats gevangene<a href="<?= $this->dir_backs ?>moveprisoner/<?= $id ?>">verplaats</a></div>
    <div class="inside_space colored spaced">laat gevangene vrij<a href="<?= $this->dir_backs ?>freeprisoner/<?= $id ?>">zet vrij</a></div>
<?php endif; ?>