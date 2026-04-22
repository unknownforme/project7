<div>
    <form action="post">
        <div>
            <div class="card">name: <?= $name ?>
            </div>
            <div class="card">email: <?= $email ?>
            </div>
        </div>
        <div>
            <a class="button_link" href="<?= $this->dir_backs ?>updateaccount">change account info</a>
            <a class="button_link" href="<?= $this->dir_backs ?>logout">uitloggen</a>
        </div>
    </form>

</div>