<?php

/*
----------------------------------------
Kласс для работы с HTML структурой сайта
----------------------------------------
*/
  
class html{
  
  /*
  -----
  Капча
  -----
  */
  
  public static function captcha($text){
    
    global $captha_length, $captcha_random_seed, $captcha_key;
    
    //$text - описание
    
    ?>      
    <input autocomplete="off" placeholder="<?=lg($text)?>" name="captcha" class="captcha1" type="text" maxlength="<?=$captha_length?>" size="<?=$captha_length?>">
    <div class='captcha2'>
    <?php
    
    for ($i = 0; $i < $captha_length; $i++) {
      
      $snum = mt_rand(0,9); 
      $psnum = md5($snum + $captcha_random_seed);
      
      ?><img src="<?=PHP_SELF?>?image=<?=$psnum?>"><?
      
      $captcha_key .= $snum;
    
    }
    
    $captcha_key = md5($captcha_key + $captcha_random_seed);
    
    ?>
    </div>
    <input name="captcha_key" type="hidden" value="<?=$captcha_key?>"><br />
    <?
    
  }
  
  /*
  ---------------
  Выбор вариантов
  ---------------
  */
  
  public static function select($name, $param, $text = null, $class = 'form-control-select', $icons = 'user'){
    
    //$name - имя для POST передачи
    //$text - описание
    //$class - стиль
    //$param - параметры массива
    //$icons - иконка
    
    ?>
    <div class='<?=$class?>-optimize'>  
    <span id='form-control-info'><?=icons($icons, 16)?></span>
    <span class='form-control-modify-select-appearance'><?=icons('angle-down', 20)?></span>
    <span class="form-control-title"><?=lg($text)?></span>
    <select name="<?=$name?>" class="<?=$class?>">
      
    <?php
    foreach ($param as $position => $name) { 
      
      if ($name[1] == 'selected'){
        
        $selected = " selected='selected'";
        
      }else{
        
        $selected = null;

      }
      
      ?><option value="<?=$position?>"<?=$selected?>><?=lg($name[0])?></option><?
      
    }
    ?>
    
    </select>
    </div>
    <?
    
  }
  
  /*
  -------
  Чекбокс
  -------
  */
  
  static function checkbox($name, $text = null, $value = null, $checked = null){
    
    //$name - имя чекбокса для POST передачи
    //$text - описание чекбокса
    //$value - параметр передачи в POST
    //$checked - предопределенный параметр
    
    ?>
    <label class="custom-checkbox"><input type="checkbox" name="<?=$name?>" value="<?=$value?>" <?=($value == $checked ? " checked='checked'" : null)?> ><span><?=lg($text)?></span></label>
    <?
    
  }
  
  /*
  -----
  Радио
  -----
  */
  
  static function radio($name, $text = null, $value = null, $checked = null){
    
    //$name - имя для POST передачи
    //$text - описание
    //$value - параметр передачи в POST
    //$checked - преопределенный параметр
    
    ?>
    <label class="custom-radio"><input type="radio" name="<?=$name?>" value="<?=$value?>" <?=($value == $checked ? "checked" : null)?> ><span><?=lg($text)?></span></label>
    <?
    
  }
  
  /*
  -----------------------------
  Форма добавления комментариев
  -----------------------------
  */
  
  public static function comment($name, $action, $text = null, $type = null, $id = 'count_char', $o_id = 0, $mp = 0){
    
    //$name - имя POST параметра
    //$action - Ссылка обработки запроса
    //$text - текст внутри окна
    //$type - тип
    //$id - id элемента
    //$mp - индикатор печатания сообщения (для почты)
    //$o_id - id объекта
    
    require (ROOT.'/system/PHP-classes/comment/form.php');
  
  }

  /*
  ------------------
  Заголовок страницы
  ------------------
  */

  static function title($title){
    
    config('TITLE', lg($title));
    
  }
  
  /*
  -----------------
  Описание страницы
  -----------------
  */

  static function description($description){
    
    config('DESCRIPTION', lg($description));
    
  }
  
  /*
  --------------------------
  Ключевые слова на странице
  --------------------------
  */

  static function keywords($keywords){
    
    config('KEYWORDS', lg($keywords));
    
  }
  
  /*
  ---------------
  Пустое значение
  ---------------
  */
  
