<?php
  $align = isset($align) ? $align : "left";
  $width = isset($width) ? $width : "143px";
  $height = isset($height) ? $height : "25px";
  $logoSrc = isset($logoSrc) ? $logoSrc : "";
  $logoAlt = isset($logoAlt) ? $logoAlt : ""
?>
<table
              border="0"
              cellpadding="0"
              cellspacing="0"
              width="100%"
              role="presentation"
            >
              <tr>
                <td class="leftColumnContent" align="<?= $align ?>">
                  <img
                    src="<?= $logoSrc ?>"
                    alt="<?= $logoAlt ?>"
                    width="<?= $width ?>"
                    height="<?= $height ?>"
                    style="margin-top: 20px;"
                    class="columnImage"
                  />
                </td>
              </tr>
            </table>