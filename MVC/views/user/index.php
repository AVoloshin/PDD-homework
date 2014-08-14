<div>
    <h2>Пользователи</h2>
    <ul>
        <?php var_dump($this->users);foreach($this->users as $value){?>
            <li><?php echo $value?> </li>
        <?php } ?>
    </ul>
</div>