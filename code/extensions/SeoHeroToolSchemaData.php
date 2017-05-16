<?php

class SeoHeroToolSchemaData extends Extension
{
    public function onAfterInit()
    {
        Requirements::insertHeadTags($this->OutSchemaData());
    }

    public function OutSchemaData()
    {
        $data = array('test'=>'werder');
        return '<script type="application/ld+json">'.json_encode($data).'</script>';
    }
}
