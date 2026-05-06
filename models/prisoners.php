<?php if($this->auth->hasAnyRole(\Delight\Auth\Role::DIRECTOR, \Delight\Auth\Role::ADMIN)): ?>
<div class="inside_space colored spaced">voeg gevangene toe<a href="addprisoner">nieuwe gevangene</a></div>
<div class="inside_space colored spaced">voeg arrestatie toe<a href="addarrest">nieuwe arrestatie</a></div><br>
<?php endif; ?> 

<div id="list_container">
    <form method="post">
        <input class="searchbar" type="text" name="search" placeholder="zoek...">
        <input class="searchbar_button" type="submit" value="zoek">
        <div id="options_container">
            <div class="option_block">
                <label for="cars">zoek op gevangenisstatus:</label><br>
                <select name="arrest_status">
                    <option value="arrested">gearresteerd</option>
                    <option value="free" <?php if($prisoner_status == "free") {echo " selected ";} ?>>alle gevangenen</option>
                </select>
            </div>
        </div>
    </form>
    <?php if ($prisoner_status === "arrested"): ?>
    <table>
        <tr>
            <th>naam</th>
            <th>afdeling</th>
            <th>cel</th>
            <th>reden</th>
            <th>gender</th>
            <th>vrijlating</th>
            <th>informatie</th>
        </tr>
        <?php foreach($prisoners as $prisoner): ?>
            <tr>
                <td><?= $prisoner['name'] ?></td>
                <td><?= $prisoner['vleugel'] ?></td>
                <td><?= $prisoner['cell_id'] ?></td>
                <td class="align_left"><?= $prisoner['reason'] ?></td>
                <td><?= $prisoner['gender'] ?></td>
                <td><?= date('Y-m-d h:i:s', $prisoner['time_to_release']) ?></td>
                <td><a href="<?= $this->dir_backs ?>prisonerinfo/<?= $prisoner['inmate_id'] ?>">bekijk</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php else: ?>
        <table>
        <tr>
            <th>naam</th>
            <th>gender</th>
            <th>informatie</th>
        </tr>
        <?php foreach($prisoners as $prisoner): ?>
            <tr>
                <td><?= $prisoner['name'] ?></td>
                <td><?= $prisoner['gender'] ?></td>
                <td><a href="<?= $this->dir_backs ?>prisonerinfo/<?= $prisoner['id'] ?>">bekijk</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
</div>