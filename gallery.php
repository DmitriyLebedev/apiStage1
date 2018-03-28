<?php
/*
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
*/
$app = require_once __DIR__.'/gallery_lib/app.php';

$app->setImagesUrl('http://localhost/galleria_images/');
$app->setImagesDir('galleria_images/');
$app->setTargetDir("Prochee/");
$app->setBaseDir('/home/dm/Pictures');
//------------------------------
$app->readDirectory();

?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script type="text/javascript">
var urls = {};


<?php	$i = 0;	
if ( !$app->getDirectoris() ) : 
		foreach ( $app->getImages(2) as $entry) : ?>
urls[<?= $i ?>] = { type: "<?= $entry['type'] ?>", vertical: "<?= $entry['vertical'] ?>", url: "<?= $entry['url'] ?>"} 

<?php $i++;
		endforeach; ?>

$(document).ready( function(){
	window.scrollTo(0,0);
	$(window).scroll (function(e){ inventoryScrollLoad($(window).scrollTop());});
});

var index = 0;
var loading = false;
function inventoryScrollLoad(ws){

 	if( !loading && document.body.scrollHeight - $(window).height() - ws < 320){
 		loading = true;
 		var entry = urls[ index ];
 		var elem = '';

 		if( entry.type == 'video') {
   	    	elem = '<video width="100%" controls="controls"><br /><hr /><br /><source src="'+ entry.url +'" type="video/mp4" /></video>';

   	    } else if(entry.type == 'image') {
 			elem = '<img style="width: '+ ( entry.vertical == 1 ? '50' : '100' ) +'%; image-orientation: from-image;" src="'+ entry.url +'" /><br /><hr /><br />';

 		}
 		
 		$('#container').append( elem );
 		
		loading = false;
		index ++;
 	}
}
<?php 	endif; ?>
</script>

<body>
<div style="text-align: center">
<?php	if ( $app->getDirectoris() ) : ?>
   
   <div style="margin-top: 10%">
   
<?php 		foreach ( $app->getDirectoris() as $entry) : ?>
           
        <a style="font-size: 2em;" href="?dir=<?= $entry['url'] ?>"><?= $entry['name'] ?></a><br /><br />
            
<?php   	endforeach; ?>
     
     </div>
        
<?php 	endif; ?>


<?php	if($app->getImages()) : ?>

		<div id="container">

<?php		foreach ( $app->getImages(0, 2) as $entry) : 
				if( $entry['type'] == 'image') : ?>
            
        	<img style="width:<?= $entry['vertical'] ? '50' : '100' ?>%; image-orientation: from-image;" src="<?= $entry['url'] ?>" /><br /><hr /><br />
        	
 <?php			elseif( $entry['type'] == 'video') : ?>
 
    	    <video width="100%" controls="controls"><br /><hr /><br />
           		<source src="<?= $entry['url'] ?>" type="video/mp4" />
	        </video>

        
<?php 			endif;
			endforeach; ?>

		</div>
		
<?php	endif; ?>


</div>
<div style="width: 100%; text-align: center; margin-top:100px;">
    <form action="" method="post" enctype="multipart/form-data">
        Select image to upload:
        <input type="file" name="fileToUpload" id="fileToUpload">
        <br /><br />
        <input type="submit" value="Upload Image" name="submit">
    </form>
</div>

</body>
</html> 
