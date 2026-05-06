<?php if(empty($type_request)): ?>
    <div class="field">
        <h2>informatie:</h2>
        <div>naam: <?= $prisoner_info['name'] ?></div>
        <div>bsn nummer: <?= $prisoner_info['bsn'] ?></div>
        <div>nationaliteit: <?= $prisoner_info['nationality'] ?></div>
        <div>gender: <?= $prisoner_info['gender'] ?></div>
        <div>lengte: <?= $prisoner_info['length'] ?></div>
        <div>geboortedatum: <?= $prisoner_info['date_of_birth'] ?></div>
        <?php if ($this->auth->hasAnyRole(\Delight\Auth\Role::DIRECTOR, \Delight\Auth\Role::ADMIN)): ?><a class="button_link no_pos_manip" href=<?= $this->dir_backs . "prisonerdossier/" . $id . "?edit=true" ?>>aanpassen</a><?php endif; ?>
    </div>
<?php else: ?>
    <form method="post">

        <?php foreach($list as $list_key => $list_item): ?>
            <div class="separateditems">

                <label for="<?= $list_key ?>"><?= $list_item ?></label>
                <input class="textinput" type="text" name="<?= $list_key ?>" value="<?= $prisoner_info[$list_key] ?>" required>
            </div>
        <?php endforeach; ?>
        
        <div class="separateditems">
            <label for="date">geboortedatum</label>
            <input class="timefield" type="date" name="date" value="<?= $prisoner_info['date_of_birth'] ?>" required>
        </div>
        <a class="button_link" href="<?= $this->dir_backs . "prisonerdossier/" . $id ?>">annuleer</a>
        <input class="button_link" type="submit" value="stuur">

    </form>
<?php endif; ?>

<div id="list_container">
    <?php if (!empty($prisoner_history)): ?>
    <table>
        <tr>
            <th>afdeling</th>
            <th>cel</th>
            <th>reden</th>
            <th>arrestatie</th>
            <th>vrijlating</th>
        </tr>
        <?php foreach($prisoner_history as $prisoner): ?>
            <tr>
                <td><?= $prisoner['vleugel'] ?></td>
                <td><?= $prisoner['cell_id'] ?></td>
                <td class="align_left"><?= $prisoner['reason'] ?></td>
                <td><?= date('Y-m-d h:i:s', $prisoner['time_jailed']) ?></td>
                <td><?= date('Y-m-d h:i:s', $prisoner['time_to_release']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php else: ?>
        Hmm... Ik heb geen eerdere incidenten van deze persoon kunnen vinden.
    <?php endif; ?>
</div>