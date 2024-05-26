<?php

define('IGNORES',[".","..","cgi-bin",".DS_Store","_"]);
define('LIBDIR', __DIR__.'/vendors');
define('CURRDATETIME', date("Y-m-d h:i:sa"));
define('CONFIG_EXT', '.json');
define('PAGE_EXT', '.md');
require __DIR__ . '/utils.php';
require_once 'Request.php';
// Vendor libs
require_once LIBDIR.'/vendor/autoload.php';


class HyperSite
{
    public $configs;
    public $sitepath;
    public $contentDir;
    public $themesDir;
    public $pagesDir;
    public $articlesDir;
    public $templatesDir;
    public $assetsDir;
    public $assetsURI;
    public $dbpath;
    public HyperRequest $request;
    private ParsedownExtra $contentParser;
    private \Twig\Environment $templateParser;

    public function __construct(string $sitepath)
    {

        $this->request          = new HyperRequest();
        $this->sitepath         = $sitepath;
        $this->configs          = readJSON($sitepath . '/config.json');
        $this->contentDir       = $sitepath . '/content';
        $this->pagesDir         = $this->contentDir . '/pages';
        $this->articlesDir      = $this->contentDir . '/posts';
        $this->themesDir        = $sitepath . '/themes';
        $this->templatesDir     = $this->themesDir . '/' . $this->themeName();
        $this->assetsDir        = $this->templatesDir . '/assets';
        $this->dbpath           = $sitepath . '/db';
        $this->assetsURI        = '/assets';
        if($this->request->isAsset($this)){return;}
        $this->initParsers();
        $this->request->resolve($this);
    
    }

    public function themeName()
    {
        return $this->configs['theme'];
    }
    public function saveToFS($payload){
        list($schema, $jsondata) = $payload; 
        $response = [
            'cmd' => 0, // special attribute to notify reciever to execute a service
            'status' => true,
        ];

        $useSchema =  $schema ?: []; // process arrays into separate files
        $uid = $_GET['id'] ?: uniqid();
        $entry_dir = $this->dbpath.'/'.$uid;
        $schema_file = $entry_dir.'/'.'schema.json';
        if(!file_exists($entry_dir)) mkdir($entry_dir,0777,true);
        if($useSchema){
            foreach ($useSchema as $key){
                $isFK = str_ends_with($key, "[]");
                $key = str_replace("[]","", $key);// clean up key
                $data = $jsondata[$key] ?? null;
                $file = $entry_dir.'/'.$key.'.json';
                $response[$key] = file_exists($file); // may notify caller on existing state
                if(!$data) continue;
                if($isFK && array_is_list($data)){
                    update($file, $data); continue; // updates list in contents
                }
                save($file, $data); // updates file contents
            }
            save($schema_file, $schema);
            return $response;
        }
        
        save($entry_dir.'/'.uniqid().'.json', $jsondata);
        return $response;

    }
    public function xsaveToFS($payload){
        list($schema, $jsondata) = $payload; 
        $response = [
            'cmd' => 0, // special attribute to notify reciever to execute a service
            'status' => true,
        ];

        $useSchema =  $schema && $schema['useSchema'] ?: null; // process arrays into separate files
        $table =  ($useSchema ? $schema['table'] : 'entries'); // table
        $pk =  $schema['pk'] ?: ''; // primary key
        $fks =  $schema['fks']; // table
        $uid = ($_GET['id'] ?: $jsondata[$pk]) ?: uniqid();
        $entry_dir = $this->dbpath.'/'.$uid;
        $entry_file = $entry_dir.'/'.$uid.'.json';
        $schema_file = $entry_dir.'/'.'schema.json';
        if(!file_exists($entry_dir)) mkdir($entry_dir,0777,true);
        if($useSchema){
            foreach ($fks as $key){
                $staticArr = str_ends_with($key, "[]");
                $key = str_replace("[]","", $key);
                $data = $jsondata[$key] ?? null;
                $file = $entry_dir.'/'.$key.'.json';
                $response[$key] = file_exists($file); // may notify caller on existing state
                if(!$data || !is_array($data)) continue;
                if(!$staticArr && array_is_list($data)){
                    update($file, $data); continue; // updates list in contents
                }
                save($file, $data); // updates file contents
            }
            save($schema_file, $schema);
            return $response;
        }
        
        save($entry_file, $jsondata);
        // if($schema)save($schema_file, $schema);
        return $response;

    }

