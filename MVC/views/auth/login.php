<form method="post">
    <label for="login">Login</label>
    <input id="login" name="login" value="<?php $this->login ?>"/>
    <br />
    <label for="password">Password</label>
    <input id="password" name="password" type="password" />
    <br />
    <input type="submit" value="Войти">
    <br />
    <p><?php echo $this->msg ?></p>
</form>