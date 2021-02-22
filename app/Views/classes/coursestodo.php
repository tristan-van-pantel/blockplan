<?= $this->extend('layouts'. DIRECTORY_SEPARATOR .'admin') ?>
<?= $this->section('content') ?>

<div class="card text-white bg-dark">
    <h2 class="card-header">Add Courses Todo for <?= $class->name ?></h2>
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
        <?php if (!empty($courses)) : ?>
        <form action="<?= route_to('classescoursestodosave') ?>" method="post">
            <?= csrf_field() ?>
            <div class="form-group">
            <input type="hidden" name="class_id" value="<?= $class->id ?>"/>
                <label for="exampleFormControlSelect2">Select courses for the classes Todo-List </label>
                <select multiple class="form-control" id="exampleFormControlSelect2" size="<?= count($courses) ?>"
                    name="selectedCourses[]">
                    <?php foreach ($courses as $course) : ?>
                    <option value="<?= $course->id ?>" <?= (in_array($course->id, $todoIds)) ? ("selected") : ("") ?>>
                        <?= $course->name ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="<?= route_to('classes') ?>"><button type="button" class="btn btn-secondary">Back</button></a>
        </form>

        <?php else : ?>

        <h3>You first have to create courses, else there is nothing to select</h3>
        <?php endif ?>






    </div>
</div>







<?= $this->endSection() ?>