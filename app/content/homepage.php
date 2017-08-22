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
     * Render any custom JS to go into the document ready script.
     * @return void
     */
    protected function renderDocumentReady(): void
    {
        ?>
        $('#tabs').tabs();
        $( '#accordion' ).accordion();
        <?php
    }

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
        <br>
        <div id="tabs">
            <ul>
                <li><a href="#tabs-1">Nunc tincidunt</a></li>
                <li><a href="#tabs-2">Proin dolor</a></li>
                <li><a href="#tabs-3">Aenean lacinia</a></li>
            </ul>
            <div id="tabs-1">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque sed lorem urna. Ut sed diam
                    volutpat, convallis risus iaculis, bibendum erat. Nunc tempor magna et auctor pharetra. Cras nunc
                    enim, molestie nec neque quis, sollicitudin pellentesque metus. Cras volutpat tortor et lorem
                    congue, at eleifend ipsum sollicitudin. Nulla facilisi. Ut ac nisi molestie, pulvinar risus
                    convallis, facilisis risus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per
                    inceptos himenaeos. Duis tincidunt nisl est. Proin tincidunt mi nec ante vestibulum, a sagittis
                    dolor venenatis. Nunc a porttitor nisl, consectetur sagittis turpis.</p>
            </div>
            <div id="tabs-2">
                <p>Mauris vitae malesuada elit. Duis porta quis ipsum at porttitor. Aenean ante nulla, consequat at
                    lacinia sit amet, hendrerit et velit. Aenean et pulvinar mi, ac facilisis velit. Mauris mauris nisi,
                    consectetur nec sem sit amet, faucibus bibendum diam. Aliquam iaculis malesuada felis quis semper.
                    Vestibulum at neque mi. Ut tempus ac lectus a tristique. Praesent sit amet posuere ante. Cras
                    euismod dapibus ex ac ultrices. Curabitur odio ligula, bibendum non sapien et, pulvinar pulvinar
                    eros. Quisque metus orci, eleifend vel nisi vel, accumsan viverra tortor. Fusce rhoncus dolor congue
                    auctor feugiat. Maecenas aliquam eleifend massa, vel posuere lectus vulputate non. Duis tempus
                    viverra nibh, et interdum arcu. Proin mi sem, semper a ipsum non, eleifend sagittis massa.</p>
            </div>
            <div id="tabs-3">
                <p>Quisque quis elit malesuada, dignissim nunc et, elementum erat. Donec auctor interdum elementum.
                    Maecenas et risus convallis mauris convallis consectetur non eget ligula. Duis vitae elit vel leo
                    consectetur hendrerit. Duis tempus ac ante quis ullamcorper. Morbi ac nibh et felis venenatis
                    dictum. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis
                    egestas. Vestibulum eget suscipit dui. Nullam feugiat felis sit amet tincidunt ullamcorper. Sed
                    sagittis consequat consectetur. Fusce blandit vulputate posuere.</p>
            </div>
        </div>
        <br>
        <div id="accordion">
            <h3>Section 1</h3>
            <div>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam vitae finibus est, quis aliquam diam.
                    Suspendisse convallis laoreet aliquam. Aliquam erat volutpat. Aliquam sit amet aliquet tellus. Fusce
                    ac lobortis erat. Vivamus sed nisl vitae nunc facilisis cursus at sed lectus. Fusce purus urna,
                    vehicula ac sagittis sed, aliquam in ligula.</p>
            </div>
            <h3>Section 2</h3>
            <div>
                <p>Phasellus aliquet, felis a congue ultrices, purus est suscipit quam, in vestibulum quam ante at
                    tellus. Donec ullamcorper faucibus elementum. Sed ac facilisis turpis. Pellentesque rhoncus sit amet
                    urna at rutrum. Phasellus eu egestas ligula. Sed tempor magna non facilisis iaculis. Etiam eu ligula
                    elementum ante consectetur vehicula vel pulvinar diam. Suspendisse neque neque, pharetra vel
                    ultricies eget, ultrices eu arcu. Proin enim ligula, tincidunt sed dui ac, fringilla facilisis
                    metus. Donec vel ornare nulla, nec cursus elit. Aliquam luctus felis eu lobortis maximus. Integer id
                    enim nec ipsum aliquam varius blandit nec magna.</p>
            </div>
            <h3>Section 3</h3>
            <div>
                <p>Donec aliquet libero sapien, et gravida metus dictum et. Praesent non pretium turpis. Suspendisse
                    commodo, ex vel commodo laoreet, nisi enim faucibus turpis, id ultrices felis nisl quis nibh.
                    Aliquam sed turpis eget lacus ullamcorper molestie. Pellentesque habitant morbi tristique senectus
                    et netus et malesuada fames ac turpis egestas. Phasellus sit amet tempus tortor. Suspendisse iaculis
                    mauris quis metus bibendum, viverra vulputate ipsum aliquet. Cras laoreet nec sem lobortis
                    scelerisque. Etiam enim nisi, cursus quis tincidunt quis, pellentesque mollis metus.</p>
                <ul>
                    <li>List item one</li>
                    <li>List item two</li>
                    <li>List item three</li>
                </ul>
            </div>
            <h3>Section 4</h3>
            <div>
                <p>In suscipit tincidunt arcu, sed consequat orci commodo iaculis. Nam pulvinar mi at libero finibus
                    euismod. Ut tempor tempus nisi non bibendum. Aliquam condimentum elementum magna vitae dictum. Nunc
                    in ex et ex vulputate tincidunt. Maecenas ullamcorper rhoncus metus tempor accumsan. Quisque in
                    gravida lectus. Aenean sit amet felis est. Duis facilisis ex in viverra fermentum. Ut ullamcorper
                    nunc non tortor tincidunt elementum. Aenean tincidunt arcu nec enim porta, ac euismod nisl bibendum.
                    Etiam auctor malesuada tellus, ac ullamcorper nulla volutpat vitae. Etiam efficitur turpis massa, eu
                    ornare justo congue sit amet. Ut porttitor leo eget aliquet iaculis. Donec tempor dolor in erat
                    rutrum, a mattis velit aliquet. Proin tempus est vel lobortis fringilla.</p>
            </div>
        </div>
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
        <i>Brought to you by SunsetCoders. &copy;<?= date('Y'); ?></i>
        <?php
    }
}