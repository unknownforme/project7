 <table>
    <tr>
        <th>naam</th>
        <th>afdeling</th>
        <th>reden</th>
        <th>gearresteerd op:</th>
        <th>vrijgelaten op:</th>
    </tr>
    <?php foreach($prisoners as $prisoner): ?>
        <tr>
            <td><?= $prisoner ?></td>
            <td><?= $prisoner ?></td>
            <td><?= $prisoner ?></td>
            <td><?= $prisoner ?></td>
            <td><a href="edit/<?= $prisoner['id'] ?>">edit</a></td>
        </tr>
    <?php endforeach; ?>
 </table>