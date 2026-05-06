<div class="inside_space colored spaced">voeg notitie toe<a href="addcheckup">nieuwe notitie</a></div>

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
        Hmm... Ik heb geen notities kunnen vinden.
    <?php endif; ?>
</div>