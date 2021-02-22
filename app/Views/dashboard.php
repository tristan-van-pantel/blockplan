<?= $this->extend('layouts'.DIRECTORY_SEPARATOR.'frontend') ?>
<?= $this->section('content') ?>

<div class="card mb-3">
  <h3 class="card-header text-white bg-dark">Personal Infos</h3>
  <div class="card-body">
    <table class="table">

      <tbody>
        <tr>
          <td><b>Vorname:</b> </td>
          <td><?= $user->firstname ?></td>
        </tr>
        <tr>
          <td><b>Nachname:</b></td>
          <td><?= $user->lastname ?></td>
        </tr>
        <tr>
        <tr>
          <td><b>Email:</b> </td>
          <td><?= $user->email ?></td>
        </tr>
        <tr>
          <td><b>Klasse:</b> </td>
          <td><?= $user->class ?></td>
        </tr>
        <tr>
          <td><b>Begin: </b></td>
          <td><?= (!empty($user->begin)) ? ( date("d.m.Y", strtotime($user->begin)) ) : ('kein Anfangsdatum eingetragen') ?></td>
        </tr>
        <tr>
          <td><b>End:</b></td>
          <td><?= (!empty($user->end)) ? ( date("d.m.Y", strtotime($user->end)) ) : ('kein Enddatum eingetragen') ?></td>
        </tr>
        <?php if (!empty($user->days)) : ?>
          <tr>
          <td><b>Fehltage:</b></td>
          <td><?= $user->days ?></td>
        </tr>
          <?php endif ?>
      </tbody>
    </table>

  </div>
</div>






<?php if (!empty($user->courses2do)) : ?>
<div class="card mb-3">
  <h3 class="card-header text-white bg-dark">Pflichtkurse</h3>
  <div class="card-body">
    <?php if (!empty($user->visited_ids)) : ?>
    <div class="progress">
      <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
        style="width: <?= ( 100/( count($user->courses2do)/count($user->visited_ids) ) ) ?>%"
        aria-valuenow="<?= ( 100/( count($user->courses2do)/count($user->visited_ids) ) ) ?>" aria-valuemin="0"
        aria-valuemax="100"><?= count($user->visited_ids) ?> von <?= count($user->courses2do) ?></div>
    </div>
    <?php endif ?>
    <div class="list-group list-group-flush">
      <?php foreach ($user->courses2do as $item) : ?>

      <button type="button"
        class="<?= (in_array($item->id, $user->visited_ids)) ? ("list-group-item list-group-item-success list-group-item-action") : ("list-group-item list-group-item-action") ?>"><?= $item->name ?></button>

      <?php endforeach ?>
    </div>
  </div>
</div>
<?php endif ?>




<?= $this->endSection() ?>