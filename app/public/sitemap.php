<?php
/**
 * Dynamic sitemap script.
 *
 * @author Simon Mitchell <kartano@gmail.com>
 *
 * @version     1.0.0           Prototype
 */
require_once dirname(__FILE__) . '/../bootstrap.php';
header('Content-Type:application/xml');
header('charset','utf8');

$sitemap = new SimpleXMLElement("<urlset></urlset>");
$sitemap->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

$url=$sitemap->addChild('url');
$url->addChild('loc', 'http://www.example.com/');
$url->addChild('lastmod', '2017-10-20');
$url->addChild('changefreq', 'monthly');
$url->addChild('priority', '0.8');

echo $sitemap->asXML();
