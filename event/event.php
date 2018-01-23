<?php
include "../function.php";
include "../html/partials/head.php";
include "../html/partials/nav.php";
if (isAdmin()) {
    $events = $pdo->query("SELECT event.id, event.name, event.date, event.time_carpool, divesite.name AS divesite, concat(user.firstname, ' ', user.name) AS diveleader, diveclub.name AS diveclub FROM event INNER JOIN divesite ON event.id_divesite = divesite.id INNER JOIN user ON event.id_user = user.id INNER JOIN diveclub ON event.id_diveclub = diveclub.id WHERE event.date >= curdate()")->fetchAll();
} else {
    $stmt = $pdo->prepare("SELECT event.id, event.name, event.date, event.time_carpool, divesite.name AS divesite, concat(user.firstname, ' ', user.name) AS diveleader, diveclub.name AS diveclub FROM event INNER JOIN divesite ON event.id_divesite = divesite.id INNER JOIN user ON event.id_user = user.id INNER JOIN diveclub ON event.id_diveclub = diveclub.id INNER JOIN diveclub_user ON event.id_diveclub = diveclub_user.id_diveclub WHERE diveclub_user.id_user = ? AND event.date >= curdate()");
    $stmt->execute(array($_SESSION['id']));
    $events = $stmt->fetchAll();
}
$stmt = $pdo->prepare("SELECT user_event.id_event AS id FROM user_event WHERE id_user = ?");
$stmt->execute(array($_SESSION['id']));
$registrations = $stmt->fetchAll();
$registrations = array_reduce($registrations, 'array_merge', array());
?>
    <div class="container">
        <h2>Duik overzicht</h2> <a class="btn btn-outline-success" href="event_add.php" role="button">Duik
            toevoegen</a><br/>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Naam</th>
                <th>Datum</th>
                <th>Vertrek uur</th>
                <th>Duikplaats</th>
                <th>Duikverantwoordelijke</th>
                <th>Duikclub</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($events as $event) { ?>
                <tr>
                    <td><?php print $event['name']; ?></td>
                    <td><?php print $event['date']; ?></td>
                    <td><?php print $event['time_carpool']; ?></td>
                    <td><?php print $event['divesite']; ?></td>
                    <td><?php print $event['diveleader']; ?></td>
                    <td><?php print $event['diveclub']; ?></td>
                    <td><a class="btn btn-outline-primary"
                           href="event_info.php?id=<?php print $event['id']; ?>"
                           role="button">Info</a>
                        <?php if (!in_array($event['id'], $registrations)) { ?>
                            <a class="btn btn-outline-success"
                               href="event_register.php?id=<?php print $event['id']; ?>"
                               role="button">Inschijven</a>
                        <?php } else { ?>
                            <a class="btn btn-outline-danger"
                               href="event_unregister.php?id=<?php print $event['id']; ?>"
                               role="button">Uitschijven</a>
                        <?php }
                        if (isAdmin()) { ?>
                            <a class="btn btn-outline-warning"
                               href="event_edit.php?id=<?php print $event['id']; ?>"
                               role="button">Wijzig</a> <a class="btn btn-outline-danger"
                                                           href="event_delete.php?id=<?php print $event['id']; ?>"
                                                           role="button">Delete</a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
<?php
include "../html/partials/includes.php";