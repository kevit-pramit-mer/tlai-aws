<?php

use yii\db\Expression;

$expression = new Expression( 'YEAR(NOW())' );
$year       = ( new \yii\db\Query )->select( $expression )->scalar();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>Set up a new password for Giptech</title>
    <style type="text/css">


        body {
            width: 100% !important;
            height: 100%;
            margin: 0;
            line-height: 1.4;
            background-color: #F2F4F6;
            color: #74787E;
            -webkit-text-size-adjust: none;
        }

        @media only screen and (max-width: 600px) {
            .email-body_inner {
                width: 100% !important;
            }

            .email-footer {
                width: 100% !important;
            }
        }

        @media only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }
    </style>

</head>

<body style="-webkit-text-size-adjust: none; box-sizing: border-box; color: #74787E; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; height: 100%; line-height: 1.4; margin: 0; width: 100% !important;"
      bgcolor="#F2F4F6">

<table class="email-wrapper" width="100%" cellpadding="0" cellspacing="0"
       style="box-sizing: border-box; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; margin: 0; padding: 0; width: 100%;"
       bgcolor="#F2F4F6">
    <tr>
        <td align="center"
            style="box-sizing: border-box; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; word-wrap: break-word;">
            <table class="email-content" width="100%" cellpadding="0" cellspacing="0"
                   style="box-sizing: border-box; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; margin: 0; padding: 0; width: 100%;">
                <tr>
                    <td class="email-masthead"
                        style="box-sizing: border-box; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; padding: 25px 0; word-wrap: break-word;"
                        align="center">
                            <name>ECOUC</name>
<!--                            <img src="http://www.Giptech.com/wp-content/uploads/2016/04/Giptech-logo-trans-sm.png" alt="Calltech" height="40%"/>-->
                    </td>
                </tr>

                <tr>
                    <td class="email-body" width="100%"
                        style="border-bottom-color: #EDEFF2; border-bottom-style: solid; border-bottom-width: 1px; border-top-color: #EDEFF2; border-top-style: solid; border-top-width: 1px; box-sizing: border-box; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; margin: 0; padding: 0; width: 100%; word-wrap: break-word;"
                        bgcolor="#FFFFFF">
                        <table class="email-body_inner" align="center" width="570"
                               style="box-sizing: border-box; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; margin: 0 auto; padding: 0; width: 570px;"
                               bgcolor="#FFFFFF">

                            <tr>
                                <td class="content-cell"
                                    style="box-sizing: border-box; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; padding: 35px; word-wrap: break-word;">
                                    <h1 style="box-sizing: border-box; color: #2F3133; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; font-size: 19px; font-weight: bold; margin-top: 0;"
                                        align="left">Hi, <?= $this->params['name'] ?></h1>
                                    <p style="box-sizing: border-box; color: #74787E; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; font-size: 16px; line-height: 1.5em; margin-top: 0;"
                                       align="left">The password for your ECOUC Account was recently changed.

                                        No action is necessary; this is just a notification regarding your account’s
                                        safety.

                                        Enjoy your day! </p>
                                    <br/>The ECOUC Team
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="box-sizing: border-box; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; word-wrap: break-word;">
                        <table class="email-footer" align="center" width="570" cellpadding="0" cellspacing="0"
                               style="box-sizing: border-box; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; margin: 0 auto; padding: 0; text-align: center; width: 570px;">
                            <tr>
                                <td class="content-cell" align="center"
                                    style="box-sizing: border-box; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; padding: 35px; word-wrap: break-word;">
                                    <p class="sub align-center"
                                       style="box-sizing: border-box; color: #AEAEAE; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; font-size: 12px; line-height: 1.5em; margin-top: 0;"
                                       align="center">© <?= $year; ?> ECOUC. All rights reserved.</p>
                                    <p class="sub align-center"
                                       style="box-sizing: border-box; color: #AEAEAE; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; font-size: 12px; line-height: 1.5em; margin-top: 0;"
                                       align="center">
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
