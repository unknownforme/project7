<form method="post">
<a class="button_link" href="<?= $this->dir_backs ?>prisonerinfo/<?= $id ?>">terug</a>    
    <input class="no_pos_manip button_link" type="submit" value="<?php if ($this->auth->hasAnyRole(\Delight\Auth\Role::DIRECTOR, \Delight\Auth\Role::ADMIN)) { echo "bevrijd gevangene";} else { echo "maak bevrijdingsaanvraag"; } ?>">
</form>