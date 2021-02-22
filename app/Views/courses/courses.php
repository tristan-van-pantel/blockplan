<?= $this->extend('layouts'. DIRECTORY_SEPARATOR .'admin') ?>
<?= $this->section('content') ?>

<div class="card text-white bg-dark">



  <h2 class="card-header">Fächer</h2>


  <div class="card-body">
    <table class="table table-striped table-dark" id="datatable">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Name</th>
          <th scope="col">internal ID</th>
          <th scope="col">Actions</th>

        </tr>
      </thead>
      <tbody>
        <?php if ($courses) : ?>

        <?php foreach ($courses as $course) : ?>
        <tr>

          <th scope="row"><?= $course->id ?></th>
          <td><?= $course->name ?></td>
          <td><?= $course->internal_id ?></td>
          <td>
            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
              data-target="#CourseModal<?= $course->id ?>" class="float-left">Delete</button>
          </td>
        </tr>

        <!-- Modal -->
        <div class="modal fade" id="CourseModal<?= $course->id ?>" tabindex="-1" role="dialog"
          aria-labelledby="CourseModal<?= $course->id ?>ModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="CourseModal<?= $course->id ?>ModalLabel">Achtung</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" id="modal-body">
                Wollen Sie das Fach wirklich löschen?
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Abbrechen</button>
                <form action="<?= route_to('deletecourse') ?>" method="post">
                  <?= csrf_field() ?>
                  <input type="hidden" name="course_id" value="<?= $course->id ?>" />
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



    <a href="<?= route_to('addcourse') ?>"><button type="button" class="btn btn-secondary btn-lg">Add new
        Course</button></a>




  </div>
</div>

</div>
</div>

</div>



<div class="container mt-5">
  <div class="row">
    <div class="col-sm-12 offset-sm">

      <div class="card text-white bg-dark">



        <h2 class="card-header">Kurstermine</h2>


        <div class="card-body">
          <table class="table table-striped table-dark" id="datatable2">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Begin</th>
                <th scope="col">End</th>
                <th scope="col">Lecturer</th>
                <th scope="col">Classroom</th>
                <th scope="col">Classes</th>
                <th scope="col">Actions</th>

              </tr>
            </thead>
            <tbody>
              <?php if ($courses) : ?>

              <?php foreach ($dates as $date) : ?>

              <tr class="<?= ($date->end <= date("Y-m-d H:i:s")) ?("table-danger") : ("") ?>">

                <th scope="row"><?= $date->id ?></th>
                <td>
                  <?php foreach ($datesModel->findCourseNameByDatesId($date->id) as $item ) : ?>

                  <?= $item->name ?>

                  <?php endforeach ?>


                </td>
                <td><?= date("d.m.Y", strtotime($date->begin)) ?></td>
                <td><?= date("d.m.Y", strtotime($date->end)) ?></td>
                <td>
                  <?php foreach ($datesModel->getLecturerByDatesId($date->id) as $item ) : ?>

                    <?= (!empty($item->firstname) && !empty($item->lastname)) ? ($item->firstname . ' ' . $item->lastname) : ($item->username) ?>

                  <?php endforeach ?>

                </td>
                <td>
                  <?php foreach ($datesModel->getRoomByDatesId($date->id) as $item ) : ?>

                  <?= $item->name ?>

                  <?php if (!empty($datesModel->sumClassesSizeByDateId($date->id)[0]->enrolled_students) && $datesModel->sumClassesSizeByDateId($date->id)[0]->enrolled_students != 0) : ?>
                  <div class="progress">
                    <div
                      class="<?= (100/($item->capacity/$datesModel->sumClassesSizeByDateId($date->id)[0]->enrolled_students)>50) ? ("progress-bar progress-bar-striped bg-warning") : ("progress-bar progress-bar-striped bg-success") ?>"
                      role="progressbar"
                      style="width: <?= 100/($item->capacity/$datesModel->sumClassesSizeByDateId($date->id)[0]->enrolled_students) ?>%"
                      aria-valuenow="<?= 100/($item->capacity/$datesModel->sumClassesSizeByDateId($date->id)[0]->enrolled_students) ?>"
                      aria-valuemin="0" aria-valuemax="100"></div>
                  </div>


                  <?php endif ?>



                  <?php endforeach ?>

                </td>
                <td>
                  <!-- <//?php foreach ($datesModel->findClassIdByDatesId($date->id) as $item) : ?>

<li><//?= $item->classes_id ?></li>

<//?php endforeach ?> -->

                  <?php foreach ($datesModel->findClassNameByDatesId($date->id) as $item) : ?>

                  <li style="list-style:none"><span class="small"><?= $item->name ?> (<?= $item->enrolled_students ?>
                      students)</span></li>

                  <?php endforeach ?>






                </td>

                <td>
                  <form action="<?= route_to('editdateofcourse', $date->id) ?>" method="post" class="float-left">
                    <?= csrf_field() ?>
                    <input type="hidden" name="editDateId" value="<?= $date->id ?>" />
                    <button type="submit" class="btn btn-primary btn-sm">Edit</button>
                  </form>

                  <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                    data-target="#Modal<?= $date->id ?>" class="float-left">Delete</button>
                </td>


              </tr>


              <!-- Modal -->
              <div class="modal fade" id="Modal<?= $date->id ?>" tabindex="-1" role="dialog"
                aria-labelledby="Modal<?= $date->id ?>ModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="Modal<?= $date->id ?>ModalLabel">Achtung</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body" id="modal-body">
                      Wollen Sie den Kurs wirklich löschen?
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Abbrechen</button>
                      <form action="<?= route_to('deleteDateOfCourse') ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="deleteDateId" value="<?= $date->id ?>" />
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
          <a href="<?= route_to('insertdateofcourse') ?>"><button type="button" class="btn btn-secondary btn-lg">Add new
              date of Course</button></a>




        </div>
      </div>







      <script>
        $(document).ready(function () {
          $('#datatable').DataTable();
          $('#datatable2').DataTable({
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