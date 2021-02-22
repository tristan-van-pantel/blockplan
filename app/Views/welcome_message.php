<?= $this->extend('layouts'. DIRECTORY_SEPARATOR .'tables') ?>
<?= $this->section('content') ?>
<?php if (logged_in()) : ?>
<style>
.typewriter h4 {
  overflow: hidden; /* Ensures the content is not revealed until the animation */
  border-right: .15em solid orange; /* The typwriter cursor */
  white-space: nowrap; /* Keeps the content on a single line */
  margin: 0 auto; /* Gives that scrolling effect as the typing happens */
  letter-spacing: .15em; /* Adjust as needed */
  animation: 
    typing 3.5s steps(40, end),
    blink-caret .75s step-end infinite;
}

/* The typing effect */
@keyframes typing {
  from { width: 0 }
  to { width: 100% }
}

/* The typewriter cursor effect */
@keyframes blink-caret {
  from, to { border-color: transparent }
  50% { border-color: orange; }
}</style>

<div class="typewriter">
<h4>Guten Tag</h4>
</div>
<div class="typewriter">
<h4><?= (user()->firstname && user()->lastname ) ? (user()->firstname . ' '. user()->lastname) : (user()->username) ?> </h4>
</div>
<div class="typewriter">
<h4>Login erfolgreich..</h4>
</div>








    <?php else : ?>
    Diese Seite ist nur für angemeldete Nutzer.
    <?php endif ?>




    <?= $this->endSection() ?>