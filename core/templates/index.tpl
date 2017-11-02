<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="Pragma" content="no-cache"
  </head>
  <body>
{if $usertype=="guest"}
  {if $error!=""}
    Ошибка: {$error} <br>
  {/if}
<form method="POST" action="{$baseDir}/login">
Логин <input name="login" type="text"><br>
Пароль <input name="password" type="password"><br>
<input name="submit" type="submit" value="Войти">
</form>

{else}

Баланс = {$balance} руб.<br>
<form method="POST" action="{$baseDir}/pay">
  {if $error!=""}
    Ошибка: {$error} <br>
  {/if}
Списать со счета <input name="pay" type="text"> руб.<br>
<input name="submit" type="submit" value="Списать">
</form>
<br>
<form method="POST" action="{$baseDir}/logout">
<input name="submit" type="submit" value="Выход">
</form>

{/if}
  </body>
</html>