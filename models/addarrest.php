<form method="post">

    <?php foreach($list as $list_key => $list_item): ?>
        <div class="separateditems">
            <label for="<?= $list_key ?>"><?= $list_item ?></label>
            <input class="textinput" type="text" name="<?= $list_key ?>" required>
        </div>
    <?php endforeach; ?>
    
    <div class="separateditems">
        <label for="cell">cel:</label>
        <select name="cell" class="timefield">
            <?php foreach($cells as $cell): 
                if ($cell['in_use'] == 0) :?> 
                    <option value="<?= $cell['id'] ?>"><?= $cell['id'] . " vleugel: " . $cell['vleugel'] ?></option>
                <?php endif;
            endforeach; ?>
        </select>
    </div>
    
    <div class="separateditems">
        <label for="time_jailed">gearresteerd op:</label>
        <input class="timefield" type="date" name="time_jailed" required>
    </div>
    
    <div class="separateditems">
        <label for="time_to_release">wordt vrijgelaten op:</label>
        <input class="timefield" type="date" name="time_to_release" required>
    </div>

    <input class="submit" type="submit" value="stuur">

</form>