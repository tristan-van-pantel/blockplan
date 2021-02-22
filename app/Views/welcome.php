<?= $this->extend('welcome_message') ?>

<?= $this->section('main') ?>
hsadjsdh
<?php if (logged_in()) : ?>
    <form action='/logout' method="get">
        <button type="submit">Logout</button>
    </form>
<?php endif ?>

<?= $this->endSection() ?>