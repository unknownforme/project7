<form method="post">
    maak gebruiker "<?= $user['username'] ?>"<br>
    <?php foreach($roles as $rolename => $role): ?>
        <?php 
            
        try {
            if ($this->auth->admin()->doesUserHaveRole($id, $role)) {
                $check = "checked";
            }
            else {
                $check = "";
            }
        }    
        catch (\Delight\Auth\UnknownIdException $e) {
            die('Unknown user ID');
        }
        ?>
        <label class="floater" for="<?= $rolename ?>"><?= $rolename ?></label>
        <input class="floater" type="checkbox" name="<?= $rolename ?>" <?= $check ?>><br>
    <?php endforeach; ?>
    <input type="submit" value="submit">
</form>