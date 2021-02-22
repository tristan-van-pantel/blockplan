<?= $this->extend('layouts'. DIRECTORY_SEPARATOR .'admin') ?>
<?= $this->section('content') ?>


<div class="card text-white bg-dark">
  <h2 class="card-header">Klassenverwaltung</h2>
  <div class="card-body">

    <table class="table table-striped table-dark" id="datatable">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Name</th>
          <th scope="col">Begin</th>
          <th scope="col">End</th>
          <th scope="col">Size (in students)</th>
          <th scope="col">Progess</th>
          <th scope="col">Action</th>

        </tr>
      </thead>
      <tbody>


        <?php if (!empty($classes)) : ?>
        <?php foreach ($classes as $class) : ?>
        <tr class="<?= ($class->end <= date("Y-m-d H:i:s")) ?("table-danger") : ("") ?>">
          <th scope="row"><?= $class->id ?></th>
          <td><?= $class->name ?></td>
          <td><?= date("d.m.Y", strtotime($class->begin)) ?></td>
          <td><?= date("d.m.Y", strtotime($class->end)) ?></td>
          <td><?= $class->enrolled_students ?></td>
          <td>
            <?php if ( !empty( $classModel->getClassesVisitedCourses($class->id) ) && !empty( $classModel->getClassesTodoCourses($class->id) ) ) : ?>

              
            <button type="button" class="btn btn-secondary" data-container="body" data-toggle="popover"
              data-placement="top" title="Kurse" data-content="<ul class='list-group list-group-flush'><?php foreach ($classModel->getClassesTodoCourses($class->id) as $item) : ?>
                <li class='list-group-item'><?= $item->name ?>
                <?php foreach ( $classModel->getClassesVisitedCourses($class->id) as $visited_course) : ?>
                <?= ($item->id == $visited_course->id) ? ("<i class='fas fa-check float-right'></i>") : ("") ?>
                <?php endforeach ?></li>
            <?php endforeach ?></ul>">
              <?= count($classModel->getClassesVisitedCourses($class->id)) ?> von
              <?= count($classModel->getClassesTodoCourses($class->id)) ?>
            </button>
            <?php endif ?>
          </td>
          <td>
            <a href=" <?= route_to('editclass', $class->id) ?>"><button type="button"
                class="btn btn-primary float-left btn-sm">Edit</button></a>
            <button type="button" class="btn btn-danger btn-sm float-left" data-toggle="modal"
              data-target="#Modal<?= $class->id ?>">Delete</button>
              <a href=" <?= route_to('classactivation', $class->id) ?>"><button type="button"
                class="btn btn-warning float-left btn-sm">(De)activate</button></a>
            <a href="<?= route_to('classescoursestodo', $class->id) ?>"><button type="button"
                class="btn btn-light float-left btn-sm">Pflichtkurse</button></a>

          </td>


        </tr>

        <!-- Modal -->
        <div class="modal fade" id="Modal<?= $class->id ?>" tabindex="-1" role="dialog"
          aria-labelledby="Modal<?= $class->id ?>ModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="Modal<?= $class->id ?>ModalLabel">Achtung</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" id="modal-body">
                Wollen Sie die Klasse "<?= $class->name . '" ' ?> wirklich löschen?
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Abbrechen</button>
                <form action="<?= route_to('deleteClass') ?>" method="post" class="float-left">
                  <?= csrf_field() ?>
                  <input type="hidden" name="class" value="<?= $class->id ?>" />
                  <button type="submit" class="btn btn-warning">Delete</button>
                </form>
              </div>
            </div>
          </div>
        </div>


        <?php endforeach ?>

        <?php endif ?>



      </tbody>
    </table>
    <a href="<?= route_to('addclass') ?>"><button type="button" class="btn btn-secondary btn-lg">Add new
        Class</button></a>
  </div>
</div>


<script>
  $(document).ready(function () {
    $('#datatable').DataTable({
      columnDefs: [{
        type: 'de_date',
        targets: 3
      }],
      "order": [
        [3, "desc"]
      ],
    });
  });
  $(function () {
    $('[data-toggle="popover"]').popover({
      html: true,
    })
  });
</script>

<?= $this->endSection() ?>