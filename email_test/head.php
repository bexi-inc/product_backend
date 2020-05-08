<?php
$title = isset($title) ? $title : "Test";
$backgroundColor = isset($backgroundColor) ? $backgroundColor : "#fff";
?>
<html lang="en">
  <head>
    <title><?= $title ?></title>
    <meta http-equiv="Content-Type" content="text/html charset=UTF-8" />
    <!--[if (mso)|(mso 16)|(mso 7)|(mso 10)|(mso 13)]>
      <style type="text/css">
        a {
          text-decoration: none;
          color: #fff;
        }
        body,
        table,
        td,
        tr,
        h1,
        p,
        a {
          font-family: Arial, Helvetica, sans-serif !important;
        }
      </style>
    <![endif]-->
    <style type="text/css">
      @media only screen and (max-width: 480px) {
        #templateColumns {
          width: 100% !important;
        }

        .templateColumnContainer {
          display: block !important;
          width: 100% !important;
        }

        .leftColumnContent {
          font-size: 16px !important;
          line-height: 125% !important;
        }

        .rightColumnContent {
          font-size: 16px !important;
        }
        .emailImage {
          height: auto !important;
          max-width: 464px !important;
          width: 100% !important;
        }
        .emailButton {
          max-width: 464px !important;
          width: 100% !important;
          padding: 0 !important;
        }
        .emailButton a {
          display: block !important;
          font-size: 16px !important;
        }
      }
      .email-title {
        font-family: Arial, sans-serif;
        font-weight: 500;
      }
      .email-text,
      .email-subTitle {
        font-family: Arial, sans-serif;
      }
      .email-text {
        font-weight: 200;
      }
      .email-subTitle {
        font-weight: 700;
      }
    </style>
  </head>
  <body style="background-color: #fff; margin: 0;">
    <center>
      <table
        border="0"
        cellpadding="0"
        cellspacing="10"
        width="464"
        id="templateColumns"
        role="presentation"
        style="background-color: <?= $backgroundColor ?>"
      >
        <tr>
          <td
            align="left"
            valign="top"
            width="100%"
            class="templateColumnContainer"
          >