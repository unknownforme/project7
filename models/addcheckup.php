<?php if (empty($prisoners)) : ?>
    er zijn geen gevangenen waar je een notitie bij kan maken
<?php else: ?>
    <form method="post">
        <div class="separateditems">
            <label for="prisoner_id">gevangene:</label>
            <select name="prisoner_id" class="timefield">
                <?php foreach($prisoners as $prisoner): ?>
                        <option value="<?= $prisoner['id'] ?>"><?= $prisoner['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="separateditems">
            <label for="info">informatie:</label>
            <input class="textinput" type="text" name="info" value="<?= $last_email ?? "" ?>">
        </div>
        <input class="button_link" type="submit" value="maak aan">
    </form>
<?php endif; ?>
