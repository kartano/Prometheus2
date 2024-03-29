<?php
/**
 * Page rendering class options
 *
 * @author          Simon Mitchell <kartano@gmail.com>
 *
 * @namespace       Prometheus2\common\pagerendering
 *
 * @version         1.1.0        2017-12-03 20:18:00 SM:  Added uses_angular property.
 */

/**
 * @property bool   uses_jquery      Flag to determine if header needs to include jquery.
 * @property bool   uses_jqueryui    Flag to determine if header needs to include jquery ui
 * @property bool   display_header   Flag to determine if page <header> element is rendered.
 * @property bool   display_footer   Flag to determine if page <footer> element is rendered.
 * @property string document_type    The HTML Document type.
 * @property string render_body_only If TRUE then only the body of the page is render - the <HEAD> and <BODY> tags do
 *           NOT get used.
 * @property string title            The title of the page.  For the SITE title, use that supplied in SETTINGS.
 * @property string sectionid        The HTML ID.
 * @property string description The Description to be used for the meta tags in web pages.
 * @property bool   requires_logged_in  If TRUE then session authentication will be checked.  THIS WILL *NOT* WORK IF YOU ARE OPERATING WITH CONTENT ONLY RENDERING!
 * @property bool   uses_font_awesome If TRUE then the CDN for the font awesome icon set is imported.  TRUE by default.
 * @property bool   uses_angular    If TRUE then the CDN for the AngularJS library is included.
 */

namespace Prometheus2\common\pagerendering;

use Prometheus2\common\settings\Settings AS CFG;

/**
 * Class PageOptions
 * @package Prometheus2\common\pagerendering
 */
class PageOptions
{
    /**
     * @var int $sectioncount A static var shared between all page options, to help ensure they are unique.
     */
    protected static $sectioncount = 1;
    /**
     * @var array The array of settings
     */
    protected $data = [];

    /**
     * PageOptions constructor.
     */
    public function __construct()
    {
        $this->uses_jquery = true;
        $this->uses_jqueryui = true;
        $this->display_header = true;
        $this->display_footer = true;

        $this->document_type = 'HTML';

        $this->render_body_only = false;

        $this->title = CFG::get('app', 'name');

        $this->sectionid = 'section' . self::$sectioncount++;

        $this->description = CFG::get('app','description');

        $this->requires_logged_in=false;

        $this->uses_font_awesome=true;

        $this->uses_angular=false;
    }

    /**
     * @param string $name The name of the setting.
     *
     * @return mixed The return value. NULL of setting not found.
     */
    public function __get(string $name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        } else {
            return null;
        }
    }

    /**
     * Magic method - set.
     *
     * @param string $name  The name of the setting.
     * @param mixed  $value The value for the setting.
     */
    public function __set(string $name, $value)
    {
        $this->data[$name] = $value;
    }
}
