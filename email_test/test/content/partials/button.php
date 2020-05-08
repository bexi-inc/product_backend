<?php
$text = isset($text) ? $text : null;
$link = isset($link) ? $link : null;
$height = isset($height) ? $height : null;
$width = isset($width) ? $width : null;
$titleAlign = isset($titleAlign) ? $titleAlign : "center";
$backgroundColor = isset($backgroundColor) ? $backgroundColor : null;
$color = isset($color) ? $color : "#fff";
$radius = isset($radius) ? $radius : null;
$fontFamily = isset($fontFamily) ? $fontFamily : "Helvetica, Arial,sans-serif";
$fontSize = isset($fontSize) ? $fontSize : "16px";
$fontWeight = isset($fontWeight) ? $fontWeight : "400";
$border = isset($border) ? $border : "0";
?>
<table
              border="0"
              cellpadding="15"
              cellspacing="0"
              width="100%"
              role="button"
              class="emailButton" 
            >
            <tr>
                <td style="padding: 0">
                  <div>
                    <!--[if mso]>
                      <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="<?= $link ?>" style="height:<?= $height ?>;v-text-anchor:middle;width:<?= $width ?>;" arcsize="<?= $radius ?>; border: <?= $border ?>" strokecolor="<?= $color ?>" fillcolor="<?= $backgroundColor ?>">
                        <w:anchorlock/>
                        <center style="color:<?= $color ?>;font-family:<?= $fontFamily ?>;font-size:<?= $fontSize ?>; font-weight: <?= $fontWeight ?>"><?= $text ?></center>
                      </v:roundrect>
                    <![endif]-->
                    <a class="emailButton" href="<?= $link ?>" style="border: <?= $border ?> ;background-color:<?= $backgroundColor ?>;border-radius:<?= $radius ?>;color:<?= $color ?>;display:inline-block;font-family:<?= $fontFamily ?>;font-size:<?= $fontSize ?>;line-height:<?= $height ?>;text-align:<?= $titleAlign?>;text-decoration:none;font-weight:<?= $fontWeight ?>;padding-right: 50px;padding-left: 50px;-webkit-text-size-adjust:none;mso-hide:all;"><?= $text ?></a>
                  </div>
                </td>
            </tr>
</table>