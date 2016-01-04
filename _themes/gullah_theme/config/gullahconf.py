#!/usr/bin/env python
# -*- coding: utf-8 -*- #
from __future__ import unicode_literals, print_function
import sys,os
sys.path.insert(0, '.')
from hypersettings import *

EDIT_THIS = False
SUMMARY_END_MARKER = '<!--more-->'
PAGE_PATHS                     = ['content/pages']
ARTICLE_PATHS                  = ['content/posts']
###########################
#     General Settings    #
###########################
TIMEZONE                      = 'UTC'
DEFAULT_LANG                  = 'en'
SITEPATH                      = '/Users/hyperking/HyperBox/Dropbox/ForesightCommunication/GullahCelebration/gullah.com/gullah_site'

AUTHOR                        = "Charles Young, III, Chairman"
SITENAME                      = "Hilton Head Island - Gullah Celebration"
SITEDESCRIPTION               = "Celebration of Gullah Culture and Heritage"
KEYWORDS_DEFAULT              = "Gullah Celebration, Hilton Head Island,gullah festival, Gullah Music, Gullah Heritage, geechee"
# SITEURL                       = 'http://gullah.dev'
SITEURL                       = 'http://www.gullahcelebration.com'
CLOUDURL                      = 'http://cloud.gullahcelebration.com'
ENDDATE                       = datetime.now().strftime('2014-02-02')


ASSETSURL                     = CLOUDURL 
SITETHUMBNAIL                 = "http://cloud.gullahcelebration.com/img/sitethumb.jpg"
DEFAULT_PAGINATION            = 10
DEFAULT_DATE_FORMAT           = ('%Y-%m-%d')
DEFAULT_DATE                  ="fs"
USE_FOLDER_AS_CATEGORY        = True
SUMMARY_MAX_LENGTH            = 20
RELATED_POSTS_MAX             = 6
DEFAULT_PAGINATION            = 10
USE_FOLDER_AS_CATEGORY        = True
DISPLAY_PAGES_ON_MENU         = True
SHOW_CATEGORY_IN_MENU         = False
REVERSE_ARTICLE_ORDER         = True
RELATIVE_URLS                 = False
FEED_DOMAIN                   = SITEURL
TAG_FEED_RSS                  = None
RELATED_POSTS_MAX             = 5
RECENT_ARTICLES_COUNT         = 4
WITH_FUTURE_DATES             = True

# DEFAULT_METADATA = {('Meta','sample'),}
ASSET_SOURCE_PATHS = ['static/',]
    

###################
#     RWD MENU OPTIONS     #
###################
MULTI_LEVEL_MOBILE_MENU       = False
MULTI_LEVEL_PUSH_MENU         = False
BASIC_MOBILE_MENU             = True
NO_JAVA_MOBILE_MENU           = False
WIDGETS                       = True
###################
#     Plugins     #
###################
PLUGINS                      =[
                              'related_posts',
                              'neighbors',
                              'gallery',
                              'subcategory',
                              # 'md_yaml',
                              # 'summary',
                              'assets',
                              ]
###########################
# Social Networks & Links #
###########################
PAGE_META = {
'gallery': {
  'description':'Gullah Celebration Media Gallery. Browse our collection of Videos, Photos and Links',
  'phrase':'Ya make memories and ya live long'  
  },
'gullah-events':{
  'description':'Gullah Celebration Events. Browse Gullah Celebration events and buy tickets.',
  'phrase':'Gullah phrase for buying tickets'
  },
'gullah-store':{
  'description':'Come by our store.',
  'phrase':'come to da sto'
  },
'Travel Info':{
  'description':'If you don\'t have horse for ride; ride cow',
  'phrase':'Ef yent hab hawss fuh ride,ride cow!'
  },
'About Us':{
  'description':'',
  'phrase':'about us gullah phrase'
  },
'Volunteer':{
  'description':'',
  'phrase':'about us gullah phrase'
  },
'Our Sponsors':{
  'description':'',
  'phrase':'Thank You Hilton Head!'
  }
}


###########################
# Social Networks & Links #
###########################
DISQUS              = "gullahcelebration"
GOOGLESITEVERIFY              = "PYEJXJXY7ugI4Cp9wLLpmFa-w6zWczcWBOWaETAlh-A"
GOOGLE_ANALYTICS              = 'UA-37361255-1'
SOCIAL =                      [
                              ('twitter', 'http://twitter.com/gullahiltonhead'),
                              ('facebook', 'https://www.facebook.com/gullah.celebration'),
                              ('pinterest', 'http://www.pinterest.com/gullahiltonhead/'),
                              ]

RSS_FEED_FROM_BLOG            = 'http://blog.gullahcelebration.com/feeds/posts/default/?alt=rss'
CATEGORY_FEED_RSS             = 'feeds/%s.rss.xml'


THEME                         = THEME_PATH+"/gullah_theme"
THEME_STATIC_DIR              = 'assets/'
# THEME_STATIC_PATHS            = ['static',SITEPATH+'/content/media',]

READERS = {'html': None}

# Specify the directory you want to copy from the content folder as static.
# Note this will copy listed folder(s) and all contents within them into your root output directory
STATIC_PATHS = [
    '_extra',
    'assets',
    ]
