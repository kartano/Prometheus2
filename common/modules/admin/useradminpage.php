<?php
/**
 * User administration page.
 *
 * @author          Simon Mitchell <kartano@gmail.com>
 *
 * @namespace       Prometheus2\common\modules\admin
 *
 * @version         1.0.0           2017-09-08 2017-09-08 Prototype
 * @version         1.0.1           2017-09-18 SM:  Added use of the language utility.
 */


namespace Prometheus2\common\modules\admin;
use Prometheus2\common\pagerendering as Page;
use Prometheus2\common\database as DB;
use Prometheus2\common\widgets AS Widgets;
use Prometheus2\common\settings AS Settings;

/**
 * Class UserAdminPage
 * @package Prometheus2\common\modules\admin
 */
class UserAdminPage extends Page\PageRenderer
{
    protected $userGridCallback;
    /**
     * UserAdminPage constructor.
     *
     * @param \Prometheus2\common\database\PromDB $database
     */
    public function __construct(DB\PromDB $database)
    {
        $options=new Page\PageOptions();
        parent::__construct($database, $options);

        $datagrid=new Widgets\DataGrid($database,$this, 'user_table','Users', '', true,'cntPromUserID');
        $datagrid->addColumn(Settings\Language::translate('Fullname'), 'row', 'Fullname', 'Fullname');
        $datagrid->addColumn(Settings\Language::translate('Preferred Name'), 'col', 'txtPreferredName', 'txtPreferredName');
        $datagrid->addColumn(Settings\Language::translate('Email'), 'col', 'txtEmail', 'txtEmail');
        $datagrid->addColumn(Settings\Language::translate('Date Created'),'col','datCreated','datCreated',Widgets\DataGridColumn::DATE_FORMAT);
        $datagrid->addColumn(Settings\Language::translate('Last Login'),'col','datLastLogin','datLastLogin',Widgets\DataGridColumn::DATE_FORMAT);

        $this->userGridCallback=new UserGridJSCallback();
        $datagrid->setCallbackRenderingObject($this->userGridCallback);
    }

    /**
     * Render section content
     * @return void
     */
    protected function renderSectionContent(): void
    {
        $query="SELECT
                    prom2_user.cntPromUserID,
                    prom2_user.datCreated,
                    CONCAT(
                
                        IF (
                            COALESCE (enuSalutation, '') = '',
                            '',
                            enuSalutation
                        ),
                        ' ',
                
                    IF (
                        COALESCE (txtFirstname, '') = '',
                        '',
                        txtFirstname
                    ),
                    ' ',
                    IF (
                        COALESCE (txtLastname, '') = '',
                        '',
                        txtLastname
                    )) AS Fullname,
                 prom2_user.txtPreferredName,
                 prom2_user.txtEmail,
                 prom2_user.datLastLogin
                FROM
                    prom2_user
                ORDER BY Fullname";
        $statement=$this->database->prepare($query);
        $statement->execute();
        $this->getWidget('user_table')->renderWidget($statement);
    }
}

/**
 * Class UserGridJSCallback
 * @package Prometheus2\common\modules\admin
 */
class UserGridJSCallback implements Widgets\IDataGridJSCallBacks
{
    public function renderEditRecordJS(): void
    {
        ?>
        alert('Edit User Here');
        <?php
    }

    public function renderAddRecordJS(): void
    {
        ?>
        alert('Add User Here');
        <?php
    }

    public function renderDeleteRecordJS(): void
    {
        ?>
        alert('Delete User Here');
        <?php
    }
}
