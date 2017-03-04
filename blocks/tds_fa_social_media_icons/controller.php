<?php
namespace Concrete\Package\TdsFaSocialMediaIcons\Block\TdsFaSocialMediaIcons;

use Concrete\Core\Block\BlockController;

class Controller extends BlockController
{
    protected $btInterfaceWidth = 450;
    protected $btInterfaceHeight = 580;
    protected $btCacheBlockOutput = true;
    protected $btTable = 'btTdsFaSocialMediaIcons';
    protected $btDefaultSet = 'social';

    protected $iconStyles;
    protected $iconColor;
    protected $mediaList = null;

    public function getBlockTypeDescription()
    {
        return t('Add FontAwesome social media icons on your pages.');
    }

    public function getBlockTypeName()
    {
        return t('FA Social Media Icons');
    }

    public function add()
    {
    	$this->view();
    }

    public function edit()
    {
        $this->view();
    }

    public function view()
    {
    	$this->mediaList = unserialize($this->mediaList);
        if (empty($this->mediaList))
        {
        	$this->set('iconShape', 'round');
        	$this->set('iconColor', 'logo');
        	$this->set('iconSize', '25');
        	$this->set('hoverIcon', 'none');
        	$this->set('iconMargin', '0');
        }
        $this->iconColor = $this->getSets()['iconColor'];
    	$this->genIcons();
    	$this->set('mediaList', $this->mediaList);
    }

    public function save($args)
    {
    	$args['iconSize']	= intval($args['iconSize']);
        $args['iconMargin']	= intval($args['iconMargin']);
        $args['mediaList']	= serialize($args['mediaList']);

        parent::save($args);
    }

    public function getIconStyles()
    {
    	return $this->iconStyles;
    }

    public function getMediaList()
    {
    	return $this->mediaList;
    }

    private function genIcons()
    {
    	$mediaListMaster = [
	    	//	name			 fa-					icon color				place holder
	    	'Behance'		=> [ 'fa' => 'behance',		'icolor' => '#1769FF',	'ph' => t("https://www.behance.net/your-account-name")			],
	    	'deviantART'	=> [ 'fa' => 'deviantart',	'icolor' => '#4E6252',	'ph' => t("https://your-account-name.deviantart.com")			],
	    	'Dribbble'		=> [ 'fa' => 'dribbble',	'icolor' => '#EA4C89',	'ph' => t("https://dribbble.com/your-account-name")				],
	    	'Email'			=> [ 'fa' => 'envelope-o',	'icolor' => '#696969',	'ph' => t("mailto:your-email-address@example.com")				],
	    	'Facebook'		=> [ 'fa' => 'facebook',	'icolor' => '#3B5998',	'ph' => t("https://www.facebook.com/your-account-name")			],
	    	'Flickr'		=> [ 'fa' => 'flickr',		'icolor' => '#000000',	'ph' => t("https://www.flickr.com/photos/your-account-name")	],
	    	'Github'		=> [ 'fa' => 'github',		'icolor' => '#000000',	'ph' => t("https://github.com/your-account-name")				],
	    	'GooglePlus'	=> [ 'fa' => 'google-plus',	'icolor' => '#DD4B39',	'ph' => t("https://plus.google.com/+your-account-name")			],
	    	'Instagram'		=> [ 'fa' => 'instagram',	'icolor' => '#517FA4',	'ph' => t("http://instagram.com/your-account-name")				],
	    	'iTunes'		=> [ 'fa' => 'music',		'icolor' => '#0247A4',	'ph' => t("https://itunes.apple.com/...")						],
	    	'Linkedin'		=> [ 'fa' => 'linkedin',	'icolor' => '#007BB6',	'ph' => t("https://www.linkedin.com/in/your-account-name")		],
	    	'Pinterest'		=> [ 'fa' => 'pinterest-p',	'icolor' => '#CB2027',	'ph' => t("https://www.pinterest.com/your-account-name")		],
	    	'Skype'			=> [ 'fa' => 'skype',		'icolor' => '#00AFF0',	'ph' => t("skype:profile_name?your-account-name")				],
	    	'SoundCloud'	=> [ 'fa' => 'soundcloud',	'icolor' => '#FF3A00',	'ph' => t("https://soundcloud.com/your-account-name")			],
	    	'Spotify'		=> [ 'fa' => 'spotify',		'icolor' => '#7AB800',	'ph' => t("https://play.spotify.com/artist/your-account-name")	],
	    	'Tumblr'		=> [ 'fa' => 'tumblr',		'icolor' => '#35465C',	'ph' => t("http://www.your-account-name.tumblr.com")			],
	    	'Twitter'		=> [ 'fa' => 'twitter',		'icolor' => '#55ACEE',	'ph' => t("https://twitter.com/your-account-name")				],
	    	'Vimeo'			=> [ 'fa' => 'vimeo',		'icolor' => '#1AB7EA',	'ph' => t("http://vimeo.com/your-account-name")					],
	    	'Youtube'		=> [ 'fa' => 'youtube',		'icolor' => '#E52D27',	'ph' => t("https://www.youtube.com/user/your-account-name")		],
	    	'Xing'			=> [ 'fa' => 'xing',		'icolor' => '#006567',	'ph' => t("https://www.xing.com/profile/your-account-name")		],
    	];

    	$concrete = \Config::get('concrete');
    	$version = substr($concrete['version_installed'], 0, 1);
    	if ($version != '8')
    	{
    		$mediaListMaster['Pinterest']['fa'] = 'pinterest';
    		$mediaListMaster['Vimeo']['fa'] = 'vimeo-square';
    	}

    	$this->iconStyles = '
			.social-icon:hover { %hoverAttrs% }
			.social-icon {	margin: 0 calc(%iconMargin%px / 2); float: left;
							height: %iconSize%px; width: %iconSize%px; border-radius: %borderRadius%px; }
			.social-icon i.fa {	font-size: calc(%iconSize%px *.6); text-align: center; width: 100%; padding-top: calc((100% - 1em) / 2); }
			.social-icon-black { color: #ffffff; background: #000000; }
			.social-icon-grey  { color: #ffffff; background: #696969; }
		';

    	$mustInit = empty($this->mediaList);
    	foreach ($mediaListMaster as $key => $mProps)
    	{
    		$this->iconStyles .= '	.social-icon-' . $key . ' { color: #ffffff; background: ' . $mProps['icolor'] . '; }'."\n";
    		$this->iconStyles .= '	.social-icon-' . $key . '-inverse { color: ' . $mProps['icolor'] . '; }'."\n";
    		$iconClass = 'social-icon social-icon-' . $key;
    		if ($this->iconColor == 'inverse')
    		{
    			$iconClass .= '-inverse';
    		}
    		$icon =  '<span class="' . $iconClass . '"><i class="fa fa-' . $mProps['fa'] . '"></i></span>';

    		if ($mustInit)
    		{
    			$this->mediaList[$key] = [];
    		}
    		$props = $this->mediaList[$key];
    		if ($props['checked'])
    		{
    			$this->mediaList[$key]['html'] = '<a title="' . t('Go to'). ' ' . $key. '" target="' . $linkTarget . '"  href="' . $props['url'] . '">' . $icon . '</a>';
    		}

    		$this->mediaList[$key]['iconHtml'] = $icon;
    		$this->mediaList[$key]['ph'] = $mProps['ph'];
    	}
    }
}