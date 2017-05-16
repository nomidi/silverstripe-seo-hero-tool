<?php
class SeoHeroToolEditFile extends DataObject
{
    private static $has_many = array(
    'SeoHeroToolRedirects' => 'SeoHeroToolRedirect',
  );
    private static $singular_name = 'Robots Htaccess Editor';

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $content = $this->displayRobotsContent();
        if ($content !== false) {
            $robotsField = new TextareaField('RobotsContent', _t('SeoHeroToolEditFile.RobotsContentField', 'Content of robots.txt'), $content);
        } else {
            $robotsField = new LiteralField('RobotsContent', _t('SeoHeroToolEditFile.Robotstxtchmod', 'Please check the access right to the file robots.txt'));
        }

        $fields->removeByName('SeoHeroToolRedirects');
        $htaccessGrid = new GridField('SeoHeroToolRedirects', _t('SeoHeroToolEditFile.Redirect', '301 Weiterleitungen'), $this->SeoHeroToolRedirects());
        $config = $htaccessGrid->getConfig();
        $config = GridFieldConfig_RecordEditor::create();
        $htaccessGrid->setConfig($config);

        $fields->addFieldToTab('Root.Main', $robotsField);
        $fields->addFieldToTab('Root.Main', $htaccessGrid);

        return $fields;
    }

    private function robotsPath()
    {
        return $_SERVER['DOCUMENT_ROOT'].'/robots.txt';
    }

    private function displayRobotsContent()
    {
        if ((@fileperms($this->robotsPath()) & 0777) != 0777) {
            return false;
        }
        if (!file_exists($this->robotsPath())) {
            $this->writeFile($this->robotsPath(), "");
        }
        $content = file_get_contents($this->robotsPath());
        return $content;
    }

    private function writeFile($content = "")
    {
        @file_put_contents($this->robotsPath(), $content);
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();
        $this->writeFile($this->RobotsContent);
    }

    public static function current_entry()
    {
        if ($entry = SeoHeroToolEditFile::get()->First()) {
            return $entry;
        }

        return self::make_site_config();
    }

    public static function make_entry()
    {
        $config = SeoHeroToolEditFile::create();
        $config->write();
        return $config;
    }

    public function requireDefaultRecords()
    {
        parent::requireDefaultRecords();
        $entry = SeoHeroToolEditFile::get()->first();
        if (!$entry) {
            self::make_entry();
            DB::alteration_message("Added default SeoHeroToolEditFile", "created");
        }
    }

    public function canCreate($Member = null)
    {
        if (permission::check('SUPERUSER')) {
            return false;
        } else {
            return false;
        }
    }

    public function canDelete($Member = null)
    {
        if (permission::check('SUPERUSER')) {
            return false;
        } else {
            return false;
        }
    }
}
