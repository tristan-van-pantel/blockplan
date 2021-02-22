<?= $this->extend('layouts'. DIRECTORY_SEPARATOR .'tables') ?>
<?= $this->section('content') ?>
<div class="card mb-3">
    <h3 class="card-header text-white bg-dark">Krankmeldung
    </h3>

    <div class="card-body">
        <!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->
        <?php if ($step === 1) : ?>
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
        <h3>1. Schitt: </h3>
        <h4>informieren Sie uns über schnellstmöglich über Ihre Abwesenheit</h4>
        <form action="<?= route_to('callinsick') ?>" method="post">
            <?= csrf_field() ?>
            <div class="form-group">
                <label for="begin">Anfang der Absesenheit (möglich: ab heute oder ab morgen)</label>
                <input type="date" class="form-control" id="begin_id" name="begin" min="<?= date ('Y-m-d') ?>"
                    max="<?= date("Y-m-d", strtotime('tomorrow')) ?>">
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="exampleCheck1" name="checkbox">
                <label class="form-check-label" for="exampleCheck1">Haken = ich will mich offiziell abwesend melden</label>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-step-forward"></i></button>
        </form>

        <!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------- -->


        <?php elseif ($step === 2) : ?>
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
        <h3>2. Schitt: </h3>
        <h4>laden Sie Ihre Krankschreibung hoch. Wichtig: Dies muss innherhalb von drei Tagen nach Beginn des ersten
            Fehltages geschehen, sonst handelt es sich um unentschuldigtes Fehlen!</h4>
        <p><small>idealerweise als PDF, notfalls als JPG oder PNG</small></p>
        <form action="<?= route_to('uploadhealtcertificate') ?>" method="post" enctype="multipart/form-data"
            class="mb-5">
            <?= csrf_field() ?>
            <input type="hidden" name="notification_id"
                value="<?= (!empty($notification_id)) ? ($notification_id) : (null) ?>" />




            <!-- <textarea id="summernote" name="post"></textarea> -->
            <div class="form-group">
                <input type="file" name="file" id="file" class="inputfile" />

            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-step-forward"></i></button>
        </form>




        <?php elseif ($step === 3) : ?>
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
        <h3>3. Schitt: </h3>
        <h4>laden Sie Ihr ausgefülltes und unterschriebenes Abwesenheitsformular hoch</h4>
        <p><small>idealerweise als PDF, notfalls als JPG oder PNG</small></p>
        <form action="<?= route_to('uploadillnessform') ?>" method="post" enctype="multipart/form-data" class="mb-5">
            <?= csrf_field() ?>
            <input type="hidden" name="notification_id"
                value="<?= (!empty($notification_id)) ? ($notification_id) : (null) ?>" />




            <!-- <textarea id="summernote" name="post"></textarea> -->
            <div class="form-group">
                <input type="file" name="file" id="file" class="inputfile" />

            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-step-forward"></i></button>
        </form>





        <?php elseif ($step === 4) : ?>
        Vielen Dank. Sie haben sich krankgemeldet, den Krankenschein hochgeladen sowie unser Formular hochgeladen.
        Jetzt bearbeiten wir den Vorgang. Innherhalb der nächsten 24 Stunden (Werktags) oder bis zum Mittag des
        kommenden Montags sollte Ihre Krankmeldung unter den "bisherigen Fehlzeiten" auftauchen.
        falls nicht, kontaktieren Sie uns bitte telefonisch.
        Wir wünschen Ihnen gute Besserung!


        <?php endif ?>



    </div>
</div>







<div class="card mb-3">
    <h3 class="card-header text-white bg-dark">bisherige Fehlzeiten
    </h3>

    <div class="card-body">
        <?php if (empty($completed_notifications)) : ?>
        Keine Fehzeiten vorhanden.
        <?php else : ?>
        <div class="table-responsive-lg">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Anfang</th>
                        <th scope="col">Ende</th>
                        <th scope="col">Status</th>
                        <th scope="col">Fehltage</th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($completed_notifications as $completed) : ?>
                    <tr <?= ($completed->intime == false) ? ('class="table-danger"') : ('class="table-success"') ?>>

                        <td><?= date('d.m.Y', strtotime($completed->begin)) ?></td>
                        <td><?= date('d.m.Y', strtotime($completed->end)) ?></td>
                        <td><?= ($completed->intime == false) ? ('unentschuldigt') : ('entschuldigt') ?></td>
                        <td><?= $completed->days ?></td>


                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
        <?php endif ?>

    </div>
</div>

<?= $this->endSection() ?>