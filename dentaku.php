<?php
ini_set( 'display_errors', 1 );


$num = isset($_GET['num']) ? $_GET['num'] : null;
$operator = isset($_GET['operator']) ? $_GET['operator'] : '+';
$num1 = isset($_GET['num1']) ? $_GET['num1'] : null;

$num = mb_convert_kana($num, "n", "UTF-8");
$num1 = mb_convert_kana($num1, "n", "UTF-8");

if ( !is_null($num) && !is_null($num1) && preg_match("/^[0-9]+$/", $num) === 1 && preg_match("/^[0-9]+$/", $num1) === 1 ) {
    switch ($operator) {
        case '+':
            $answer = $num + $num1;
            break;
        case '-':
            $answer = $num - $num1;
            break;
        case '*':
            $answer = $num * $num1;
            break;
        case '/':
          if($_GET['num1']==0){
            $answer = '0以外の数字を入れてください';
          } else {
             $answer = $num / $num1 ;
           }
          break;
          default:

        case '+':
            $answer = $num + $num1;
            break;
            }
            $result = "{$answer}";
        } else {
          $result = '計算されていません。';
        }


?>
<!DOCTYPE html>
<html lang = "ja">
  <head>
    <meta charset = "utf-8">
    <title>電卓を作ろう</title>
    <link rel="stylesheet" href="dentaku.css">
  </head>

  <body>

    <form action="" method="get">
      <h1>計算してみよう！</h1>
    <p><input type="number" name="num" style="width:110px;height:15px;" style="ime_mode:disabled" value="<?php echo $num; ?>" placeholder="半角数字のみ" required autofocus></p>

    <select name="operator">
        <option value="+" <?php if ($operator === '+') { echo 'selected'; } ?>>+</option>
        <option value="-" <?php if ($operator === '-') { echo 'selected'; } ?>>-</option>
        <option value="*" <?php if ($operator === '*') { echo 'selected'; } ?>>*</option>
        <option value="/" <?php if ($operator === '/') { echo 'selected'; } ?>>/</option>
    </select>

    <p><input type="number" name="num1" style="width:110px;height:15px" name="num1" style="ime_mode:disabled" value="<?php echo $num1; ?>" placeholder="半角数字のみ" required></p>
    <input type="submit" id="btn" value="計算する">
    <input type="button" id="btn1" onclick="location.href='http://localhost:8888/dentaku/dentaku.php'" value="リセット">

</form>
<p><?php echo $result; ?></p>
  </body>
</html>
