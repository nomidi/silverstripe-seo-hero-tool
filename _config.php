<?php
//define global path to Components' root folder
#use SilverStripe\Core\Config\Config;

if (!defined('SEO_HERO_TOOL_PATH')) {
    define('SEO_HERO_TOOL_PATH', rtrim(basename(dirname(__FILE__))));
}

#Config::inst()->update('LeftAndMain', 'extra_requirements_css', array(SEO_HERO_TOOL_PATH.'/css/SeoHeroTool.css'));
