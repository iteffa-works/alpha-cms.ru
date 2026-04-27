<?php 

/*
--------------------------
Класс для работы с файлами
--------------------------
*/
  
class file{
  
  /*
  --------------
  Загрузка файла
  --------------
  */
  
  public static function upload($path) {
    
    //$path - путь обращения к обработчику
    
    ?>
    <form id="upload-container" class="upload-container upload-container-n" method="POST" action="<?=$path?>" enctype="multipart/form-data">
    <input id="file-input" type="file" name="file[]" multiple required>
    <label for="file-input" class="upload-container-button"><?=icons('upload', 17, 'fa-fw')?> <?=lg('Загрузить')?></label><br />
    <span><?=lg('или перетащите сюда файлы')?></span>
    </form>  

    <div id="result"></div>
      
    <script>
    $(document).ready(function(){
      
      var dropZone2 = document.getElementById('upload-container');
      var dropZone = $('#upload-container');
      
      dropZone.on('drag dragstart dragend dragover dragenter dragleave drop', function(){
        
        return false;
      
      });
      
      dropZone.on('dragover dragenter', function() {
        
        dropZone2.setAttribute('class', 'upload-container upload-container-a');
      
      });
      
      dropZone.on('dragleave', function(e) {
        
        dropZone2.setAttribute('class', 'upload-container upload-container-n');
      
      });
      
      dropZone.on('drop', function(e) {
        
        dropZone2.setAttribute('class', 'upload-container upload-container-n');
        var files = e.originalEvent.dataTransfer.files;
        sendFiles(files);
      
      });
      
      $('#file-input').change(function() {
        
        var files = this.files; 
        sendFiles(files);
      
      });
      
      function sendFiles(files) {
        
        var Data = new FormData();
        
        $(files).each(function(index, file) {
          
          Data.append('file[]', file);
        
        });
        
        $.ajax({
          
          xhr: function() {
            
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function(evt) {
              
              if (evt.lengthComputable) {
                
                var percentComplete = evt.loaded / evt.total;
                percentComplete = parseInt(percentComplete * 100);
                $('#result').html("<div class='file-upload'><div class='file-upload-progress'><div class='file-upload-progress-ind' style='width: "+percentComplete+"%;'><div class='file-upload-progress-ind-num'><?=lg('Загрузка')?> "+percentComplete+"%</div></div></div></div>");
                dropZone2.style.display = 'none';
                
                if (percentComplete === 100) {
                  
                  $('#result').html("<div class='file-upload'><div class='file-upload-progress'><div class='file-upload-progress-ind' style='width: 100%; background-color: #53CE9F;'><div class='file-upload-progress-ind-num'><?=lg('Успешно загружено')?> 100%</div></div></div></div>");
                
                }
              
              }
            
            }, false);
            
            return xhr;
          
          },
          url: dropZone.attr('action'),
          type: dropZone.attr('method'),
          data: Data,
          cache: false,
          contentType: false,
          processData: false,
          success: function(data) {
            
            $('#result').html(data);
            dropZone2.style.display = '';
          
          }
        
        });
      
      }
    
    });
    </script>
      
