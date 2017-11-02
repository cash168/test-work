<?php
/* Smarty version 3.1.30, created on 2017-11-02 13:51:27
  from "/var/www/test/core/templates/index.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_59faf8af74ad26_69764022',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b86d9dfd8ef0fc1424b698c2dd7e2f290d0a0bfb' => 
    array (
      0 => '/var/www/test/core/templates/index.tpl',
      1 => 1509619884,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_59faf8af74ad26_69764022 (Smarty_Internal_Template $_smarty_tpl) {
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="Pragma" content="no-cache"
  </head>
  <body>
<?php if ($_smarty_tpl->tpl_vars['usertype']->value == "guest") {?>
  <?php if ($_smarty_tpl->tpl_vars['error']->value != '') {?>
    Ошибка: <?php echo $_smarty_tpl->tpl_vars['error']->value;?>
 <br>
  <?php }?>
<form method="POST" action="<?php echo $_smarty_tpl->tpl_vars['baseDir']->value;?>
/login">
Логин <input name="login" type="text"><br>
Пароль <input name="password" type="password"><br>
<input name="submit" type="submit" value="Войти">
</form>

<?php } else { ?>

Баланс = <?php echo $_smarty_tpl->tpl_vars['balance']->value;?>
 руб.<br>
<form method="POST" action="<?php echo $_smarty_tpl->tpl_vars['baseDir']->value;?>
/pay">
  <?php if ($_smarty_tpl->tpl_vars['error']->value != '') {?>
    Ошибка: <?php echo $_smarty_tpl->tpl_vars['error']->value;?>
 <br>
  <?php }?>
Списать со счета <input name="pay" type="text"> руб.<br>
<input name="submit" type="submit" value="Списать">
</form>
<br>
<form method="POST" action="<?php echo $_smarty_tpl->tpl_vars['baseDir']->value;?>
/logout">
<input name="submit" type="submit" value="Выход">
</form>

<?php }?>
  </body>
</html><?php }
}
