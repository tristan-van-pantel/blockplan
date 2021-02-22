<?= $this->extend('layouts'. DIRECTORY_SEPARATOR .'admin') ?>
<?= $this->section('content') ?>

<div class="card text-white bg-dark mb-3">
    <h3 class="card-header text-white bg-dark">1. Schritt
    </h3>

    <div class="card-body">

        <?= ( !empty($user->firstname && !empty($user->lastname)) ) ? ($user->firstname .' '. $user->lastname) : ($user->username) ?>
        meldet sich ab <?= date('d.m.Y', strtotime($notification->begin)) ?>
        &nbspkrankheitsbedingt vom Unterricht ab. Eingereicht wurde die Abmeldung am
        <?= date('d.m.Y', strtotime($notification->created_at)) ?>
    </div>
</div>

<div class="card text-white bg-dark mb-3">
    <h3 class="card-header text-white bg-dark">2. Schritt
    </h3>

    <div class="card-body">
        <?php if (empty($health_certificate)) : ?>
        <div class="alert alert-danger" role="alert">Bisher wurde noch kein Krankenschein eingereicht.</div>

        <?php else : ?>

        <a class="btn btn-primary" target="_blank" rel="noopener noreferrer"
            href="<?= base_url() .'/assets/uploads/health_certificates/'.$health_certificate[0]->filename ?>"><i
                class="far fa-eye fa-lg"></i>&nbsp Krankenschein
            betrachten</a>
        <p><small>
                Eingereicht am: <?= date('d.m.Y', strtotime($health_certificate[0]->created_at)) ?></small>
        </p>
        <?= ($health_certificate[0]->time_ago < -2) ? ('<div class="alert alert-danger" role="alert"><h3>Achtung: der Krankenschein wurde mehr als drei Tage nach der Krankmeldung hochgeladen</h3></div>') : ('') ?>





        <?php endif ?>
    </div>
</div>

<div class="card text-white bg-dark mb-3">
    <h3 class="card-header text-white bg-dark">3. Schritt
    </h3>

    <div class="card-body">
        <?php if (empty($illness_form)) : ?>
        <div class="alert alert-danger" role="alert">Bisher wurde noch kein Formular eingereicht.</div>


        <?php else : ?>
        <a class="btn btn-primary" target="_blank" rel="noopener noreferrer"
            href="<?= base_url() .'/assets/uploads/illness_forms/'.$illness_form[0]->filename ?>"><i
                class="far fa-eye fa-lg"></i>&nbsp Formular
            betrachten</a>

        <p><small>
                Eingereicht am: <?= date('d.m.Y', strtotime($illness_form[0]->created_at)) ?></small>
        </p>
        <?= ($illness_form[0]->time_ago < -2) ? ('<div class="alert alert-danger" role="alert"><h3>Achtung: das Formular wurde mehr als drei Tage nach der Krankmeldung hochgeladen</h3></div>') : ('') ?>





        <?php endif ?>
    </div>
</div>



<div class="card text-white bg-dark mb-3">
    <h3 class="card-header text-white bg-dark">4. Schritt
    </h3>

    <div class="card-body">
        <?php if (session()->has('message')) : ?>
        <div class="alert alert-danger">
            <?= session('message') ?>
        </div>
        <?php endif ?>

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
        <form action="<?= route_to('notificationenddate') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="notification_id" value="<?= $notification->id ?>" />
            <div class="form-group">
                <label for="endid">Enddatum aus Krankenschein entnehmen:</label>
                <input type="date" name="end" id="endid" class="form-control"
                    value="<?= (!empty($notification->end)) ? (date('Y-m-d', strtotime($notification->end))) : ('') ?>">
            </div>


            <button type="submit" class="btn btn-primary">Enddatum eintragen/bearbeiten</button>

        </form>


    </div>
</div>


<div class="card text-white bg-dark mb-3">
    <h3 class="card-header text-white bg-dark">Vorgang abschließen
    </h3>

    <div class="card-body">
        <?php if (!empty($notification->end)) : ?><p>
            Wenn Sie alle Schritte validiert haben, können Sie den Vorgang der Krankmeldung hier für den Schüler
            abschließen.</p>
        <p><button type="button" class="btn btn-success btn-lg float-left" data-toggle="modal"
                data-target="#Modal">Entschuldigte Fehlzeit eintragen</button>
            <button type="button" class="btn btn-danger btn-lg float-right" data-toggle="modal"
                data-target="#Modal2">unentschuldigte Fehlzeit eintragen</button></p>
    </div>
    <?php if (!empty($health_certificate) && !empty($illness_form)) : ?>
    <?php if (($illness_form[0]->time_ago < -2) || ($health_certificate[0]->time_ago < -2)) : ?><div
        class="card-footer">
        <b>Hinweis:
            Wenn Krankenschein und Formular nicht auf analogem Weg eingegangen sind, ist die Fehlzeit als unentschuldigt
            (da nicht fristgerecht) zu werten.</b></div>
    <?php endif ?>
    <?php endif ?>
    <?php else : ?>
    Um den Vorgang abschließen zu können, müssen Sie erst ein Enddatum eingetragen haben.


    <?php endif ?>

</div>

<!-- Modal -->
<div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="ModalModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalModalLabel">Achtung</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal-body">
                Wollen Sie den Vorgang wirklich abschließen? Haben Sie Krankenschein, Formular und Fristeinhaltung
                validiert?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Abbrechen</button>
                <form action="<?= route_to('completenotification') ?>" method="post" class="float-left">
                    <?= csrf_field() ?>
                    <input type="hidden" name="notification_id" value="<?= $notification->id ?>" />




                    <button type="submit" class="btn btn-warning">Vorgang abschließen</button>
                </form>
            </div>
        </div>
    </div>
</div>




<!-- Modal2 -->
<div class="modal fade" id="Modal2" tabindex="-1" role="dialog" aria-labelledby="ModalModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalModalLabel">Achtung</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal-body">
                Wollen Sie den Vorgang wirklich abschließen? Haben Sie Krankenschein, Formular und Fristeinhaltung
                validiert?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Abbrechen</button>
                <form action="<?= route_to('completenotificationunexcused') ?>" method="post" class="float-left">
                    <?= csrf_field() ?>
                    <input type="hidden" name="notification_id" value="<?= $notification->id ?>" />




                    <button type="submit" class="btn btn-warning">Vorgang abschließen</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>