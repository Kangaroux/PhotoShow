<?php
/**
 * This file implements the class ImagePanel.
 * 
 * PHP versions 4 and 5
 *
 * LICENSE:
 * 
 * This file is part of PhotoShow.
 *
 * PhotoShow is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PhotoShow is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PhotoShow.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category  Website
 * @package   Photoshow
 * @author    Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @copyright 2011 Thibaud Rohmer
 * @license   http://www.gnu.org/licenses/
 * @link      http://github.com/thibaud-rohmer/PhotoShow
 */

/**
 * ImagePanel
 *
 * The ImagePanel contains one image and the navigation buttons.
 *
 * @category  Website
 * @package   Photoshow
 * @author    Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @copyright Thibaud Rohmer
 * @license   http://www.gnu.org/licenses/
 * @link      http://github.com/thibaud-rohmer/PhotoShow
 */

class ImagePanel implements HTMLObject
{

    /// Header of the html page
    public $page_header;
	
	/// Image object
	private $image;
	
	/// Video object
	private $video;
	
	/// Description object
	private $description;

	/// Judge object
	private $judge;

	/**
	 * Create ImagePanel
	 *
	 * @param string $file 
	 * @author Thibaud Rohmer
	 */
	public function __construct($file=NULL){
		
		if(!isset($file)){
			return;
		}

        $file_type = File::Type($file);

        if($file_type == "Image"){
            /// Create Image object
            $this->image	=	new Image($file);
        }
        elseif($file_type == "Video"){
            /// Create Video object
            $this->video	=	new Video($file);		
        }		
		
		if(!Settings::$nodescription){
			/// Create Description object
			$this->description = new Description($file);
		}

		/// Create Image object
		$this->imagebar	=	new ImageBar($file);

        $pageURL = Settings::$site_address."/?f=".urlencode(File::a2r($file));
        
        // generate the header - opengraph metatags for facebook
        $this->page_header = "<meta property=\"og:url\" content=\"".$pageURL."\"/>\n"
            ."<meta property=\"og:site_name\" content=\"".Settings::$name."\"/>\n"
            ."<meta property=\"og:type\" content=\"website\"/>\n"
            ."<meta property=\"og:title\" content=\"".Settings::$name.": ".File::a2r($file)."\"/>\n"
            ."<meta property=\"og:image\" content=\"".Settings::$site_address."/?t=Thb&f=".urlencode(File::a2r($file))."\"/>\n";
        if (Settings::$fbappid){
            $this->page_header .= "<meta property=\"fb:app_id\" content=\"".Settings::$fbappid."\"/>\n";
        }

		/// Set the Judge
		$this->judge 	=	new Judge($file);
	}

	/**
	 * Display ImagePanel on website
	 *
	 * @return void
	 * @author Thibaud Rohmer
	 */
	public function toHTML(){
        if (!isset($this->image) && !isset($this->video)){
            return;
        }
		
		echo "<div id='image_panel_table'>\n";

		if(isset($this->image)){
			echo "<div id='bigimage' style='position:relative;'>\n";
			echo "<div onclick='next_image()' style='cursor:pointer;position: absolute;top: 0;left: 0;width: 50%;height: 100%;'></div>\n";
			echo "<div onclick='prev_image()' style='cursor:pointer;position: absolute;top: 0;left: 50%;width: 50%;height: 100%;'></div>\n";

			$this->image->toHTML();
			echo "</div>\n";
		}
        elseif(isset($this->video)){

			echo "<div id='bigvideo'>\n";
			$this->video->toHTML();
			echo "</div>\n";
		}		

		if(!Settings::$nodescription){
			echo "<div class='description'>";
			$this->description->toHTML();
			echo "</div>";
		}

		echo "<div id='image_bar'>\n";
		$this->imagebar->toHTML();
		echo "</div>\n";

		echo "</div>\n";
	}
	
}
?>
