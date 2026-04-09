<form method="post">
    maak gebruiker "<?= $user['username'] ?>"<br>
    <?php foreach($roles as $rolename => $role): ?>
        <label class="floater" for="<?= $rolename ?>"><?= $rolename ?></label>
        <input class="floater" type="checkbox" name="<?= $rolename ?>"><br>
    <?php endforeach; ?>
    <input type="submit" value="submit">
</form>