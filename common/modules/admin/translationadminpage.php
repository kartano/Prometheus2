<?php
/**
 * Translation List admin page
 *
 * @author          Simon Mitchell <kartano@gmail.com>
 *
 * @namespace       Prometheus2\common\modules\admin
 *
 * @version         1.0.0           2017-09-20 2017-09-20 Prototype
 */


namespace Prometheus2\common\modules\admin;

use Prometheus2\common\pagerendering as Page;
use Prometheus2\common\database as DB;
use Prometheus2\common\widgets AS Widgets;
use Prometheus2\common\settings AS Settings;

/**
 * Class TranslationAdminPage
 * @package Prometheus2\common\modules\admin
 */
class TranslationAdminPage extends Page\PageRenderer
{
    protected $translationGridCallback;
    /**
     * TranslationAdminPage constructor.
     *
     * @param \Prometheus2\common\database\PromDB $database
     */
    public function __construct(DB\PromDB $database)
    {
        $options = new Page\PageOptions();
        parent::__construct($database, $options);

        $datagrid = new Widgets\DataGrid($database, $this, 'translation_table', 'Translations','',true,'cntTranslationID');
        $datagrid->addColumn(Settings\Language::translate('Source Term'), 'row', 'txtSourceString', 'txtSourceString');
        $datagrid->addColumn(Settings\Language::translate('Replacement Term'), 'col', 'txtReplacementString',
            'txtReplacementString');
        $this->translationGridCallback=new TranslationGridJSCallback();
        $datagrid->setCallbackRenderingObject($this->translationGridCallback);
    }

    /**
     * Render section content
     * @return void
     */
    protected function renderSectionContent(): void
    {
        $query = "SELECT
prom2_translations.cntTranslationID,
prom2_translations.txtSourceString,
prom2_translations.txtReplacementString
FROM
prom2_translations
ORDER BY txtSourceString
";
        $statement = $this->database->prepare($query);
        $statement->execute();
        $this->getWidget('translation_table')->renderWidget($statement);
    }
}

/**
 * Class TranslationGridJSCallback
 * @package Prometheus2\common\modules\admin
 */
class TranslationGridJSCallback implements Widgets\IDataGridJSCallBacks
{
    public function renderDeleteRecordJS(): void
    {
        ?>
        alert('Delete Translation here');
        <?php
    }

    public function renderAddRecordJS(): void
    {
        ?>
        alert('Add Translation here');
        <?php
    }

    public function renderEditRecordJS(): void
    {
        ?>
        alert('Edit Translation here');
        <?php
    }
}
