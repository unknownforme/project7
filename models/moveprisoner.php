<form method="post">
<a class="button_link" href="<?= $this->dir_backs ?>prisonerinfo/<?= $id ?>">terug</a>    
        
    <label for="cell">verplaats naar cel:</label>
    <select name="cell" class="timefield">
        <?php foreach($cells as $cell): 
            if ($cell['in_use'] == 0) :?> 
                <option value="<?= $cell['id'] ?>"><?= " vleugel: " . $cell['vleugel'] . " id: " . $cell['cell_nr'] ?></option>
            <?php endif;
        endforeach; ?>
    </select>

    <input class="no_pos_manip button_link" type="submit" value="<?php if ($this->auth->hasAnyRole(\Delight\Auth\Role::DIRECTOR, \Delight\Auth\Role::ADMIN)) { echo "verplaats gevangene";} else { echo "maak verplaatsingsaanvraag"; } ?>">
</form>