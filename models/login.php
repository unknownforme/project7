<form method="post">
    <div class="separateditems">
        <label for="email">email:</label>
        <input class="textinput" type="text" name="email" value="<?= $last_email ?? "" ?>" required>
    </div>
    <div class="separateditems">
        <label for="password">wachtwoord:</label>
        <input class="textinput" type="password" name="password" required>
    </div>
    <input type="submit" value="login">
</form>