    <?
    
  }
  
  /*
  -------------------------------------------
  Функция отдачи файла на просмотр/скачивание
  -------------------------------------------
  */
  
  public static function download($filename, $name, $mimetype='application/octet-stream'){
    
    if (!file_exists($filename)){ die('Файл не найден'); }
    
    @ob_end_clean();
    
    $from = 0;
    $size = filesize($filename);
    $to = $size;
    
    header('Cache-Control: max-age=2592000, public');
    header('Pragma: public');
    
    if (isset($_SERVER['HTTP_RANGE'])){
      
      if (preg_match('#bytes=-([0-9]*)#i', $_SERVER['HTTP_RANGE'], $range)){
        
        $from = $size-$range[1];
        $to = $size;
      
      }elseif (preg_match('#bytes=([0-9]*)-#i', $_SERVER['HTTP_RANGE'], $range)){
        
        $from = $range[1];
        $to = $size;
      
      }elseif (preg_match('#bytes=([0-9]*)-([0-9]*)#i', $_SERVER['HTTP_RANGE'], $range)){
        
        $from = $range[1];
        $to = $range[2];
      
      }
      
      header('HTTP/1.1 206 Partial Content');
      
      $cr = 'Content-Range: bytes '.$from .'-'.$to.'/'.$size;
    
    }else{
      
      header('HTTP/1.1 200 Ok');
      
      $etag = md5($filename);
      $etag = substr($etag, 0, 8) . '-' . substr($etag, 8, 7) . '-' . substr($etag, 15, 8);
      
      header('ETag: "'.$etag.'"');
      header('Accept-Ranges: bytes');
      header('Content-Length: ' .($to-$from));
      if (isset($cr)){ header($cr); }
      header('Connection: close');
      header('Content-Type: ' . $mimetype);
      header('Last-Modified: ' . gmdate('r', filemtime($filename)));
      header("Last-Modified: ".gmdate("D, d M Y H:i:s", filemtime($filename))." GMT");
      header("Expires: ".gmdate("D, d M Y H:i:s", TM + 3600)." GMT");
      $f = fopen($filename, 'rb');
      
      if (preg_match('#^image/#i', $mimetype)){
        
        header('Content-Disposition: filename="'.$name.'";');
        
      }else{
        
        header('Content-Disposition: attachment; filename="'.$name.'";');
        
      }
      
      fseek($f, $from, SEEK_SET);
      
      $size = $to;
      $downloaded = 0;
      
      while (!feof($f) && !connection_status() && ($downloaded < $size)) {
        
        $block = min(1024 * 8, $size - $downloaded);
        echo fread($f, $block);
        $downloaded += $block;
        flush();
      
      }
      
      fclose($f);
    
    }
    
  }
  
  /*
  ---------------------------------------------------
  Функция задающая соответствие mime типов расширению 
  ---------------------------------------------------
  */
  
  public static function mime($ras = null){
    
    if ($ras == null){
      
      return 'application/octet-stream';
    
    }else{
      
      $htaccess = file(ROOT.'/.htaccess');
      
      for ($i = 0; $i < count($htaccess); $i++){
        
        if (preg_match('#^AddType#i', trim($htaccess[$i]))){
          
          $type = explode(' ', trim($htaccess[$i]));
          $rass = str_replace('.', null, $type[2]);
          $mime[$rass] = $type[1];
        
        }
      
      }
      
      if (isset($mime[$ras])){
        
        return $mime[$ras];
      
      }else{
        
        return 'application/octet-stream';
      
      }
    
    }
    
  }
  
  /*
  ---------------------------
  Стилизация расширения файла
  ---------------------------
  */
  
  public static function ext($ext = null, $data = 'big'){
    
    //$data - версия иконки. big или small
    //$ext - формат файла
    
    if ($ext == 'jpg'){
      
      return file_icons($ext, '#47CE9A', 'camera', $data);
    
    }elseif ($ext == 'jpeg'){
      
      return file_icons($ext, '#47CE9A', 'camera', $data); 
    
    }elseif ($ext == 'png'){
      
      return file_icons($ext, '#35B0EF', 'image', $data);
    
    }elseif ($ext == 'gif'){
      
      return file_icons($ext, '#DC4ADA', 'image', $data);
    
    }elseif ($ext == 'svg'){
      
      return file_icons($ext, '#F5595D', 'image', $data);
    
    }elseif ($ext == 'mp4'){
      
      return file_icons($ext, '#59A8F5', 'film', $data);
    
    }elseif ($ext == 'avi'){
      
      return file_icons($ext, '#A8CD3E', 'play', $data);
    
    }elseif ($ext == '3gp'){
      
      return file_icons($ext, '#E1D35A', 'play', $data);
    
    }elseif ($ext == 'flv'){
      
      return file_icons($ext, '#59CBD2', 'play', $data);
    
    }elseif ($ext == 'mkv'){
      
      return file_icons($ext, '#FB5C51', 'play', $data);
    
    }elseif ($ext == 'mp3'){
      
      return file_icons($ext, '#F74E52', 'music', $data);
    
    }elseif ($ext == 'doc'){
      
      return file_icons($ext, '#6191FF', 'file-text', $data);
    
    }elseif ($ext == 'docx'){
      
      return file_icons($ext, '#6191FF', 'file-text', $data);
    
    }elseif ($ext == 'ppt'){
      
      return file_icons($ext, '#F95353', 'file-powerpoint-o', $data);
    
    }elseif ($ext == 'pdf'){
      
      return file_icons($ext, '#F95353', 'file-powerpoint-o', $data);
    
    }elseif ($ext == 'pptx'){
      
      return file_icons($ext, '#F95353', 'file-powerpoint-o', $data);
    
    }elseif ($ext == 'txt'){
      
      return file_icons($ext, '#5ED565', 'file-text', $data);
    
    }elseif ($ext == 'apk'){
      
      return file_icons($ext, '#48C983', 'android', $data);
    
    }elseif ($ext == 'zip'){
      
      return file_icons($ext, '#FFAD34', 'file-zip-o', $data);
    
    }elseif ($ext == 'rar'){
      
      return file_icons($ext, '#FFAD34', 'file-zip-o', $data);
    
    }elseif ($ext == 'acms'){
      
      return file_icons($ext, '#349AFF', 'gear', $data);
    
    }elseif ($ext == 'themes'){
      
      return file_icons($ext, '#58B5FF', 'paint-brush', $data);
    
    }else{
      
      return file_icons($ext, '#E4E033', $data);
    
    }
    
  }
  
  /*
  ---------------------------------
  Оповещения об ошибке при выгрузке 
  файла
  ---------------------------------
  */
  
  public static function error($text = 'Не удалось загрузить один или несколько файлов.<br /><br /><b>Возможные причины:</b><br /><br />- превышены лимиты сервера на размер выгружаемых файлов;<br /> - закончилось дисковое пространство на сервере;<br />- неверная директория для сохранения файла;<br />- недостаточно прав у директории для сохранения файла.'){
    
    ?>
    <div class='file-info'><?=icons('exclamation-triangle', 16)?> <?=lg($text)?></div>
    <?
      
    exit;
    
  }
  
  /*
  ------------------------------------
  Динамическое обновление списка после
  загрузки файлов
  ------------------------------------
  */
  
  public static function update($url, $element){
    
    ?> 
    <script>
    var data = "<?=$url?>";
    var toLoad = data+' <?=$element?>';
    $("<?=$element?>").load(toLoad);        
    modal_bottom_close();
    </script>
    <?
    
  }

}