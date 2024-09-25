<!DOCTYPE html>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?php echo(HTML_ENCODING); ?>">
        <title><?php $this->_($this->siteName); ?> - Careers</title>
        <?php global $careerPage; if (isset($careerPage) && $careerPage == true): ?>
            <script type="text/javascript" src="../js/lib.js"></script>
            <script type="text/javascript" src="../js/sorttable.js"></script>
        <?php else: ?>
            <script type="text/javascript" src="js/lib.js"></script>
            <script type="text/javascript" src="js/sorttable.js"></script>
        <?php endif; ?>
        <style type="text/css" media="all">
            <?php echo($this->template['CSS']); ?>
        </style>
    </head>
    <body>
    <!-- TOP -->
    <?php echo($this->template['Header']); ?>

    <!-- CONTENT -->
    <?php echo($this->template['Content']); ?>

    <!-- FOOTER -->
    <?php echo($this->template['Footer']); ?>
    <div style="font-size:9px;">
        <br />
    </div>
    <div style="text-align:center;">

        <?php /* WARNING: It is against the terms of the CPL to remove or alter the following line.  The 'Powered by OpenCATS' line must stay visible on every page. */ ?>
        <span style="font-size: 9px;">Powered by</span> <a style="color: #888; position: relative; font-size: 9px; font-weight: normal; text-align: center; left: 0px; top: 0px;" href="http://www.opencats.com" target="_blank">OpenCATS</a>.

    </div>
    <script type="text/javascript">st_init();</script>
    </body>
</html>
