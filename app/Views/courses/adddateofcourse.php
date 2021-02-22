<?= $this->extend('layouts'. DIRECTORY_SEPARATOR .'admin') ?>
<?= $this->section('content') ?> 

                <div class="card text-white bg-dark">
                    <h2 class="card-header">Add new date of Course</h2>
                    <div class="card-body">

    <?php if (session()->has('success')) : ?>
        <div class="alert alert-success">
            <?= session('success') ?>
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




    <form action="<?= route_to('commitInsertDateOfCourse') ?>" method="post">
        <?= csrf_field() ?>
        <div class="form-group">
            <label for="exampleFormControlSelect1">Fach wählen:</label>
            <select class="form-control" id="exampleFormControlSelect1" name="courses_id">
            <?php if ($courses) : ?>
                <?php foreach ($courses as $course) : ?>

                    <option value="<?= $course->id ?>"><?= $course->name ?></option>

                <?php endforeach ?>
            <?php endif ?>
            </select>
        </div>
        <div class="form-group">
            <label for="exampleFormControlSelect2">
            Dozenten wählen:</label>
            <select class="form-control" id="exampleFormControlSelect2" name="users_id">
            <?php if ($lecturers) : ?>
                <?php foreach ($lecturers as $lecturer) : ?>

                    <option value="<?= $lecturer->id ?>"><?= (!empty($lecturer->firstname) && !empty($lecturer->lastname)) ? ($lecturer->firstname . ' ' . $lecturer->lastname) : ($lecturer->username) ?></option>

                <?php endforeach ?>
            <?php endif ?>
            </select>
        </div>
        <div class="form-group">
            <label for="exampleFormControlSelect1">Select room</label>
            <select class="form-control" id="exampleFormControlSelect1" name="rooms_id">
            <?php if ($rooms) : ?>
                <?php foreach ($rooms as $room) : ?>

                    <option value="<?= $room->id ?>"><?= $room->name ?> - capacity: <?= $room->capacity ?></option>

                <?php endforeach ?>
            <?php endif ?>

            </select>
        </div>
        <div class="form-group">
            <label for="beginid">Begin:</label>
            <input type="date" id="beginid" name="begin"  class="form-control">
        </div>
        <div class="form-group">
            <label for="endid">End:</label>
            <input type="date" id="endid" name="end" class="form-control">
        </div>
        <div class="form-group">
  <label for="selclasses">Select classes</label>
    <select multiple class="form-control" name="combinedClasses[]" id="selclasses">
    <?php if ($classes) : ?>
                <?php foreach ($classes as $class) : ?>

                    <option value="<?= $class->id ?>"><?= $class->name ?></option>

                <?php endforeach ?>
            <?php endif ?>
    </select>
  </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="<?= route_to('courses') ?>"><button type="button" class="btn btn-secondary">Back</button></a>
    </form>


                    </div>
                </div>
                <?= $this->endSection() ?>