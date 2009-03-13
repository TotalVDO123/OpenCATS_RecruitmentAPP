<?php
/*
   * OSATS
   *
   *
   *
*/

include_once('./lib/ActivityEntries.php');
include_once('./lib/StringUtility.php');
include_once('./lib/DateUtility.php');
include_once('./lib/JobOrders.php');
include_once('./lib/Site.php');
include_once('./lib/CareerPortal.php');
include_once('./lib/i18n.php');

class WizardUI extends UserInterface
{
    public function __construct()
    {
        parent::__construct();

        $this->_authenticationRequired = false;
        $this->_moduleDirectory = 'wizard';
        $this->_moduleName = 'wizard';
        $this->_moduleTabText = '';
        $this->_subTabs = array();

        /*
        $this->addPage('Welcome!', './modules/wizard/WizardIntroIntro.tpl', '', false, true);
        $this->addPage('License', './modules/wizard/WizardIntroLicense.tpl', '', true, true);
        $this->addPage('Register', './modules/wizard/WizardIntroProf.tpl', '', false, true);
        $this->addPage('Setup Users', './modules/wizard/WizardIntroUsers.tpl', '
            $users = new Users($siteID);
            $mp = $users->getAll();
            $data = $users->getLicenseData();

            $this->_template->assign(\'users\', $mp);
            $this->_template->assign(\'totalUsers\', $data[\'totalUsers\']);
            $this->_template->assign(\'userLicenses\', $data[\'userLicenses\']);
            $this->_template->assign(\'accessLevels\', $users->getAccessLevels());
        ');
        $this->addPage('Localization', './modules/wizard/WizardIntroLocalization.tpl', '
            $this->_template->assign(\'timeZone\', $_SESSION[\'OSATS\']->getTimeZone());
            $this->_template->assign(\'isDateDMY\', $_SESSION[\'OSATS\']->isDateDMY());
        ');

        $this->addJsInclude('./js/wizardIntro.js');
        $this->setFinishURL('?m=home');
        */
    }


    public function handleRequest()
    {
        $action = $this->getAction();
        switch ($action)
        {
            case 'ajax_getPage':
                $this->ajax_getPage();
                break;

            default:
                $this->show();
                break;
        }
    }

    public function show()
    {
        if (!isset($_SESSION['OSATS_WIZARD']) || empty($_SESSION['OSATS_WIZARD']) ||
            !is_array($_SESSION['OSATS_WIZARD']))
        {
            // The user has removed or the session for the wizard has been lost,
            // redirect to rebuild it
            osatutil::transferRelativeURI(osatutil::getIndexName() . 'm=home');
            return;
        }

        // Build the javascript for navigation
        $js = '';
        for ($i=0; $i<count($_SESSION['OSATS_WIZARD']['pages']); $i++)
        {
            $js .= sprintf('addWizardPage("%s", %s, %s);%s',
                addslashes($_SESSION['OSATS_WIZARD']['pages'][$i]['title']),
                $_SESSION['OSATS_WIZARD']['pages'][$i]['disableNext'] ? 'true' : 'false',
                $_SESSION['OSATS_WIZARD']['pages'][$i]['disableSkip'] ? 'true' : 'false',
                "\n"
            );
        }
        $js .= sprintf('var finishURL = \'%s\';', $_SESSION['OSATS_WIZARD']['finishURL'], "\n");
        $js .= sprintf('var currentPage = %d;%s', $_SESSION['OSATS_WIZARD']['curPage'], "\n");
        $this->_template->assign('js', $js);

        if (isset($_SESSION['OSATS_WIZARD']['js'])) $jsInclude = $_SESSION['OSATS_WIZARD']['js'];
        else $jsInclude = '';

        $this->_template->assign('jsInclude', $jsInclude);
        $this->_template->assign('pages', $_SESSION['OSATS_WIZARD']['pages']);
        $this->_template->assign('currentPage', $_SESSION['OSATS_WIZARD']['pages'][$_SESSION['OSATS_WIZARD']['curPage']-1]);
        $this->_template->assign('currentPageIndex', $_SESSION['OSATS_WIZARD']['curPage']-1);
        $this->_template->assign('active', $this);
        $this->_template->assign('enableSkip', true);
        $this->_template->assign('enablePrevious', $_SESSION['OSATS_WIZARD']['curPage']==1 ? false : true);
        $this->_template->assign('enableNext', true);

        $this->_template->display('./modules/wizard/Show.tpl');
    }

    public function ajax_getPage()
    {
        if (!isset($_SESSION['OSATS_WIZARD']) || !is_array($_SESSION['OSATS_WIZARD']) ||
            !count($_SESSION['OSATS_WIZARD']))
        {
            echo __('This wizard has no pages.');
            return;
        }

        // Get the current page of the wizard
        if (isset($_GET['currentPage'])) $currentPage = intval($_GET['currentPage']); else $currentPage = 1;
        if ($currentPage < 1 || $currentPage > count($_SESSION['OSATS_WIZARD']['pages'])) $currentPage = 1;

        if (isset($_GET['requestAction'])) $requestAction = $_GET['requestAction']; else $requestAction = '';
        switch ($requestAction)
        {
            case 'next':
                $requestPage = $currentPage + 1;
                break;
            case 'previous':
                $requestPage = $currentPage - 1;
                break;
            case 'skip':
                $requestPage = count($_SESSION['OSATS_WIZARD']);
                break;
            case 'current':
            default:
                $requestPage = $currentPage;
                break;
        }

        // Set session variables (if they exist)
        if (isset($_SESSION['OSATS']) && !empty($_SESSION['OSATS']))
        {
            $session = $_SESSION['OSATS'];
            $this->_template->assign('userID', $userID = $session->getUserID());
            $this->_template->assign('userName', $userName = $session->getUserName());
            $this->_template->assign('siteID', $siteID = $session->getSiteID());
            $this->_template->assign('siteName', $siteName = $session->getSiteName());
        }

        // Figure out which template to display
        if (!isset($_SESSION['OSATS_WIZARD']['pages'][$requestPage -= 1])) $requestPage = 0;
        $template = $_SESSION['OSATS_WIZARD']['pages'][$requestPage]['template'];
        $_SESSION['OSATS_WIZARD']['curPage'] = $requestPage + 1;

        if (($php = $_SESSION['OSATS_WIZARD']['pages'][$requestPage]['php']) != '')
        {
            eval($php);
        }

        $this->_template->display($template);
    }
}