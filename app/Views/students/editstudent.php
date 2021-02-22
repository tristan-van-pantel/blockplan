<?= $this->extend('layouts'. DIRECTORY_SEPARATOR .'admin') ?>
<?= $this->section('content') ?> 

                <div class="card text-white bg-dark">
                <?php if (!empty($studentsClassname)) : ?>
                    <h2 class="card-header">Edit Student <?= $student->username ?> from class <?= $studentsClassname ?> </h2>
                    
<?php else : ?>
    <h2 class="card-header">Edit student (without class) named <?= $student->username ?></h2>
                    <?php endif ?>

                    <div class="card-body">
                        

                    <?php if (session()->has('message')) : ?>
        <div class="alert alert-danger">
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


<?php if (!empty($student)) : ?>
                    <form action="<?= route_to('updateStudent') ?>" method="post">
                    <?= csrf_field() ?>
                     <input type="hidden" name="userid" value="<?= $student->id ?>" />
                     <input type="hidden" name="oldclass" value="<?= $studentsClassId ?>" />


                    <div class="form-group">
                    <label for="nameid">Name</label>
                    <input type="text" class="form-control" name="username" id="nameid" value="<?= $student->username ?>">
                    </div>
                    <div class="form-group">
                    <label for="nameid">First Name</label>
                    <input type="text" class="form-control" name="firstname" id="firstnameid" value="<?= $student->firstname ?>">
                    </div>
                    <div class="form-group">
                    <label for="nameid">Last Name</label>
                    <input type="text" class="form-control" name="lastname" id="lastnameid" value="<?= $student->lastname ?>">
                    </div>
                    <div class="form-group">
                    <label for="nameid">E-Mail</label>
                    <input type="text" class="form-control" name="email" id="emailid" value="<?= $student->email ?>">
                    </div>
                    <div class="form-group">
                    <label for="exampleFormControlSelect1">Select Class</label>
                    <select class="form-control" id="exampleFormControlSelect1" name="selectedClass">
                    
                    <?php foreach ($classes as $class) : ?>

                    <option value="<?= $class->id ?>" <?= ($class->name === $studentsClassname) ? ("selected") : ("") ?> ><?= $class->name ?></option>

                    <?php endforeach ?>

                    </select>
</div>


                    <button type="submit" class="btn btn-primary" id="submit">Submit</button>
                    <a href="<?= route_to('showactivestudents') ?>"><button type="button" class="btn btn-secondary">Back</button></a>
                    </form>
                    <?php endif ?>
                       

                    </div>
                </div>

            </div>
        </div>
    </div>

<?= $this->endSection() ?>









