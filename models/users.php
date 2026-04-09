 <table>
    <tr>
        <th>naam</th>
        <th>aanpassen</th>
    </tr>
    <?php foreach($users as $user): ?>
        <tr>
            <td><?= $user['username'] ?></td>
            <td><a href="editroles/<?= $user['id'] ?>">edit</a></td>
        </tr>
    <?php endforeach; ?>
 </table>