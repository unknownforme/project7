<form method="post">
    <input class="searchbar" type="text" name="search" placeholder="zoek...">
    <input class="searchbar_button" type="submit" value="zoek">
</form>
 
 <table>
    <tr>
        <th>naam</th>
        <th>reden</th>
        <th>gearresteerd op:</th>
        <th>vrijgelaten op:</th>
    </tr>
    <?php foreach($history as $prisoner): ?>
        <tr>
            <td><?= $prisoner['name'] ?></td>
            <td><?= $prisoner['reason'] ?></td>
            <td><?= date('Y-m-d h:i:s',$prisoner['time_jailed']); ?></td>
            <td><?= date('Y-m-d h:i:s',$prisoner['time_to_release']); ?></td>
        </tr>
    <?php endforeach; ?>
 </table>