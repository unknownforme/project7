<?php if (!empty($all_requests)) : ?>
    <table>
        <tr>
            <th>gevangene</th>
            <th>type</th>
            <th>keuze</th>
        </tr>
            <?php foreach($all_requests as $request) : ?>
            <tr>
                <td><?= $this->db_obj->getPrisonerNameById($request['prisoner_id']) ?></td>
                <td><?php if ($request['type'] == 0) { echo "verhuis gevangene naar cel " . $request['to']; } else { echo "zet gevangene vrij";} ?></td>
                <td><a href="<?= $this->dir_backs ?>cipierrequests?id=<?= $request['id'] ?>">accepteren</a> / <a href="<?= $this->dir_backs ?>cipierrequests?delete_id=<?= $request['id'] ?>">afwijzen</a></td>
            </tr>
        <?php endforeach; ?>
    </table>

<?php else : ?>

    geen aanvragen gevonden

<?php endif; ?>