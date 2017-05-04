<?php /* $Id: Error.tpl 1930 2007-02-22 08:39:53Z will $ */ ?>
<?php TemplateUtility::printHeader(__('Calendar')); ?>
<?php TemplateUtility::printHeaderBlock(); ?>
<?php TemplateUtility::printTabs($this->active); ?>
    <div id="main">
        <?php TemplateUtility::printQuickSearch(); ?>

        <div id="contents">
            <table>
                <tr>
                    <td width="3%">
                        <img src="images/calendar.gif" width="24" height="24" alt="<?php echo __("Calendar");?>" style="border: none; margin-top: 3px;" />&nbsp;
                    </td>
                    <td><h2><?php echo __("Calendar");?></h2></td>
                </tr>
            </table>

            <p class="fatalError">
                <?php echo __("A fatal error has occurred.");?><br />
                <br />
                <?php echo($this->errorMessage); ?>
            </p>
        </div>
    </div>
<?php TemplateUtility::printFooter(); ?>