  public static function empty($title = 'Пока пусто', $icons = 'sticky-note-o') {
    
    ?>
    <div class='list2'> 
    <span><?=icons($icons, 80, 'fa-fw')?></span>
    <div><?=lg($title)?></div>
    </div>
    <?
      
  }
  
  /*
  -----------------
  Окно ввода текста 
  -----------------
  */
  
  static function textarea($text = null, $name = null, $placeholder = null, $title = null, $class = 'form-control-textarea', $rows = 5, $bb = 1) {
    
    //$placeholder - описание внутри поля
    //$name - имя для передачи в POST
    //$title - описание поля
    //$text - текст поля
    //$class - стиль поля
    //$rows - высота поля
    //$bb - показ панели добавления bb кодов. Если 1 - общий показ, если 0 - скрытие, если 2 - показ панели bb-кодов и смайлов без файлов
    
    if (str($title) > 0){
      
      echo lg($title)."<br />";
      
    }
    
    ?>
    <span style='position: relative'>
    <textarea id='count_char' onkeyup="countLetters()" name='<?=$name?>' rows='<?=$rows?>' placeholder='<?=lg($placeholder)?>' class='<?=$class?>'><?=$text?></textarea>
    <small style='position: absolute; bottom: 10px; right: 15px; color: #566B75'><span id='countLetters'><?=mb_strlen(str_replace("\r", "", $text), 'UTF-8')?></span></small>
    </span>
    <?
      
    require (ROOT.'/system/connections/bb_textarea.php');
    
  }
  
  /*
  -----------------
  Окно ввода данных 
  -----------------
  */

  static function input($name, $placeholder = null, $title = null, $length = null, $value = null, $class = 'form-control-100', $type = 'text', $data = null, $icons = 'user', $comment = null) {
    
    //$placeholder - описание внутри поля
    //$name - имя для передачи в POST
    //$title - описание поля
    //$length - максимальное количество вводимых в поле символов
    //$value - введенный в поле текст по умолчанию
    //$class - стиль поля
    //$type - тип поля
    //$data - дополнительные атрибуты
    //$icons - иконки
    //$comment - комментарий со вспывающим окном
    
    if (str($title) > 0){
      
      ?>
      <?=lg($title)?>
      <?
      
    }
    
    if ($type == 'password'){

      $pass = "id='".$name."'";
      
    }else{

      $pass = null;
      
    }
    
    if (str($comment) > 0){
      
      $tr = "tr";
      $tr_class = "<div class='input-info'>".lg($comment)."</div>";
      
    }else{
      
      $tr = null;
      $tr_class = null;
      
    }
      
    ?>
    <div class='<?=$class?>-optimize <?=$tr?>'>
    <?=$tr_class?> 
    <span id='form-control-info'><?=icons($icons, 17)?></span>
    <input name="<?=$name?>" maxlength="<?=$length?>" length="<?=$length?>" type="<?=$type?>" value="<?=$value?>" placeholder="<?=lg($placeholder)?>" class="<?=$class?>" <?=$pass?> <?=$data?> >      
    <?php if ($type == 'password'){ ?>       
    <span id="password-checkbox" onclick="pass_eye('<?=$name?>')" class="<?=$name?>"><?=icons('eye-slash', 17)?></span>
    <?php } ?>
    </div>
    <?
    
  }
  
  /*
  ----------------------
  Кнопка отправки данных 
  ----------------------
  */
  
  static function button($class = null, $name = null, $icon = null, $title = null, $o = null, $icon_size = 15) {
    
    if ($icon == null) {
      
      $i = null;
    
    }else{
      
      $i = "<i class='fa fa-".$icon." fa-fw' style='font-size: ".$icon_size."px'></i>";
    
    }
    
    ?>
    <button o="<?=$o?>" onclick="load_p('<?=lg('Ждите')?>', '#<?=$name?>')" type="submit" class="<?=$class?>" name="<?=$name?>" id="<?=$name?>" value="go"><?=$i?> <?=lg($title)?></button>
    <input type="hidden" value="go" name="<?=$name?>">
    <?      
      
    if (config('CSRF') == 1){
      
      ?><input type="hidden" name="<?=csrf::token_id()?>" value="<?=csrf::token(csrf::token_id())?>"><?
      
    }  

  
  }

}