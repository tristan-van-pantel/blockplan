<?= $this->extend('layouts'. DIRECTORY_SEPARATOR .'admin') ?>
<?= $this->section('content') ?>

<div class="card text-white bg-dark">


    <h2 class="card-header">Edit <?= $vacation->name ?></h2>




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

        <form action="<?= route_to('insertvacationedit') ?>" method="post">
            <?= csrf_field() ?>
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" placeholder="Enter name" name="name"
                    value="<?= $vacation->name ?>">
            </div>
            <div class="form-group">
                <label for="begin">Begin</label>
                <input type="date" class="form-control" id="begin_id" placeholder="Enter abbreviation" name="begin"
                    value="<?= date('Y-m-d', strtotime($vacation->begin)) ?>">
            </div>
            <div class="form-group">
                <label for="end">End</label>
                <input type="date" class="form-control" id="end_id" placeholder="Enter abbreviation" name="end"
                    value="<?= date('Y-m-d', strtotime($vacation->end)) ?>">
            </div>
            <div class="form-group">
                <label for="affected_classes">affected classes</label>
                <select multiple class="form-control" id="affected_classes_id" name="affected_classes[]">
                    <?php if (!empty($classes)) : ?>
                    <?php foreach ($classes as $class) : ?>
                    <option value="<?= $class->id ?>" <?php foreach ($oldClassIds as $old) : ?>
                        <?= ($class->id === $old) ? ("selected") : ("") ?> <?php endforeach ?>><?= $class->name ?>
                    </option>
                    <?php endforeach ?>
                    <?php endif ?>
                </select>
            </div>

            <input type="hidden" name="vacation_id" value="<?= $vacation->id ?>" />

            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="<?= route_to('vacation') ?>"><button type="button" class="btn btn-secondary">Back</button></a>
        </form>


    </div>
</div>


<?= $this->endSection() ?>