<?php /* $Id: ConsiderSearchModal.tpl 3093 2007-09-24 21:09:45Z brian $ */ ?>
<?php TemplateUtility::printModalHeader(__('Job Orders'), 'js/sorttable.js', __('Add Candidate to This Job Order Pipeline')); ?>

    <?php if (!$this->isFinishedMode): ?>
        <p><?php echo __("Search for a candidate below, and then click on the candidate's first or last name to add the selected candidate to the job order pipeline.");?></p>

        <table class="searchTable">
            <form id="searchByFullNameForm" name="searchByFullNameForm" action="<?php echo(CATSUtility::getIndexName()); ?>?m=joborders&amp;a=considerCandidateSearch" method="post">
                <input type="hidden" name="postback" id="postback" value="postback" />
                <input type="hidden" id="mode_fullname" name="mode" value="searchByFullName" />
                <input type="hidden" id="jobOrderID_fullName" name="jobOrderID" value="<?php echo($this->jobOrderID); ?>" />

                <tr>
                    <td><?php echo __("Search by Full Name");?>:&nbsp;</td>
                    <td><input type="text" class="inputbox" id="wildCardString_fullname" name="wildCardString" />&nbsp;*</td>
                </tr>
                <tr>
                    <td><input type="submit" class="button" id="searchByFullName" name="searchByFullName" value="<?php echo __("Search by Full Name");?>" /></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
            </form>
        </table>
        <br />

        <a href="<?php echo(CATSUtility::getIndexName()); ?>?m=joborders&amp;a=addCandidateModal&amp;jobOrderID=<?php echo($this->jobOrderID); ?>">
            <img src="images/candidate_inline.gif" width="16" height="16" class="absmiddle" alt="add" border="0" />&nbsp;<?php echo __("Add Candidate");?>
        </a>
        <br />

        <?php if (empty($_POST['mode']) || $_POST['mode'] == 'searchByFullName'): ?>
            <script type="text/javascript">
                document.searchByFullNameForm.wildCardString.focus();
            </script>
        <?php else: ?>
            <script type="text/javascript">
                document.searchByKeySkillsForm.wildCardString.focus();
            </script>
        <?php endif; ?>

        <?php if ($this->isResultsMode): ?>
            <br />
            <p class="noteUnsized"><?php echo __("Search Results");?></p>

            <?php if (!empty($this->rs)): ?>
                <table class="sortable" width="100%">
                    <tr>
                        <th align="left" nowrap="nowrap"><?php echo __("First Name");?></th>
                        <th align="left" nowrap="nowrap"><?php echo __("Last Name");?></th>
                        <th align="left" nowrap="nowrap"><?php echo __("Key Skills");?></th>
                        <th align="left"><?php echo __("Created");?></th>
                        <th align="left"><?php echo __("Owner");?></th>
                        <th align="center"><?php echo __("Action");?></th>
                    </tr>

                    <?php foreach ($this->rs as $rowNumber => $data): ?>
                        <tr class="<?php TemplateUtility::printAlternatingRowClass($rowNumber); ?>">
                            <?php if (!$data['inPipeline']): ?>
                                <td valign="top" align="left">
                                    <a href="<?php echo(CATSUtility::getIndexName()); ?>?m=joborders&amp;a=addToPipeline&amp;getback=getback&amp;jobOrderID=<?php echo($this->jobOrderID); ?>&amp;candidateID=<?php $this->_($data['candidateID']); ?>">
                                        <?php $this->_($data['firstName']); ?>
                                    </a>
                                    &nbsp;
                                </td>
                                <td valign="top" align="left">
                                    <a href="<?php echo(CATSUtility::getIndexName()); ?>?m=joborders&amp;a=addToPipeline&amp;getback=getback&amp;jobOrderID=<?php echo($this->jobOrderID); ?>&amp;candidateID=<?php $this->_($data['candidateID']); ?>">
                                        <?php $this->_($data['lastName']); ?>
                                    </a>
                                    &nbsp;
                                </td>
                            <?php else: ?>
                                <td valign="top" align="left"><?php $this->_($data['firstName']); ?>&nbsp;</td>
                                <td valign="top" align="left"><?php $this->_($data['lastName']); ?>&nbsp;</td>
                            <?php endif; ?>
                            <td valign="top" align="left"><?php $this->_($data['keySkills']); ?>&nbsp;</td>
                            <td valign="top" align="left" nowrap="nowrap"><?php $this->_($data['dateCreated']); ?>&nbsp;</td>
                            <td valign="top" align="left" nowrap="nowrap"><?php $this->_($data['ownerAbbrName']); ?>&nbsp;</td>
                            <td align="center" nowrap="nowrap">
                                <a href="#" title="<?php echo __("Show Candidate");?>" onclick="javascript:openCenteredPopup('<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=show&amp;display=popup&amp;candidateID=<?php $this->_($data['candidateID']); ?>', 'viewCandidateDetails', 1000, 675, true); return false;">
                                    <img src="images/new_browser_inline.gif" alt="consider" width="16" height="16" border="0" class="absmiddle" />
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p><?php echo __("No matching entries found.");?></p>
            <?php endif; ?>
        <?php endif; ?>
    <?php else: ?>
        <p><?php echo __("The selected candidate has been successfully added to the pipeline for this job order.");?></p>

        <form method="get" action="<?php echo(CATSUtility::getIndexName()); ?>">
            <input type="button" name="close" value="<?php echo __("Close");?>" onclick="parentGoToURL('<?php echo(CATSUtility::getIndexName()); ?>?m=joborders&amp;a=show&amp;jobOrderID=<?php echo($this->jobOrderID); ?>');" />
        </form>
    <?php endif; ?>
    </body>
</html>

