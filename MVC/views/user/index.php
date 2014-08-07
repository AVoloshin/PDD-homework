<?include_once('views/header.php')?>
<div>
    <h2>Пользователи</h2>
    <ul>
        <?php foreach($this->users as $user){?>
            <li><?$user?> </li>
        <?php } ?>
    </ul>
</div>
<? include_once('views/footer.php')?>