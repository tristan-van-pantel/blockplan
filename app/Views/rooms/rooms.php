<?= $this->extend('layouts'. DIRECTORY_SEPARATOR .'admin') ?>
<?= $this->section('content') ?> 

                <div class="card text-white bg-dark">

                    

    <h2 class="card-header">Rooms</h2>
                    

                    <div class="card-body">
                    <table class="table table-striped table-dark" id="datatable">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Capacity</th>
            <th scope="col">Equipment</th>
            <th scope="col">Actions</th>

        </tr>
        </thead>
        <tbody>
        <?php if ($rooms) : ?>

            <?php foreach ($rooms as $room) : ?>
                <tr>

            <th scope="row"><?= $room->id ?></th>
            <td><?= $room->name ?></td>
            <td><?= $room->capacity ?></td>
            <td><?= $room->installed_equipment ?></td>
            <td>
                        <a href="<?= route_to('editroom', $room->id) ?>"><button type="button" class="btn btn-primary float-left btn-sm">Edit</button></a>
                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#Modal<?= $room->id ?>"  class="float-left">Delete</button>

                    </td>
                </tr>

                        <!-- Modal -->
<div class="modal fade" id="Modal<?= $room->id ?>" tabindex="-1" role="dialog" aria-labelledby="Modal<?= $room->id ?>ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="Modal<?= $room->id ?>ModalLabel">Achtung</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modal-body">
        Wollen Sie den Raum wirklich löschen?
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Abbrechen</button>
      <form action="<?= route_to('deleteroom') ?>" method="post" class="float-left">
                            <?= csrf_field() ?>
                            <input type="hidden" name="room" value="<?= $room->id ?>" />
                            <button type="submit" class="btn btn-warning btn-sm">Delete</button>
                        </form>
      </div>
    </div>
  </div>
</div>

        <?php endforeach ?>

            <?php endif ?>

        </tbody>
                    </table>

                    
                    
                    <a href="<?= route_to('addroom') ?>"><button type="button" class="btn btn-secondary btn-lg">Add new Room</button></a>


                       

                    </div>
                </div>



<script>
    $(document).ready( function () {
    $('#datatable').DataTable();
} );

</script>




<?= $this->endSection() ?>





