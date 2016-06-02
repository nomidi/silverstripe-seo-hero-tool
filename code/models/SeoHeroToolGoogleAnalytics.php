<?php
class SeoHeroToolGoogleAnalytics extends Extension
{
    public function getAnalyticsKey()
    {
        $return =  Config::inst()->get('SeoHeroTool', 'google_key');
        if ($return == '') {
            $return =  'not valid';
        }
        return $return;
    }

    public function getEnvironmentType()
    {
        $return = Config::inst()->get('SeoHeroTool', 'environment_type');

        if ($return == '') {
            $return =  'dev';
        }
        return $return;
    }

    public function getMemberStatus()
    {
        $return = Config::inst()->get('SeoHeroTool', 'member_status');

        if (!$return) {
            $return =  false;
        }
        return $return;
    }

    public function getAnonymizeStatus()
    {
        $return = Config::inst()->get('SeoHeroTool', 'anonymizeIP');

        if (!$return) {
            $return =  true;
        }
        return $return;
    }

    public function getUserOptOut()
    {
        $return = Config::inst()->get('SeoHeroTool', 'userOptOut');
        if (!$return) {
            $return = true;
        }
      
        return $return;
    }


    public function onAfterInit()
    {
        $google_key = $this->getAnalyticsKey();
        $env_type = $this->getEnvironmentType();
        $member_status = $this->getMemberStatus();
        if ($google_key != 'not valid') {
            if ($env_type == Config::inst()->get('Director', 'environment_type') or $env_type == 'all') {
                if ((!$member_status && Member::currentUserID() == 0) || $member_status) {
                    Requirements::customScript($this->owner->renderWith('SeoHeroToolsGoogleAnalytics'), 'SeoHeroToolsGoogleAnalytics');
                }
            }
        }
    }
}