    public function queryContent(string $_queryType, bool $all = false){

        $requesturl = $this->request->path;
        $settings = $this->configs;
        $files         = [];
        $collection = [];
        $entries     = [];
        $categories = [];
        $filepath = $this->contentDir .'/'. $_queryType . (!$all ? $requesturl: '');
        if (is_dir($filepath) && file_exists($filepath)) {
            $paginate = (isset($_GET['page'])) ? $_GET['page'] : 1; //get the paginated page number
            $perpage = $settings['default_page_count']; // specify amount of result to display per page
            $files   = new FilesystemIterator(
                $filepath,
                FilesystemIterator::SKIP_DOTS
            );
            $filecount = 0;
            // foreach ($files as $file) {
            //     $filecount[] = $file;
            // }
            // use Limit Iterator for only articles
            if ($_queryType !== 'pages') $files = new LimitIterator($files, (($paginate - 1) * $perpage), $perpage);

            foreach ($files as $entry) {

                $pageurl = str_replace(PAGE_EXT, '', $entry->getFilename());
                $filefolder = dirname($requesturl . '/' . $entry->getFilename());
                $requestname = trim(str_replace(['/', '-'], ' ', $requesturl));
                // $file = $filepath;

                if (in_array($entry, IGNORES) ) continue;
                $filecount++;
                if (pathinfo($entry, PATHINFO_EXTENSION)) {

                    $openfile = file_get_contents($entry->getPathname());
                    $sections = explode("\n---", $openfile); // separate meta from content
                    $fileheader = explode("\n", str_replace('http:', '', $sections[0])); // separate the metadata from content
                    $metadata = [];
                    foreach ($fileheader as $line) {
                        $pairs = explode(":", $line); //separation of key : value pairs
                        $key = trim($pairs[0]); // sets the key
                        $value = trim($pairs[1]); // sets the value
                        $metadata[$key] = $value; //builds the key :value array based on meta attributes
                    }

                    $entries[] = [
                        'title'     => $metadata['title'],
                        'date'     => (isset($metadata['date'])) ? $metadata['date'] : '',
                        'url'    => '/' . $pageurl,
                        'menu'    => isset($metadata['isparent']) ? $metadata['isparent'] : '',
                        'category' => ($filefolder == '/') ? null : explode('/', ltrim($filefolder, '/')),
                    ];
                    $entries = array_map('array_filter', $entries); // removes any empty array elements.



                } else {
                    $categories[] = [
                        'url'      =>  $requesturl . '/' . $entry->getFilename(),
                        'name'     =>  $entry->getFilename(),
                    ];
                }
            } // end foreach pages

            // lets sort all entries by date value
            if (!function_exists('compareOrder')) {
                function compareOrder($a, $b) {
                    if (isset($a['date']) && isset($b['date'])) {
                        return $a['date'] < $b['date'] ? -1 : 1;
                    } else {
                        return 0;
                    }
                }
            }
            usort($entries, 'compareOrder');

            $templateKey = $requestname == '' ? 'pages' : 'subcategories'; // set api key names for either pages or folders

            $collection = empty($categories) ?
                ['name' => $requestname, 'template' => $templateKey, 'count' => $filecount, 'entries' => $entries] : //show this if no categories are available
                ['name' => $requestname, 'template' => 'categories', 'list' => $categories];

            foreach ($collection as $key => $value) if (empty($value)) unset($collection[$key]);

            return $collection;
        } else {
            if (file_exists($filepath . PAGE_EXT)) {
                $collection = file_get_contents($filepath . PAGE_EXT);
                $sections = explode("\n---", $collection); // separate meta from content
                $fileheader = explode("\n", str_replace('http:', '', $sections[0])); // create meta array
                $filecontents = $sections[1]; //store page content area
                $filedata = [];

                foreach ($fileheader as $line) {
                    $pairs = explode(":", $line); //separation of key : value pairs
                    $key = $pairs[0]; // sets the key
                    $value = trim($pairs[1]); // sets the value
                    $filedata[$key] = $value; //builds the key :value array based on meta attributes
                }


                $filedata["template"] = isset($filedata["template"]) ? $filedata["template"] : 'articles';
                $filedata["path"] = $filepath.PAGE_EXT;
                $filedata["content"] = $this->formatContent($filecontents);
                $collection = $filedata;
                return $collection;
            }else{
                $collection = [
                    'template'=> '404'
                ];
                return $collection;
            }
        }
    } // end queryContent

    public function getContext(){
        $pages = $this->queryContent('pages',1);
        // $posts = $this->queryContent('posts',1);
        $reqctx = $this->queryContent('pages');
        // debug($pages);
        $ctx = [
            'page' => $reqctx,
            'configs' => $this->configs,
            'navigation' => $pages['entries'],
        ];
        return [$reqctx['template'], $ctx];
    }

    public function renderHtml(String $template, Array $context){
        // $this->request->setHeader(HyperRequest::TEXTRESPONSE);
        echo $this->templateParser->render($template.'.html',$context);
    }

    public function formatContent($markdownstring) {
        // $content = htmlspecialchars($markdownstring);
        $content = $this->contentParser->text(trim($markdownstring));
        return $content;
        // return trim(str_replace(array("\r", "\n", "\r\n"), "", $content));
    }

    public function initParsers(){
        $loader = new \Twig\Loader\FilesystemLoader($this->templatesDir);
        $twig = new \Twig\Environment($loader, [
            'auto_escape' => true,
        ]);

        $twig->addFunction(new \Twig\TwigFunction('redirectTo', 'redirectTo'));
        $twig->addFunction(new \Twig\TwigFunction('destroySession', 'destroySession'));

        $this->contentParser    = new ParsedownExtra();
        $this->templateParser = $twig;
    }

    public function __debugInfo() {
        $reflection = new ReflectionClass($this);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED);

        $filteredProperties = [];
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $filteredProperties[$propertyName] = $this->$propertyName;
        }
        return $filteredProperties;
    }
}