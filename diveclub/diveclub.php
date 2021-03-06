<?php
include "../function.php";
isAdminRedirect();
include "../html/partials/head.php";
include "../html/partials/nav.php";
$diveclubs = $pdo->query("SELECT id, name, city FROM diveclub WHERE deleted = 0 ORDER BY name ASC")->fetchAll();
?>
    <div class="container">
        <h2>Duikclub overzicht</h2> <a class="btn btn-outline-success" href="diveclub_add.php" role="button">Duikclub
            toevoegen</a><br/>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Naam</th>
                <th>Stad</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($diveclubs as $diveclub) { ?>
                <tr>
                    <td><?php print $diveclub['name']; ?></td>
                    <td><?php print $diveclub['city']; ?></td>
                    <td><a class="btn btn-outline-warning"
                           href="diveclub_edit.php?id=<?php print $diveclub['id']; ?>"
                           role="button">Wijzig</a> <a class="btn btn-outline-danger"
                                                       href="diveclub_delete.php?id=<?php print $diveclub['id']; ?>"
                                                       role="button">Delete</a></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
<?php
include "../html/partials/includes.php";