# Use this along with the STATIC_PATHS setting to specify particular directories
# you wish your static files to reside.
EXTRA_PATH_METADATA = {
    '_extra/robots.txt': {'path': 'robots.txt'},
    '_extra/crossdomain.xml': {'path': 'crossdomain.xml'},
    '_extra/favicon.ico': {'path': 'favicon.ico'},
    '_extra/humans.txt': {'path': 'humans.txt'},
    '_extra/.htaccess': {'path': '.htaccess'},
    }



###########################
#       Url Settings      #
###########################
PAGE_URL =          '{slug}'
CATEGORY_URL =      '{slug}'
ARTICLE_URL =       '{category}/{date:%Y}/{date:%B}/{slug}'
TAG_URL =           'tags/{slug}'
###########################
#      Routes Settings    #
###########################
TAG_SAVE_AS = 'tags/{slug}/index.html'
PAGE_SAVE_AS        ='{slug}/index.html'
CATEGORY_SAVE_AS    ='{slug}/index.html'
ARTICLE_SAVE_AS     ='{category}/{date:%Y}/{date:%B}/{slug}/index.php'
BLOG_INDEX_SAVE_AS  = 'readme.md'
DIRECT_TEMPLATES    = ('blog_index',)
TEMPLATE_PAGES      ={'sitemap.html': 'sitemap.xml'}

SUBCATEGORY_SAVE_AS = os.path.join('', '{savepath}/index.html')
SUBCATEGORY_URL = '{fullurl}'
###########################
#     Site Data Tuples    #
###########################
MENU                    =[
                        ('About Us'),
                        ('Gullah Events'),
                        ('Gullah Store'),
                        ('Gullah Magazine'),
                        ('Gallery'),
                        ('Contact Us')
                        ]

SITE_CREDITS                  =[
                              # ('address','Hilton Head Island, SC 29925',''),
                              ('hotline','843-255-7304',''),
                              # ('nibcaa','843-255-7303',''),
                              ('email','info@gullahcelebration.com','mailto:info@gullahcelebration.com'),
                              ('&copy;','2014'+ SITENAME,''),
                              ('gullah logo by','Diane Britton Dunham',''),
                              ('site made by','Hyper King Media','http://hyperkingmedia.com')
                              ]                             

SITE_DOWNLOADS                =[
                              ('Event Guide',ASSETSURL +'/img/eventguide.pdf'),
                              ('Vendor Application',ASSETSURL +'/img/VendorApplication.pdf'),
                              ('Artist Application',ASSETSURL +'/img/ArtistApplication.pdf'),
                              ]
ISOFILTER                     ={
                                'events':{'Arts','Music','Food'},
                                'shop':{'Gifts','Posters','Apparel'}
                              }
                                
                              
CALL_TO_ACTION                =[
                              ('Buy',' Tickets',SITEURL+'/gullah-events/'),
                              ('Shop',' Store',SITEURL+'/gullah-store/'),
                              ('Event',' Guide',ASSETSURL +'/img/eventguide.pdf'),
                              ]
ASSETS_DIR = SITEURL+'/assets/img/sponsors/'
SPONSORS                =[
                              ('Coca-Cola','http://us.coca-cola.com/home/',ASSETS_DIR+'cc_logo.png'),
                              ('HHICC','http://www.hiltonheadchamber.org/',ASSETS_DIR+'hhicc_logo.gif'),
                              ('SCPRT','http://www.scprt.com/', ASSETS_DIR+'sc_logo.jpg'),
                              ('Arts Center of CC',' http://www.artshhi.com/', ASSETS_DIR+'acc_logo.png'),
                              ('SCPRT','http://www.scprt.com/', ASSETS_DIR+'pe_logo.gif'),
                              ('Town of Hilton Head','http://www.hiltonheadislandsc.gov/', ASSETS_DIR+'tohh_logo.jpg'),
                              ('Coastal Discovery Museum','http://www.coastaldiscovery.org/', ASSETS_DIR+'cdm_logo.png'),
                              ('Gtours','http://gullaheritage.com/', ASSETS_DIR+'gtours.png'),
                              ('Westin Resort Spa',' http://www.westinhiltonheadisland.com/', ASSETS_DIR+'wrs_logo.jpg'),
                              ('Custom Audio Video',' http://custom-audio-video.com/', ASSETS_DIR+'cav_logo.jpg'),
                              ('C&J lawn care',SITEURL, ASSETS_DIR+'cj_logo.png'),
                              ('Palmetto Dunes Oceanfront Resort','http://www.palmettodunes.com/ppc-book/?promocode=SPRING&gclid=CK6F24SvzcMCFUMjgQod0bgArw', ASSETS_DIR+'palmetto_ocean.png'),
                              ('CArt League of Hilton Head','http://artleaguehhi.com/', ASSETS_DIR+'artl_logo.png'),
                              ]

##################################
#  Simple Paypal Cart Settings   #
##################################
PAYPALCART                    = True
PAY_PAYPAL_ACCOUNT            = 'nibcaa@aol.com'
PAY_CURRENCY                  = 'USD'
PAY_SUCCESSURL                = SITEURL +'/gullah-store-thank-you/'
PAY_CANCELURL                 = SITEURL +'/gullah-store-cancel/'
