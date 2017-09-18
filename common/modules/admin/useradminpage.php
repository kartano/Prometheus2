<?php
/**
 * User administration page.
 *
 * @author          Simon Mitchell <kartano@gmail.com>
 *
 * @namespace       Prometheus2\common\modules\admin
 *
 * @version         1.0.0           2017-09-08 2017-09-08 Prototype
 */


namespace Prometheus2\common\modules\admin;
use Prometheus2\common\pagerendering as Page;
use Prometheus2\common\database as DB;
use Prometheus2\common\widgets AS Widgets;

class UserAdminPage extends Page\PageRenderer
{
    /**
     * UserAdminPage constructor.
     *
     * @param \Prometheus2\common\database\PromDB $database
     */
    public function __construct(DB\PromDB $database)
    {
        $options=new Page\PageOptions();
        $options->render_body_only=true;
        parent::__construct($database, $options);

        $datagrid=new Widgets\DataGrid($database,$this, 'user_table','Users');
        $datagrid->addColumn('Fullname', 'row', 'Fullname', 'Fullname');
        $datagrid->addColumn('Preferred Name', 'col', 'txtPreferredName', 'txtPreferredName');
        $datagrid->addColumn('Email', 'col', 'txtEmail', 'txtEmail');
        $datagrid->addColumn('Date Created','col','datCreated','datCreated',Widgets\DataGridColumn::DATE_FORMAT);
        $datagrid->addColumn('Last Login','col','datLastLogin','datLastLogin',Widgets\DataGridColumn::DATE_FORMAT);
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