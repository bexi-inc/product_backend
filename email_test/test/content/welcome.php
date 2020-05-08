<?php
  $title = isset($title) ? $title : "Welcome to Modu Beta";
?>
<!-- CONTENT -->
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
                <td valign="top" class="leftColumnContent">
                  <h1
                    style="
                      color: <?= $titlesColor ?>;
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
                    You're one of the first people to try our new way to make
                    design for marketing faster, smarter, and approachable.<br /><br />
                    We want to know what you like, hate and think we can
                    improve, to make Modu an essential tool for you and your
                    customers.
                  </p>
                </td>
              </tr>
            </table>
            <?php
              snippet("content/partials/space.php");
              snippet("content/partials/button.php", array_merge($buttonConfig, [link => "http://bexi.com", text => "CHECK OUT MODU!"]));
            ?>
            <table
              border="0"
              cellpadding="0"
              cellspacing="0"
              width="100%"
              role="presentation"
            >
              <!-- Text and Link -->
              <tr>
                <td colspan="0" height="26"></td>
              </tr>
              <tr>
                <td valign="top" class="leftColumnContent">
                  <p
                    style="
                      color: #7f8fa4;
                      opacity: 0.9;
                      font-size: 16px;
                      margin: 0;
                      line-height: 24px;
                      font-family: Arial, sans-serif;
                      font-weight: 200;
                    "
                    class="email-text"
                  >
                    If you're having trouble, visit the following URL in your
                    browser:
                  </p>
                  <a
                    href="https://app.getmodu.com"
                    target="_blank"
                    style="
                      color: <?= $linkColor ?>;
                      font-size: 16px;
                      text-decoration: none;
                      line-height: 24px;
                      font-family: Arial, sans-serif;
                      font-weight: 200;
                    "
                    class="email-text"
                    >https://app.getmodu.com</a
                  >
                </td>
              </tr>
            </table>
            <table
              border="0"
              cellpadding="0"
              cellspacing="0"
              width="100%"
              role="presentation"
            >
              <!-- Text and Link -->
              <tr>
                <td colspan="0" height="40"></td>
              </tr>
              <tr>
                <td valign="top" class="leftColumnContent">
                  <p
                    style="
                      color: <?= $titlesColor ?>;
                      font-size: 16px;
                      font-weight: bold;
                      margin: 0;
                      font-family: Arial, sans-serif;
                    "
                    class="email-text"
                  >
                    See you soon!
                  </p>
                  <p
                    style="
                      color: #7f8fa4;
                      opacity: 0.9;
                      font-size: 16px;
                      margin: 0;
                      line-height: 24px;
                      font-family: Arial, sans-serif;
                      font-weight: 200;
                    "
                    class="email-text"
                  >
                    The Modu Team
                  </p>
                  <a
                    href="http://getmodu.com"
                    target="_blank"
                    style="
                      color: <?= $linkColor ?>;
                      font-size: 16px;
                      line-height: 24px;
                      text-decoration: none;
                      font-family: Arial, sans-serif;
                      font-weight: 200;
                    "
                    class="email-text"
                    >getmodu.com</a
                  >
                </td>
              </tr>
            </table>