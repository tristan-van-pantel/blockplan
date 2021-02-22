<?= $this->extend('layouts'. DIRECTORY_SEPARATOR .'admin') ?>
<?= $this->section('content') ?>

<div class="card text-white bg-dark">


    <h2 class="card-header">Vacations </h2>




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
                    <th scope="col">Begin</th>
                    <th scope="col">End</th>
                    <th scope="col">affected classes</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>

                <?php if (!empty($vacations)) : ?>
                <?php foreach ($vacations as $vacation) : ?>
                <tr>
                    <th scope="row"><?= $vacation->id ?></th>
                    <td><?= $vacation->name ?></td>
                    <td><?= date("d.m.Y", strtotime($vacation->begin)) ?></td>
                    <td><?= date("d.m.Y", strtotime($vacation->end)) ?></td>
                    <td>
                        
                        <?php foreach ($vacationModel->findClassByVacationId($vacation->id) as $class) : ?>
                        <li style="list-style:none"><?= $class->name ?></li>
                        <?php endforeach ?>
                    </td>
                    <td>
                    <a href="<?= route_to('editvacation', $vacation->id) ?>"><button type="button" class="btn btn-primary btn-sm float-left">Edit</button></a>

                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                            data-target="#Modal<?= $vacation->id ?>" class="float-left">Delete</button>
                    </td>
                </tr>
                <!-- Modal -->
                <div class="modal fade" id="Modal<?= $vacation->id ?>" tabindex="-1" role="dialog"
                    aria-labelledby="Modal<?= $vacation->id ?>ModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="Modal<?= $vacation->id ?>ModalLabel">Achtung</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="modal-body">
                                Wollen Sie die/den Ferien/Feiertag(e) wirklich löschen?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Abbrechen</button>
                                <form action="<?= route_to('deletevacation') ?>" method="post">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="vacation_id" value="<?= $vacation->id ?>" />
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

        <a href="<?= route_to('addvacation') ?>"><button type="button" class="btn btn-secondary btn-lg">Add new
                vacation</button></a>


    </div>
</div>

<script>
    $('#datatable').DataTable({
        columnDefs: [{
            type: 'de_date',
            targets: 3
        }],
        "order": [
            [3, "desc"]
        ]
    });
</script>


<?= $this->endSection() ?>