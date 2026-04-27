<link rel="shortcut icon" href="/style/version/<?=version('DIR')?>/favicon/<?=version('FAVICON')?>?v=<?=front_hash()?>" />
<link rel="stylesheet" href="/style/version/<?=version('DIR')?>/styles.css?v=<?=front_hash()?>" type="text/css" />
<link rel="stylesheet" href="/style/font-awesome/font-awesome.css?v=<?=front_hash()?>">
  
<?php
  
//Подгрузка CSS компонентов из папки /style/css/ 
$result = scandir(ROOT.'/style/css/', SCANDIR_SORT_ASCENDING);
for ($i = 0; $i < count($result); $i++){
  
  if (preg_match('#\.css$#i',$result[$i])){
    
    ?><link rel="stylesheet" href="/style/css/<?=$result[$i]?>?v=<?=front_hash()?>"><?
  
  }

}

direct::components(ROOT.'/system/connections/cheader/data/', 0);

?>
  
<meta property="og:title" content="<?=config('TITLE')?>" />
<meta property="og:description" content="<?=config('DESCRIPTION')?>" />
<meta property="og:type" content="article" />
<meta property="og:image" content="<?=SCHEME?><?=HTTP_HOST?><?=(str($logo) > 0 ? $logo : '/style/version/'.version('DIR').'/logo/'.version('LOGO'))?>" />
<meta property="og:url" content="<?=SCHEME?><?=HTTP_HOST?><?=REQUEST_URI?>" />