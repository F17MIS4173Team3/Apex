<?php
header("Content-type: image/png");
$img = imagecreatetruecolor(500,500);

for($i=0;$i<500;$i++) {
  for($j=0;$j<500;$j++) {
  $ink = imagecolorallocate($img,rand(1,255),rand(1,255),rand(1,255));
  imagesetpixel($img, $i, $j, $ink);
  }
}

imagepng($img);
imagedestroy($img);

?>