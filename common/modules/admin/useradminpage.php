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
    }

    /**
     * Render section content
     * @return void
     */
    protected function renderSectionContent(): void
    {
        ?>
        <style>
            <?php
            require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'table.css';
            ?>
        </style>
        <div class="container">
            <table class="responsive-table">
                <caption>Users List</caption>
                <thead>
                <tr>
                    <th scope="col">Fullname</th>
                    <th scope="col">Preferred Name</th>
                    <th scope="col">Date Created</th>
                    <th scope="col">Email</th>
                    <th scope="col">Last Login</th>
                </tr>
                </thead>
                <tbody>
                <?php
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
                $statement->bind_result($id, $datecreated, $fullname, $prename, $email, $datelastlogin);
                while ($statement->fetch()) {
                    ?>
                    <tr>
                        <th scope="row"><?=$fullname;?></th>
                        <td data-title="PrefName"><?=$prename;?></td>
                        <td data-title="DateCreated"><?=date('d/m/Y',strtotime($datecreated));?></td>
                        <td data-title="Email"><?=$email;?></td>
                        <td data-title="LastLogin"><?=date('d/m/Y',strtotime($datelastlogin));?></td>
                    </tr>
                    <?php
                }
                $statement->close();
                ?>
                </tbody>
            </table>
        </div>
        <?php
    }
}