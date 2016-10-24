<?php
class SeoHeroTool extends Extension
{

    private static $db = array(
        'Follow' => "Enum('if,in,i,nf,nn,n','if')",
        'FollowType' => 'Boolean',
        'GenMetaDesc' => 'Text',
        'NewTitle' => 'Text',
        'Keyword' => 'Text',
        'FeaturedKeyword' => 'Text',
        'KeywordQuestion' => 'Text',
        'Canonical' => 'Text',
        'CanonicalAll' => 'Boolean',
    );

    private static $defaults = array(
        'FollowType' => true,
    );

    public static $title_display;

    public static $current_meta_desc;

    public function updateCMSFields(FieldList $fields)
    {
        return $fields;
    }
}
