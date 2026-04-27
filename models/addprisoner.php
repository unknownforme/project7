<form method="post">

    <?php foreach($list as $list_key => $list_item): ?>
        <div class="separateditems">

            <label for="<?= $list_key ?>"><?= $list_item ?></label>
            <input class="textinput" type="text" name="<?= $list_key ?>" required>
        </div>
    <?php endforeach; ?>
    
    <div class="separateditems">
        <label for="date">geboortedatum</label>
        <input class="timefield" type="date" name="date" required>
    </div>

    <input class="submit" type="submit" value="stuur">

</form>

