<?php
/**
 * DESCRIPTION
 *
 * @author   karta  <EMAIL>
 *
 * @namespace   Prometheus2\common\modules\admin
 *
 * @version         1.0.0        2017-08-25 15:12 Prototype
 */

namespace Prometheus2\common\modules\admin;

use Prometheus2\common\database as DB;
use Prometheus2\common\pagerendering as Page;
use Prometheus2\common\pagerendering\PageOptions;
use Prometheus2\common\settings\Settings AS CFG;

/**
 * Class Prom2AdminHeader
 * @package Prometheus2\common\modules\admin
 */
 class Prom2AdminHeader extends Page\PageRenderer
 {
     /**
      * Prom2AdminHeader constructor.
      * @param DB\PromDB $database The database
      * @param PageOptions $options Page options
      */
     public function __construct(DB\PromDB $database)
     {
         $options = new Page\PageOptions();
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
         <span class="prom2admin_headerspan">Prometheus2 - <?=CFG::get('app','description');?></span>&nbsp;<span class="prom2admin_versionno">version <?=PROM2_VERSION_NO;?></span>
         <p></p>
         <hr>
         <?php
     }
}
