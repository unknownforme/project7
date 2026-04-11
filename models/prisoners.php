<?php if($this->auth->hasAnyRole(\Delight\Auth\Role::DIRECTOR, \Delight\Auth\Role::ADMIN)): ?>
<div class="inside_space colored spaced">voeg gevangene toe<a href="addprisoner">nieuwe gevangene</a></div>
<div class="inside_space colored spaced">voeg arrestatie toe<a href="addarrest">nieuwe arrestatie</a></div><br>
<?php endif; ?> 
<table>
    <tr>
        <th>naam</th>
        <th>afdeling</th>
        <th>cel</th>
        <th>vrijlating</th>
        <th>gender</th>
        <th>aanpassen</th>
    </tr>
    <?php foreach($prisoners as $prisoner): ?>
        <tr>
            <td><?= $prisoner['name'] ?></td>
            <td><?= $prisoner['vleugel'] ?></td>
            <td><?= $prisoner['cell_id'] ?></td>
            <td><?= $prisoner['time_to_release'] ?></td>
            <td><?= $prisoner['gender'] ?></td>
            <td><a href="editprisoner/<?= $prisoner['id'] ?>">edit</a></td>
        </tr>
    <?php endforeach; ?>
</table>