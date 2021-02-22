<?= $this->extend('layouts'. DIRECTORY_SEPARATOR .'admin') ?>
<?= $this->section('content') ?>



<div class="card text-white bg-warning mb-3">
    <h2 class="card-header">Offene Krankmeldungen</h2>
    <div class="card-body">
        <table class="table table-borderless" id="datatable">
            <thead>
                <tr>

                    <th scope="col">Username</th>
                    <th scope="col">Firstname</th>
                    <th scope="col">Lastname</th>
                    <th scope="col">Begin</th>
                    <th scope="col">Actions</th>

                </tr>
            </thead>
            <?php if (!empty($open_notifications)) : ?>

            <?php foreach ($open_notifications as $open_notification) : ?>
            <tr>

                <td><?= $open_notification->username ?></td>
                <td><?= (!empty($open_notification->firstname)) ? ($open_notification->firstname) : ('')  ?></td>
                <td><?= (!empty($open_notification->lastname)) ? ($open_notification->lastname) : ('')  ?></td>
                <td><?= (!empty($open_notification->begin)) ? (date("d.m.Y", strtotime($open_notification->begin))) : ('')  ?>
                </td>
                <td>
                    <a href="<?= route_to('viewnotification', $open_notification->id) ?>"><button type="button"
                            class="btn btn-primary btn-sm float-left">Edit</button></a>
                </td>


            </tr>
            <?php endforeach ?>


            <?php endif ?>
        </table>
    </div>
</div>




<div class="card text-white bg-success mb-3">
    <h2 class="card-header">Entschuldigte Krankmeldungen</h2>
    <div class="card-body">
        <table class="table text-white table-borderless" id="datatable2">
            <thead>
                <tr>

                    <th scope="col">Username</th>
                    <th scope="col">Firstname</th>
                    <th scope="col">Lastname</th>
                    <th scope="col">Begin</th>
                    <th scope="col">End</th>
                    <th scope="col">Actions</th>


                </tr>
            </thead>
            <?php if (!empty($excused_notifications)) : ?>

            <?php foreach ($excused_notifications as $excused_notification) : ?>
            <tr>

                <td><?= $excused_notification->username ?></td>
                <td><?= (!empty($excused_notification->firstname)) ? ($excused_notification->firstname) : ('')  ?></td>
                <td><?= (!empty($excused_notification->lastname)) ? ($excused_notification->lastname) : ('')  ?></td>
                <td><?= (!empty($excused_notification->begin)) ? (date("d.m.Y", strtotime($excused_notification->begin))) : ('')  ?>
                </td>
                <td>
                    <?= (!empty($excused_notification->end)) ? (date("d.m.Y", strtotime($excused_notification->end))) : ('')  ?>
                </td>
                <td>
                <a href="<?= route_to('editnotification', $excused_notification->id) ?>"><button type="button"
                            class="btn btn-primary btn-sm float-left">Edit</button></a>
                </td>


            </tr>
            <?php endforeach ?>


            <?php endif ?>
        </table>
    </div>
</div>




<div class="card text-white bg-danger mb-3">
    <h2 class="card-header">Unentschuldigte Krankmeldungen</h2>
    <div class="card-body">
        <table class="table text-white table-borderless" id="datatable3">
            <thead>
                <tr>

                    <th scope="col">Username</th>
                    <th scope="col">Firstname</th>
                    <th scope="col">Lastname</th>
                    <th scope="col">Begin</th>
                    <th scope="col">End</th>
                    <th scope="col">Actions</th>

                </tr>
            </thead>
            <?php if (!empty($unexcused_notifications)) : ?>

            <?php foreach ($unexcused_notifications as $unexcused_notification) : ?>
            <tr>

                <td><?= $unexcused_notification->username ?></td>
                <td><?= (!empty($unexcused_notification->firstname)) ? ($unexcused_notification->firstname) : ('')  ?></td>
                <td><?= (!empty($unexcused_notification->lastname)) ? ($unexcused_notification->lastname) : ('')  ?></td>
                <td><?= (!empty($unexcused_notification->begin)) ? (date("d.m.Y", strtotime($unexcused_notification->begin))) : ('')  ?>
                </td>
                <td>
                    <?= (!empty($unexcused_notification->end)) ? (date("d.m.Y", strtotime($unexcused_notification->end))) : ('')  ?>
                </td>
                <td>
                <a href="<?= route_to('editnotification', $unexcused_notification->id) ?>"><button type="button"
                            class="btn btn-primary btn-sm float-left">Edit</button></a>
                </td>


            </tr>
            <?php endforeach ?>


            <?php endif ?>
        </table>
    </div>
</div>




          <script>
        $(document).ready(function () {
            $('#datatable').DataTable({
            columnDefs: [{
              type: 'de_date',
              targets: 2
            }],
            "order": [
              [2, "desc"]
            ]
          });
          $('#datatable2').DataTable({
            columnDefs: [{
              type: 'de_date',
              targets: 3
            }],
            "order": [
              [3, "desc"]
            ]
          });
          $('#datatable3').DataTable({
            columnDefs: [{
              type: 'de_date',
              targets: 3
            }],
            "order": [
              [3, "desc"]
            ]
          });
        });
      </script>


<?= $this->endSection() ?>