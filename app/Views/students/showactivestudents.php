<?= $this->extend('layouts'. DIRECTORY_SEPARATOR .'admin') ?>
<?= $this->section('content') ?> 

<div class="card text-white bg-dark">

                    <h2 class="card-header">Show enrolled Students</h2>
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
                                <th scope="col">Class</th>
                                <th scope="col">Current Course</th>
                                <th scope="col">Action</th>
                            </tr>
</thead>
<tbody>
                            <?php foreach ($students as $student) : ?>
                            <tr>
                            <th scope="row"><?= $student->id ?></th>
                            <td><?= $student->username ?></td>
                            <td><?= $student->firstname ?></td>
                            <td><?= $student->lastname ?></td>
                            <td><?= $student->email ?></td>
                            <td><?= (!empty($student->classes_id)) ?(($classModel->find(intval($student->classes_id)))->name) : ("") ?></td>                            
                            <td>
                            
                            <?php foreach ($classModel->getClassesCurrentCourseAndDate($student->classes_id) as $course) : ?>

                            <?= $course->name ?>
                            bis:
                            <?= date("d.m.Y", strtotime($course->end)) ?>

                            <?php endforeach ?>
                            </td>
                            <td>
                            
                            <form action="<?= route_to('editstudent', $student->id) ?>" method="post" class="float-left">
                            <?= csrf_field() ?>
                            <input type="hidden" name="student_classes_id" value="<?= (!empty($student->classes_id)) ? ($student->classes_id) : ("") ?>" />
                            <input type="hidden" name="studentid" value="<?= (!empty($student->id)) ? ($student->id) : ("") ?>" />
                            <button type="submit" class="btn btn-warning btn-sm">Edit</button>
                            </form>
                            </td>
                            </tr>



<?php endforeach ?>
                            
                            





                            </tbody>
                        </table>

                    </div>
                </div>

<script>
    $(document).ready( function () {
    $('#datatable').DataTable();
} );

</script>


<?= $this->endSection() ?>








