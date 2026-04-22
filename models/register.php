<form method="post">
    <div class="separateditems">
        <label for="email">email:</label>
        <input class="textinput" type="text" name="email" value="<?= $last_email ?? "" ?>">
    </div>
    <div class="separateditems">
        <label for="username">naam:</label>
        <input class="textinput" type="text" name="username" value="<?= $last_username ?? "" ?>">
    </div>
    <div class="separateditems">
        <label for="password">wachtwoord:</label>
        <input class="textinput" type="password" name="password" value="<?= $last_password ?? "" ?>">
    </div>
    wachtwoord moet met 1+ hoofdletters en 8+ characters<br>
    <input class="submit" type="submit" value="create">
</form>