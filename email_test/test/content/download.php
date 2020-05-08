<?php
  $title = isset($title) ? $title : "Your download is ready!";
  $projectName = isset($projectName) ? $projectName : "{project name}";
  $subdomain = isset($subdomain) ? $subdomain : "subdomain";
  $projectUrl = "http://$subdomain.getmodu.com";
?>
<table
              border="0"
              cellpadding="0"
              cellspacing="0"
              width="100%"
              role="presentation"
            >
              <!-- Title and Text-->
              <tr>
                <td colspan="0" height="43"></td>
              </tr>
              <tr>
                <td valign="top" class="leftColumnContent" align="<?= $titleAlign ?>">
                  <h1
                    style="
                      color: #000000;
                      font-size: 36px;
                      line-height: 48px;
                      font-family: Arial, sans-serif;
                      font-weight: 500;
                    "
                    class="email-title"
                  >
                    <?= $title ?>
                  </h1>
                  <p
                    style="
                      color: #7f8fa4;
                      opacity: 0.9;
                      font-size: 16px;
                      line-height: 24px;
                      font-family: Arial, sans-serif;
                      font-weight: 200;
                    "
                    class="email-text"
                  >
                    Your project <?= $projectName ?> has been compressed and it's
                    ready to be downloaded!
                  </p>
                </td>
              </tr>
              <tr>
                <td colspan="0" height="28"></td>
              </tr>
            </table>
            <?php snippet("content/partials/button.php", array_merge($buttonConfig, [link => "http://bexi.com", text => "DOWNLOAD PROJECT"])); ?>
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
              <!-- Text and Link -->
              <tr>
                <td colspan="0" height="35"></td>
              </tr>
              <tr>
                <td valign="top" class="leftColumnContent">
                  <p
                    style="
                      color: #7f8fa4;
                      opacity: 0.9;
                      font-size: 16px;
                      margin: 0;
                      display: inline;
                      line-height: 24px;
                      font-family: Arial, sans-serif;
                      font-weight: 200;
                    "
                    class="email-text"
                  >
                    A permanent URL has been created for this project, and you
                    can always use it to download your project:
                  </p>
                  <a
                    href="<?= $projectUrl ?>"
                    target="_blank"
                    style="
                      color: <?= $linkColor ?>;
                      font-size: 16px;
                      text-decoration: none;
                      font-weight: bold;
                      line-height: 24px;
                      font-family: Arial, sans-serif;
                    "
                    class="email-text"
                    ><?= $projectUrl ?>
                  </a>
                </td>
              </tr>
            </table>