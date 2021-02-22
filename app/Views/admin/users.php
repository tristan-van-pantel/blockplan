<?=$this->extend('layouts' . DIRECTORY_SEPARATOR . 'admin')?>
<?=$this->section('content')?>

<div class="card text-white bg-dark">
  <h2 class="card-header">Administrate Users</h2>
  <div class="card-body">
    <table class="table table-striped table-dark" id="datatable">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Name</th>
          <th scope="col">First Name</th>
          <th scope="col">Last Name</th>
          <th scope="col">Email</th>
          <th scope="col">Roles</th>
          <th scope="col">aktiv?</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($users->findAll())): ?>
        <?php foreach ($users->findAll() as $user): ?>

        <tr class="<?= ($user->active == 0 ) ? ('table-danger') : ('')?>">
          <th scope="row"><?=$user->id?></th>
          <td><?=$user->username?></td>
          <td><?=$user->firstname?></td>
          <td><?=$user->lastname?></td>
          <td><?=$user->email?></td>
          <td>
            <?php if (!empty($user->roles)): ?>
            <?=implode(', ', $user->roles)?>


            <?php endif?>
          </td>
          <td><?= ($user->active == 0) ? ('nein') : ('ja') ?></td>
          <td>
            <a href="<?=route_to('edit', $user->id)?>"><button type="button"
                class="btn btn-primary btn-sm float-left">Edit</button></a>

            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#Modal<?=$user->id?>"
              class="float-left">Delete</button>
              <?php if ($user->active == 1) : ?>
              <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#ModalDeactivate<?=$user->id?>"
              class="float-left">Deactivate</button>
              <?php else : ?>
                <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#ModalDeactivate<?=$user->id?>"
              class="float-left">Activate</button>
                <?php endif ?>

          </td>
        </tr>

        <!-- Modal Delete -->
        <div class="modal fade" id="Modal<?=$user->id?>" tabindex="-1" role="dialog"
          aria-labelledby="Modal<?=$user->id?>ModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="Modal<?=$user->id?>ModalLabel">Achtung</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" id="modal-body">
               <p> Wollen Sie den Benutzer <?=(!empty($user->firstname) && !(empty($user->lastname))) ? ($user->firstname . ' ' . $user->lastname . ' ') : ($user->username . ' ')?>wirklich löschen?</p>
               <p><strong>Eine gelöscher Nutzer wird in der Datenbak gelöscht. Dies sollten Sie nur tun, wenn Sie dies explizit wünschen. Wollen Sie einen Nutzer, beispielsweise eine(n) SchülerIn, der/die absolviert hat lediglich von der Nutzung der Plattform ausschließen, so nutzen Sie  die Funktion der Deaktivierung.</strong></p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Abbrechen</button>
                <form action="<?=route_to('destroy')?>" method="post" class="float-left">
                  <?=csrf_field()?>
                  <input type="hidden" name="user" value="<?=$user->id?>" />
                  <button type="submit" class="btn btn-danger">Delete</button>
                </form>
              </div>
            </div>
          </div>
        </div>


                <!-- Modal Deactivate -->
                <div class="modal fade" id="ModalDeactivate<?=$user->id?>" tabindex="-1" role="dialog"
          aria-labelledby="Modal<?=$user->id?>ModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="Modal<?=$user->id?>ModalLabel">Achtung</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" id="modal-body">
              <?php if ($user->active == 1) : ?>
               <p> Wollen Sie den Benutzer <?=(!empty($user->firstname) && !(empty($user->lastname))) ? ($user->firstname . ' ' . $user->lastname . ' ') : ($user->username . ' ')?>wirklich deaktivieren?</p>
               <p><strong>Nutzen Sie diese Funktion, wenn sie eine(n) SchülerIn/DozentIn in den Ruhestand schicken wollen, bzw. von der Nutzung der Plattform ausschließen wollen, jedoch nicht aus den Archiven löschen wollen.</strong></p>
               <p><strong>Wenn Sie eine ganze Klasse, die absolviert hat, deaktivieren wollen, so gehen Sie den Weg über das Menü "Klassenverwaltung".</strong></p>
               <?php else : ?>
                <p> Wollen Sie den Benutzer <?=(!empty($user->firstname) && !(empty($user->lastname))) ? ($user->firstname . ' ' . $user->lastname . ' ') : ($user->username . ' ')?>wirklich aktivieren?</p>
                <p><strong>Der Benutzer erhält damit wieder Zugang zu dieser Plattform.</strong></p>
               <?php endif ?>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Abbrechen</button>
                <form action="<?=route_to('deactivateuser')?>" method="post" class="float-left">
                  <?=csrf_field()?>
                  <input type="hidden" name="user" value="<?=$user->id?>" />
                  <input type="hidden" name="active" value="<?=$user->active?>" />
                  <?php if ($user->active == 1) : ?>
                  <button type="submit" class="btn btn-warning">Deactivate</button>
               <?php else : ?>
                <button type="submit" class="btn btn-success">Activate</button>
                <?php endif ?>                  
                </form>
              </div>
            </div>
          </div>
        </div>

        <?php endforeach?>
        <?php endif?>



      </tbody>
    </table>

  </div>
</div>




<script>
  $(document).ready(function () {
    $('#datatable').DataTable();
  });
</script>
<?=$this->endSection()?>