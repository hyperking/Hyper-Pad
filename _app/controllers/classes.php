<?php
class loadTime{
    private $time_start     =   0;
    private $time_end       =   0;
    private $time           =   0;
    public function __construct(){
        $this->time_start= microtime(true);
    }
    public function __destruct(){
        $this->time_end = microtime(true);
        $this->time = $this->time_end - $this->time_start;
        echo "<h1>Loaded in ".number_format($this->time, 3) ." seconds</h1>";
    }
}

/**
* @return query
*/
class Query
{

public function queryContent ($_query) {
	global $ignore, $requesturl, $parsedown, $settings;
			$files 		= [];
			$collection = [];
			$entries 	= [];
			$categories = [];
			$filepath = CONTENTDIR.$_query.$requesturl;

if ( is_dir($filepath) && file_exists($filepath) ) {
        $paginate = ( isset($_GET['page']) )?$_GET['page']:1;//get the paginated page number
		$perpage = $settings['default_page_count']; // specify amount of result to display per page
        $files   = new FilesystemIterator($filepath,
        			 FilesystemIterator::SKIP_DOTS);
        $filecount = [];
        foreach ($files as $file) {
        	$filecount[] =$file; 
        }
		// use Limit Iterator for only articles
		if ( $_query !== 'pages') $files = new LimitIterator($files, (($paginate-1)* $perpage), $perpage);



        foreach($files as $entry) {

			$pageurl = str_replace(PAGE_EXT,'',$entry->getFilename());
			$filefolder = dirname($requesturl.'/'.$entry->getFilename());
			$requestname = trim(str_replace(['/','-'], ' ', $requesturl));
			$file = $filepath;

            if ( in_array($entry, $ignore) || substr_count($entry, '_')) continue;
              if (pathinfo($entry, PATHINFO_EXTENSION))
              {

				$openfile = file_get_contents($entry->getPathname());
				$sections = explode("\n---", $openfile);// separate meta from content
				$fileheader = explode("\n", str_replace('http:', '', $sections[0])); // separate the metadata from content
				$metadata = [];
							foreach ($fileheader as $line) 
							{
						    $pairs = explode(":", $line); //separation of key : value pairs
				            $key = trim($pairs[0]);// sets the key
				            $value = trim($pairs[1]); // sets the value
				            $metadata[$key] = $value; //builds the key :value array based on meta attributes
							}

             	$entries[] = [ 'title' 	=> $metadata['title'],
              				   'date' 	=> (isset($metadata['date'])) ? $metadata['date']:'',
              				   'url'	=> $requesturl.'/'.$pageurl,
              				   'menu'	=> isset($metadata['isparent']) ? $metadata['isparent']:'',
              				   'category'=> ($filefolder == '/')? null: explode('/',ltrim($filefolder,'/')),
              				      ];
              	$entries = array_map('array_filter', $entries);// removes any empty array elements.

              
             
              }else{
             	$categories[] = [ 'url'      =>  $requesturl.'/'.$entry->getFilename(),
              				      'name'     =>  $entry->getFilename(),
              				      ];	    
              }

        	}// end foreach pages

        	// lets sort all entries by date value
            if( !function_exists('compareOrder')){
            	function compareOrder($a, $b)
						{
						if( isset($a['date']) && isset($b['date'])){
						  	return $a['date'] < $b['date'];
						}else{
							return false;
						}
			}
			}
            usort($entries, 'compareOrder');

			$templateKey = $requestname == '' ? 'pages' :'subcategories'; // set api key names for either pages or folders
			
			$collection = empty($categories) ? 
			['name'=>$requestname,'template'=>$templateKey,'count'=>count($filecount),'entries'=> $entries ]: //show this if no categories are available
			['name'=>$requestname,'template'=>'categories','list'=>$categories];

			foreach ($collection as $key => $value)if(empty($value))unset($collection[$key]);
 
			return $collection;
}else{
		if( file_exists($filepath.PAGE_EXT) ){
				$collection = file_get_contents($filepath.PAGE_EXT);
				$sections = explode("\n---", $collection);// separate meta from content
				$fileheader = explode("\n", str_replace('http:', '', $sections[0])); // create meta array
				$filecontents = $sections[1]; //store page content area
				$filedata = [];

		foreach ($fileheader as $line) 
				{
			    $pairs = explode(":", $line); //separation of key : value pairs
	            $key = $pairs[0];// sets the key
	            $value = trim($pairs[1]); // sets the value
	            $filedata[$key] = $value; //builds the key :value array based on meta attributes
				}


				$filedata["template"] = isset($filedata["template"]) ? $filedata["template"] :'articles';
				// $filedata["path"] = $filepath;
				$filedata["content"] = formatContent($filecontents);
				$collection = $filedata;
				return $collection;
		}

	
}

}// end queryContent


}// end query Class
