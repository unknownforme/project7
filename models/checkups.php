<div class="inside_space colored spaced">voeg notitie toe<a href="addcheckup">nieuwe notitie</a></div>

<div id="list_container">
    <?php if (!empty($notes)): ?>
    <table>
        <tr>
            <th>informatie</th>
            <th>datum</th>
            <th>gevangene</th>
        </tr>
        <?php foreach($notes as $note): ?>
            <tr>
                <td><?= $note['checkup_info'] ?></td>
                <td><?= date('Y-m-d h:i:s', $note['date']) ?></td>
                <td><?= $this->db_obj->getPrisonerNameById($note['prisoner_id']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php else: ?>
        Hmm... Ik heb geen notities kunnen vinden.
    <?php endif; ?>
</div>