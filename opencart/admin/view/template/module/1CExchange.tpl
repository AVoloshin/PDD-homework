<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <?php
    var_dump($this->goods);
    var_dump($this->ad);
    var_dump($_POST);

    if (isset ($zip_error)) { ?>
    <div class="warning"><?php echo $zip_error; ?></div>
    <?php } ?>

    <div class="box">
        <div class="heading">
            <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>

        </div>
        <div class="content">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                <?php echo $entry_upload; ?>
                        <input type="file" name="uploadfile" accept=".rar" required>
                <?php echo $entry_checkbox; ?>
                        <input type="checkbox" name="cleardb" value="Очистить БД">
                        <input type="submit" value="Загрузить">
            </form>
            <?php if(isset($text_complete)) { ?>
            <div class="success"><?php echo $text_complete; ?></div>
            <?php }?>
        </div>
    </div>

    <?php echo $footer; ?>