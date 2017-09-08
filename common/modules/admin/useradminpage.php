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
                <caption>Top 10 Grossing Animated Films of All Time</caption>
                <thead>
                <tr>
                    <th scope="col">Film Title</th>
                    <th scope="col">Released</th>
                    <th scope="col">Studio</th>
                    <th scope="col">Worldwide Gross</th>
                    <th scope="col">Domestic Gross</th>
                    <th scope="col">Foreign Gross</th>
                    <th scope="col">Budget</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <td colspan="7">Sources: <a href="http://en.wikipedia.org/wiki/List_of_highest-grossing_animated_films" rel="external">Wikipedia</a> &amp; <a href="http://www.boxofficemojo.com/genres/chart/?id=animation.htm" rel="external">Box Office Mojo</a>. Data is current as of August 25, 2016.</td>
                </tr>
                </tfoot>
                <tbody>
                <tr>
                    <th scope="row">Frozen</th>
                    <td data-title="Released">2013</td>
                    <td data-title="Studio">Disney</td>
                    <td data-title="Worldwide Gross" data-type="currency">$1,287,000,000</td>
                    <td data-title="Domestic Gross" data-type="currency">$400,738,009	</td>
                    <td data-title="Foreign Gross" data-type="currency">$875,742,326</td>
                    <td data-title="Budget" data-type="currency">$150,000,000</td>
                </tr>
                <tr>
                    <th scope="row">Minions</th>
                    <td data-title="Released">2015</td>
                    <td data-title="Studio">Universal</td>
                    <td data-title="Worldwide Gross" data-type="currency">$1,159,398,397</td>
                    <td data-title="Domestic Gross" data-type="currency">$336,045,770</td>
                    <td data-title="Foreign Gross" data-type="currency">$823,352,627</td>
                    <td data-title="Budget" data-type="currency">$74,000,000</td>
                </tr>
                <tr>
                    <th scope="row">Toy Story 3</th>
                    <td data-title="Released">2010</td>
                    <td data-title="Studio">Disney Pixar</td>
                    <td data-title="Worldwide Gross" data-type="currency">$1,066,969,703</td>
                    <td data-title="Domestic Gross" data-type="currency">$415,004,880</td>
                    <td data-title="Foreign Gross" data-type="currency">$651,964,823</td>
                    <td data-title="Budget" data-type="currency">$200,000,000</td>
                </tr>
                <tr>
                    <th scope="row">Zootopia</th>
                    <td data-title="Released">2016</td>
                    <td data-title="Studio">Disney</td>
                    <td data-title="Worldwide Gross" data-type="currency">$1,023,227,498</td>
                    <td data-title="Domestic Gross" data-type="currency">$341,268,248</td>
                    <td data-title="Foreign Gross" data-type="currency">$681,959,250</td>
                    <td data-title="Budget" data-type="currency">$150,000,000</td>
                </tr>
                <tr>
                    <th scope="row">Despicable Me 2</th>
                    <td data-title="Released">2013</td>
                    <td data-title="Studio">Universal</td>
                    <td data-title="Worldwide Gross" data-type="currency">$970,761,885</td>
                    <td data-title="Domestic Gross" data-type="currency">$368,061,265</td>
                    <td data-title="Foreign Gross" data-type="currency">$602,700,620</td>
                    <td data-title="Budget" data-type="currency">$76,000,000</td>
                </tr>
                <tr>
                    <th scope="row">The Lion King</th>
                    <td data-title="Released">1994</td>
                    <td data-title="Studio">Disney</td>
                    <td data-title="Worldwide Gross" data-type="currency">$987,483,777</td>
                    <td data-title="Domestic Gross" data-type="currency">$422,783,777</td>
                    <td data-title="Foreign Gross" data-type="currency">$564,700,000</td>
                    <td data-title="Budget" data-type="currency">$45,000,000</td>
                </tr>
                <tr>
                    <th scope="row">Finding Nemo</th>
                    <td data-title="Released">2003</td>
                    <td data-title="Studio">Pixar</td>
                    <td data-title="Worldwide Gross" data-type="currency">$936,743,261</td>
                    <td data-title="Domestic Gross" data-type="currency">$380,843,261</td>
                    <td data-title="Foreign Gross" data-type="currency">$555,900,000</td>
                    <td data-title="Budget" data-type="currency">$94,000,000</td>
                </tr>
                <tr>
                    <th scope="row">Shrek 2</th>
                    <td data-title="Released">2004</td>
                    <td data-title="Studio">Dreamworks</td>
                    <td data-title="Worldwide Gross" data-type="currency">$919,838,758</td>
                    <td data-title="Domestic Gross" data-type="currency">$441,226,247</td>
                    <td data-title="Foreign Gross" data-type="currency">$478,612,511</td>
                    <td data-title="Budget" data-type="currency">$150,000,000</td>
                </tr>
                <tr>
                    <th scope="row">Finding Dory</th>
                    <td data-title="Released">2016</td>
                    <td data-title="Studio">Disney Pixar</td>
                    <td data-title="Worldwide Gross" data-type="currency">$916,221,557</td>
                    <td data-title="Domestic Gross" data-type="currency">$478,714,390</td>
                    <td data-title="Foreign Gross" data-type="currency">$437,507,167	</td>
                    <td data-title="Budget" data-type="currency">$250,000,000</td>
                </tr>
                <tr>
                    <th scope="row">Ice Age: Dawn of the Dinosaurs</th>
                    <td data-title="Released">2009</td>
                    <td data-title="Studio">Fox</td>
                    <td data-title="Worldwide Gross" data-type="currency">$886,686,817</td>
                    <td data-title="Domestic Gross" data-type="currency">$196,573,705</td>
                    <td data-title="Foreign Gross" data-type="currency">$690,113,112	</td>
                    <td data-title="Budget" data-type="currency">$90,000,000</td>
                </tr>
                </tbody>
            </table>
        </div>
        <?php
    }
}