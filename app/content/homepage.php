<?php
/**
 * Default home page
 *
 * @author          Simon Mitchell <kartano@gmail.com>
 *
 * @namespace       Prometheus2\app\content
 *
 * @version         1.0.0           2017-08-22 2017-08-22 Prototype
 */


namespace Prometheus2\app\content;

use Prometheus2\common\database as DB;
use Prometheus2\common\pagerendering AS PR;

/**
 * Class HomePage
 * @package Prometheus2\app\content
 */
class HomePage extends PR\PageRenderer
{
    /**
     * HomePage constructor.
     *
     * @param \Prometheus2\common\database\PromDB           $database
     * @param \Prometheus2\common\pagerendering\PageOptions $options
     */
    public function __construct(DB\PromDB $database, PR\PageOptions $options)
    {
        parent::__construct($database, $options);
    }

    /**
     * Render the header section of the document.
     */
    protected function renderHeader(): void
    {
        ?>
        <h1>The Default Homepage</h1>
        <hr>
        <?php
    }

    /**
     * Render section content
     * @return void
     */
    protected function renderSectionContent(): void
    {
        ?>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse dictum sollicitudin nunc, quis posuere
            dolor aliquam nec. Nullam tortor purus, lacinia semper ligula sit amet, pellentesque vulputate quam. Proin
            posuere vitae nibh ac ullamcorper. Donec ut lobortis sapien. Fusce imperdiet euismod mi, eget pellentesque
            massa pharetra sit amet. Proin finibus venenatis lorem non pulvinar. Sed viverra dictum imperdiet. Cras
            venenatis ex sit amet pulvinar luctus. Cras pharetra mauris quis augue accumsan sodales. Aenean interdum
            malesuada molestie.</p>
        <p>Fusce arcu mauris, gravida ac feugiat sed, rutrum sed orci. Nullam interdum convallis leo sed porttitor.
            Proin eu gravida ex. Aliquam sapien diam, euismod ac quam ac, cursus efficitur orci. In suscipit nisl eget
            urna tempus, id pharetra justo lobortis. Vivamus malesuada ex sem, non elementum nisl feugiat ac. Aliquam
            fringilla neque vitae massa auctor semper sit amet malesuada diam.</p>
        <p>Fusce nec vulputate felis. Praesent venenatis sit amet ipsum ultricies eleifend. Maecenas malesuada rhoncus
            enim, eu pretium sapien consequat eu. Cras aliquam pellentesque ipsum. Integer aliquam justo sit amet
            scelerisque rhoncus. Ut in lacus in metus consectetur vulputate quis a leo. Suspendisse ornare dui mi, vitae
            hendrerit justo bibendum vel.</p>
        <p>Quisque venenatis vehicula nisl non egestas. In ipsum diam, feugiat vel facilisis ut, bibendum et nisi. Etiam
            in purus eu augue sollicitudin pellentesque. Integer efficitur orci elit, eget vehicula dui dictum vel.
            Nulla ac aliquam orci, eget iaculis diam. Etiam molestie tortor nec urna lacinia pellentesque. Integer sit
            amet vestibulum odio. Maecenas vel nisl in lacus lacinia fermentum nec a turpis. Pellentesque iaculis velit
            ut nisl vulputate placerat quis a erat. Cras vitae nisi id odio sagittis euismod nec maximus ex. Aliquam
            ligula nunc, efficitur vitae congue eget, ultrices vel dui.</p>
        <p>Maecenas in venenatis odio. Phasellus in neque non sapien ullamcorper consequat. Donec velit felis, interdum
            ut sem vel, dignissim laoreet mauris. Sed sed libero porta, ornare erat in, ornare justo. Integer eu risus
            aliquam, dapibus ligula non, vehicula risus. Praesent non dictum felis, non maximus dolor. Donec suscipit
            turpis ut tincidunt efficitur. Duis eu pretium dui, quis ultrices nisl. Fusce posuere feugiat metus eget
            finibus. Donec quis nisl et dolor commodo tempus. Mauris tincidunt aliquet sollicitudin. Nunc in tellus et
            libero accumsan euismod. Donec fermentum lectus nunc, in tristique quam bibendum at. Pellentesque
            pellentesque dictum diam. Aenean fermentum orci in urna euismod consequat.</p>
        <?php
    }

    /**
     * Render the footer.
     * @return void
     */
    protected function renderFooter(): void
    {
        ?>
        <hr>
        <i>Brought to you by SunsetCoders. &copy;<?=date('Y');?></i>
        <?php
    }
}