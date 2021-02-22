<?= $this->extend('layouts'. DIRECTORY_SEPARATOR .'admin') ?>
<?= $this->section('content') ?>

<div class="card text-white bg-dark">

    <h2 class="card-header">Aktive Dozenten</h2>
    <div class="card-body">
        <?php if (session()->has('message')) : ?>
        <div class="alert alert-success">
            <?= session('message') ?>
        </div>
        <?php endif ?>

        <?php if (session()->has('error')) : ?>
        <div class="alert alert-danger">
            <?= session('error') ?>
        </div>
        <?php endif ?>

        <?php if (session()->has('errors')) : ?>
        <ul class="alert alert-danger">
            <?php foreach (session('errors') as $error) : ?>
            <li><?= $error ?></li>
            <?php endforeach ?>
        </ul>
        <?php endif ?>

        <table class="table table-striped table-dark" id="datatable">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Current/Next Course</th>
                    <th scope="col">Begin</th>
                    <th scope="col">End</th>

                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($lecturers)) : ?>
                <?php foreach ($lecturers as $lecturer) : ?>
                <tr>
                    <th scope="row"><?= $lecturer->id ?></th>
                    <td><?= $lecturer->username ?></td>
                    <td><?= $lecturer->firstname ?></td>
                    <td><?= $lecturer->lastname ?></td>
                    <td><?= $lecturer->email ?></td>

                    <td>
                        <?php if (!empty($lecturer->courses)) : ?>
                        <?php foreach ($lecturer->courses as $course) : ?>

                        <?= $lecturer->courses[0]->courses_name ?>

                        <?php endforeach ?>
                        <?php endif ?>
                    </td>
                    <td>
                        <?php if (!empty($lecturer->courses)) : ?>



                        <?= date("d.m.Y", strtotime($lecturer->courses[0]->begin)) ?>



                        <?php endif ?>
                    </td>
                    <td>
                    <?php if (!empty($lecturer->courses)) : ?>
                        

                        
                        <?= date("d.m.Y", strtotime($lecturer->courses[0]->end)) ?>
                

                        
                        <?php endif ?>
                    </td>
                    </td>
                    <td>

                        <form action="<?= route_to('editlecturer', $lecturer->id) ?>" method="post" class="float-left">
                            <?= csrf_field() ?>
                            <input type="hidden" name="lecturer_classes_id"
                                value="<?= (!empty($lecturer->classes_id)) ? ($lecturer->classes_id) : ("") ?>" />
                            <input type="hidden" name="lecturerid"
                                value="<?= (!empty($lecturer->id)) ? ($lecturer->id) : ("") ?>" />
                            <button type="submit" class="btn btn-warning btn-sm">Edit</button>
                        </form>
                    </td>
                </tr>



                <?php endforeach ?>
                <?php endif ?>







            </tbody>
        </table>
    </div>
</div>
<script>
        $(document).ready(function () {
          
          $('#datatable').DataTable({
            columnDefs: [{
              type: 'de_date',
              targets: 6
            }],
            "order": [
              [6, "desc"]
            ]
          });
        });
      </script>
<?= $this->endSection() ?>