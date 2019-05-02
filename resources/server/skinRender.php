<?php
    require_once('libraries.php');
    /****** MINECRAFT 3D Skin Generator *****
     * The contents of this project were first developed by Pierre Gros on 17th April 2012.
     * It has once been modified by Carlos Ferreira (http://www.carlosferreira.me) on 31st May 2014.
     * Translations done by Carlos Ferreira.
     * Later adapted by Gijs "Gyzie" Oortgiese (http://www.gijsoortgiese.com/). Started on the 6st of July 2014.
     * Fixing various issues.
     * Later changed by ITZVGcGPmO (https://github.com/ITZVGcGPmO) April 2019.
     * Adapted for SkinSystem.
     *
     **** GET Parameters ****
     * user - A username for the skin to be rendered. (Required w/o "mojang")
     * mojang - A texture from https://textures.minecraft.net to render. (Required w/o "user")
     * vr - Vertical Rotation. (-25 by default)
     * hr - Horizontal Rotation. (35 by default)
     *
     * hrh - Horizontal Rotation of the Head. (0 by default)
     *
     * vrll - Vertical Rotation of the Left Leg. (0 by default)
     * vrrl - Vertical Rotation of the Right Leg. (0 by default)
     * vrla - Vertical Rotation of the Left Arm. (0 by default)
     * vrra - Vertical Rotation of the Right Arm. (0 by default)
     *
     * displayHair - Either or not to display hairs. Set to "false" to NOT display hairs. (true by default)
     * headOnly - Either or not to display the ONLY the head. Set to "true" to display ONLY the head. (false by default)
     *
     * format - The format in which the image is to be rendered. "png", "svg", "base64", "raw". (png by default)
     *
     * ratio - The size of the "png" image. The default and minimum value is 2. (12 by default)
     * 
     * aa - Anti-aliasing (Not real AA, fake AA). When set to "true" the image will be smoother. (false by default).
     * 
     * layers - Apply extra skin layers. (true by default)
     */
    
    if( basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"]) ) {
        // Don't adjust the error reporting if we are an include file
        error_reporting(E_ERROR);
        //error_reporting(E_ALL);
        //ini_set("display_errors", 1); // TODO not here - this is set in index.php
    }

    /* Start Global variabal
     * These variabals are shared over multiple classes
     */
    $seconds_to_cache = $config['cache_for_days']*24*60*60; // Cache duration sent to the browser.
    
    // Cosine and Sine values
    $cos_alpha = null;
    $sin_alpha = null;
    $cos_omega = null;
    $sin_omega = null;
    
    $minX = null;
    $maxX = null;
    $minY = null;
    $maxY = null;
    /* End Global variabel */
    
    /* Function converts the old _GET names to
     * the new names. This makes it still compatable
     * with scrips using the old names.
     * 
     * Espects the English _GET name.
     * Returns the _GET value or the default value.
     * Return false if the _GET is not found.
     */
    function grabGetValue($name) {
        $parameters = array('mojang' => array('old' => 'mojang', 'default' => false),
                            'user' => array('old' => 'login', 'default' => false),
                            'vr' => array('old' => 'a', 'default' => '-25'),
                            'hr' => array('old' => 'w', 'default' => '35'),
                            'hrh' => array('old' => 'wt', 'default' => '0'),
                            'vrll' => array('old' => 'ajg', 'default' => '0'),
                            'vrrl' => array('old' => 'ajd', 'default' => '0'),
                            'vrla' => array('old' => 'abg', 'default' => '0'),
                            'vrra' => array('old' => 'abd', 'default' => '0'),
                            'displayHair' => array('old' => 'displayHairs', 'default' => 'true'),
                            'headOnly' => array('old' => 'headOnly', 'default' => 'false'),
                            'format' => array('old' => 'format', 'default' => 'png'),
                            'ratio' => array('old' => 'ratio', 'default' => '12'),
                            'aa' => array('old' => 'aa', 'default' => 'false'),
                            'layers' => array('old' => 'layers', 'default' => 'true')
                            );
        
        if(array_key_exists($name, $parameters)) {
            if(isset($_GET[$name])) {
                return $_GET[$name];
            } else if (isset($_GET[$parameters[$name]['old']])) {
                return $_GET[$parameters[$name]['old']];
            }
            return $parameters[$name]['default'];
        }
        
        return false;
    }
    
    // Check if the player name value has been set, and that we are not running as an included/required file. else do nothing.
    if(( basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"]) ) && ((grabGetValue('user') !== false) || (grabGetValue('mojang') !== false))) {
        // There is a player name so they want an image output via url
        $renderparams = array(grabGetValue('mojang'),
                            grabGetValue('user'),
                            grabGetValue('vr'),
                            grabGetValue('hr'),
                            grabGetValue('hrh'),
                            grabGetValue('vrll'),
                            grabGetValue('vrrl'),
                            grabGetValue('vrla'),
                            grabGetValue('vrra'),
                            grabGetValue('displayHair'),
                            grabGetValue('headOnly'),
                            grabGetValue('format'),
                            grabGetValue('ratio'),
                            grabGetValue('aa'),
                            grabGetValue('layers'));
        $player = new render3DPlayer(...$renderparams);
        $res = $player->getSkinFile();
        $skinRaw = $res[0]; $skinHash = $res[1];
        if ($_GET['dl'] == 'true') {header('Content-Disposition: attachment; filename='.
            grabGetValue('user').'-'.hash('adler32', serialize($renderparams).$skinRaw).'.png');}
        $conttype = ['svg'=>'image/svg+xml','base64'=>'text/plain','png'=>'image/png','raw'=>'image/png'];
        header('Content-Type: '.$conttype[grabGetValue('format')]); // send content-type for any format
        if(grabGetValue('format') == 'raw'){ echo file_get_contents($skinRaw); }
        else { // cache system for "rendered" skins
            unset($renderparams[0]); unset($renderparams[1]);
            $cdir = __DIR__.'/../../'.$config['cache_dir']; mkdir($cdir, 0775, true);
            $cfile = $cdir.$skinHash.'-'.hash("md5", serialize($renderparams));
            if (!is_file($cfile) or file_get_contents($cfile) == '') { $player->get3DRender($skinRaw, $cfile); }
            echo file_get_contents($cfile);
            touch($cfile); cacheClean(__DIR__.'/../../');
        }
    } else {
        header('Content-Type: text/plain');
        echo file_get_contents(__FILE__);
    }
    
    /* Render3DPlayer class
     *
     */
    class render3DPlayer { 
        // Use a fallback skin whenever something goes wrong.
        private $fallback_img = 'https://textures.minecraft.net/texture/dc1c77ce8e54925ab58125446ec53b0cdd3d0ca3db273eb908d5482787ef4016';
        private $mojang = null;
        private $playerName = null;
        private $playerSkin = false;
        private $isNewSkinType = false;
        
        private $hd_ratio = 1;
        
        private $vR = null;
        private $hR = null;
        private $hrh = null;
        private $vrll = null;
        private $vrrl = null;
        private $vrla = null;
        private $vrra = null;
        private $head_only = null;
        private $display_hair = null;
        private $format = null;
        private $ratio = null;
        private $aa = null;
        private $layers = null;
        
        // Rotation variables in radians (3D Rendering)
        private $alpha = null; // Vertical rotation on the X axis.
        private $omega = null; // Horizontal rotation on the Y axis.
        
        private $members_angles = array(); // Head, Helmet, Torso, Arms, Legs
        
        private $visible_faces_format = null;
        private $visible_faces = null;
        private $all_faces = null;
        
        private $front_faces = null;
        private $back_faces = null;
        
        private $cube_points = null;
        
        private $polygons = null;
        
        private $times = null;
        
        public function __construct($mojang, $user, $vr, $hr, $hrh, $vrll, $vrrl, $vrla, $vrra, $displayHair, $headOnly, $format, $ratio, $aa, $layers) {
            $this->mojang = $mojang;
            $this->playerName = $user;
            $this->vR = $vr;
            $this->hR = $hr;
            $this->hrh = $hrh;
            $this->vrll = $vrll;
            $this->vrrl = $vrrl;
            $this->vrla = $vrla;
            $this->vrra = $vrra;
            $this->head_only = ($headOnly == 'true');
            $this->display_hair = ($displayHair != 'false');
            $this->format = $format;
            $this->ratio = $ratio;
            $this->aa = ($aa == 'true');
            $this->layers = ($layers == 'true');
        }
        
        /* Function can be used for tracking script duration
         *
         */
        private function microtime_float() {
            list($usec, $sec) = explode(" ", microtime());
            return ((float) $usec + (float) $sec);
        }

        /* Fetches skin URL from database using the given name
         *
         * Expects a name
         * returns a player skin file
         */
        public function getSkinFile() {
            global $config;
            if ($this->mojang!=null) {
                preg_match('/[^\/]+$/', $this->mojang, $mj256);
                $skinURL = 'https://textures.minecraft.net/texture/'.$mj256[0];}
            else if ($this->playerName!=null) { // get existing skin from skinsrestorer.
                $skinName = query('sr', 'SELECT Skin FROM Players WHERE Nick = ?', [$this->playerName])->fetch(PDO::FETCH_ASSOC)['Skin'];
                $skinLookup = query('sr', 'SELECT Value FROM Skins WHERE Nick = ?', [$skinName])->fetch(PDO::FETCH_ASSOC)['Value'];
                $skinURL = json_decode(base64_decode($skinLookup), true)['textures']['SKIN']['url'];
                if (!is_string($skinURL)) { // if cannot get skin from skinsrestorer, get skin from mojang.
                    $mjdfskfl = __DIR__.'/../../'.$config['cache_dir'].'mojang_skin-'.strtolower($this->playerName);
                    if (file_exists($mjdfskfl)) { // fetch cached mojang skin
                        $skinURL = file_get_contents($mjdfskfl);
                    } else { // fetch mojang version of skin
                        $pf = json_decode(file_get_contents('https://api.mojang.com/users/profiles/minecraft/'.strtolower($this->playerName)), true);
                        if(is_array($pf) and array_key_exists("id", $pf)) { 
                            $ca = json_decode(file_get_contents('https://sessionserver.mojang.com/session/minecraft/profile/'.$pf["id"]), true);}
                        // $ca = json_decode(file_get_contents(cacheGrab('https://sessionserver.mojang.com/session/minecraft/profile/ef5db304c36841089a352fd0a072b73d', 'test', __DIR__.'/../../')));
                        if(is_array($ca) and array_key_exists("properties", $ca)) { 
                            foreach($ca["properties"] as $element) {
                                if(array_key_exists("name", $element) && $element["name"] == "textures") {
                                    $content = base64_decode($element["value"]);
                                    $skinArray = json_decode($content, true);
                                    if(array_key_exists("textures", $skinArray) and array_key_exists("SKIN", $skinArray["textures"])){
                                        file_put_contents($mjdfskfl, $skinArray["textures"]["SKIN"]["url"]);
                                        $skinURL = $skinArray["textures"]["SKIN"]["url"];}}}}}}}
            if (strlen($skinURL) < 1) { $skinURL = $this->fallback_img; } // if can't get skin, use steve.
            if (!$mj256) { preg_match('/[^\/]+$/', $skinURL, $mj256); }
            return [cacheGrab($skinURL, $mj256[0], __DIR__.'/../../', false, ['sha256', $mj256[0]]), $mj256[0]];
        }

        /* Function renders the 3d image
         *
         */
        public function get3DRender($skinRaw, $cacheFile) {
            global $minX, $maxX, $minY, $maxY;
            // Download and check the player skin
            $this->playerSkin = @imageCreateFromPng($skinRaw);
            if ((!$this->playerSkin) || (imagesy($this->playerSkin) % 32 != 0)) {
                // Player skin does not exist or bad ratio created
                $this->playerSkin = imageCreateFromPng($this->fallback_img);
            }
            
            $this->hd_ratio = imagesx($this->playerSkin) / 64; // Set HD ratio to 2 if the skin is 128x64. Check via width, not height because of new skin type.
            
            // check if new skin type. If both sides are equaly long: new skin type
            if(imagesx($this->playerSkin) == imagesy($this->playerSkin)) {
                $this->isNewSkinType = true;
            }
            
            $this->playerSkin = img::convertToTrueColor($this->playerSkin); // Convert the image to true color if not a true color image
                $this->times[] = array('Convert-to-true-color-if-needed', $this->microtime_float());
            $this->makeBackgroundTransparent(); // make background transparent (fix for weird rendering skins)
                $this->times[] = array('Made-Background-Transparent', $this->microtime_float());
            
            // Quick fix for 1.8:
            // Copy the extra layers ontop of the base layers
            if($this->layers) {
                $this->fixNewSkinTypeLayers();
            }
            
            $this->calculateAngles();
                $this->times[] = array('Angle-Calculations', $this->microtime_float());
            $this->facesDetermination();
                $this->times[] = array('Determination-of-faces', $this->microtime_float());
            $this->generatePolygons();
                $this->times[] = array('Polygon-generation', $this->microtime_float());
            $this->memberRotation();
                $this->times[] = array('Members-rotation', $this->microtime_float());
            $this->createProjectionPlan();
                $this->times[] = array('Projection-plan', $this->microtime_float());
            $result = $this->displayImage($output);
                $this->times[] = array('Display-image', $this->microtime_float());
            switch($this->format) {
                case 'svg':
                    file_put_contents($cacheFile, '<?xml version="1.0" standalone="no"?>
                        <!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN"
                        "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">' . $result . "\n");
                    break;
                case 'base64':
                    echo file_put_contents($cacheFile, $result);
                    break;
                case 'png':
                default:
                    imagepng($result, $cacheFile);
                    imagedestroy($result);
                    break;
            }
        }
        
        /* Function fixes issues with images that have a solid background
         * 
         * Espects an tru color image.
         */
        private function makeBackgroundTransparent() {
            // check if the corner box is one solid color
            $tempValue = null;
            $needRemove = true;
            for ($iH = 0; $iH < 8; $iH++) {
                for ($iV = 0; $iV < 8; $iV++) {
                    $pixelColor = imagecolorat($this->playerSkin, $iH, $iV);
                    $indexColor = imagecolorsforindex($this->playerSkin, $pixelColor);
                    if($indexColor['alpha'] > 120) {
                        // the image contains transparancy, noting to do
                        $needRemove = false;
                    }
                    
                    if($tempValue === null) {
                        $tempValue = $pixelColor;
                    } else if ($tempValue != $pixelColor){
                        // Cannot determine a background color, file is probably fine
                        $needRemove = false;
                    }
                }
            }
            
            $imgX = imagesx($this->playerSkin);
            $imgY = imagesy($this->playerSkin);
            
            $dst = img::createEmptyCanvas($imgX, $imgY);
            
            imagesavealpha($this->playerSkin, false);
            
            if($needRemove) {
                // the entire block is one solid color. Use this color to clear the background.
                $r = ($tempValue >> 16) & 0xFF;
                $g = ($tempValue >> 8) & 0xFF;
                $b = $tempValue & 0xFF;
                
                    
                //imagealphablending($dst, true);
                $transparant = imagecolorallocate($this->playerSkin, $r, $g, $b);
                imagecolortransparent($this->playerSkin, $transparant);
                
                // create fill
                $color = imagecolorallocate($dst, $r, $g, $b);
            } else {
                // create fill
                $color = imagecolorallocate($dst, 0, 0, 0);
            }
            
            // fill the areas that should not be transparant        
            $positionMultiply = $imgX / 64;
            
            // head
            imagefilledrectangle($dst, 8*$positionMultiply, 0*$positionMultiply, 23*$positionMultiply, 7*$positionMultiply, $color);
            imagefilledrectangle($dst, 0*$positionMultiply, 8*$positionMultiply, 31*$positionMultiply, 15*$positionMultiply, $color);
            
            // right leg, body, right arm
            imagefilledrectangle($dst, 4*$positionMultiply, 16*$positionMultiply, 11*$positionMultiply, 19*$positionMultiply, $color);
            imagefilledrectangle($dst, 20*$positionMultiply, 16*$positionMultiply, 35*$positionMultiply, 19*$positionMultiply, $color);
            imagefilledrectangle($dst, 44*$positionMultiply, 16*$positionMultiply, 51*$positionMultiply, 19*$positionMultiply, $color);
            imagefilledrectangle($dst, 0*$positionMultiply, 20*$positionMultiply, 54*$positionMultiply, 31*$positionMultiply, $color);
            
            // left leg, left arm
            imagefilledrectangle($dst, 20*$positionMultiply, 48*$positionMultiply, 27*$positionMultiply, 51*$positionMultiply, $color);
            imagefilledrectangle($dst, 36*$positionMultiply, 48*$positionMultiply, 43*$positionMultiply, 51*$positionMultiply, $color);
            imagefilledrectangle($dst, 16*$positionMultiply, 52*$positionMultiply, 47*$positionMultiply, 63*$positionMultiply, $color);
            
            imagecopy($dst, $this->playerSkin, 0, 0, 0, 0, $imgX, $imgY);
            
            $this->playerSkin = $dst;
            return;
        }
        
        /* Function converts a 1.8 skin (which is not supported by
         * the script) to the old skin format.
         * 
         * Espects an image.
         * Returns a croped image.
         */
        private function cropToOldSkinFormat() {
            if(imagesx($this->playerSkin) !== imagesy($this->playerSkin)) {
                return $this->playerSkin;
            }

            $newWidth = imagesx($this->playerSkin);
            $newHeight = $newWidth / 2;
            
            $newImgPng = img::createEmptyCanvas($newWidth, $newHeight);
            
            imagecopy($newImgPng, $this->playerSkin, 0, 0, 0, 0, $newWidth, $newHeight);
            
            $this->playerSkin = $newImgPng;
        }
        
        /* Function copys the extra layers of a 1.8 skin
         * onto the base layers so that it will still show. QUICK FIX, NEEDS BETTER FIX
         * 
         * Espects an image.
         * Returns a croped image.
         */
        private function fixNewSkinTypeLayers() {
            if(!$this->isNewSkinType) {
                return;
            }
            
            imagecopy($this->playerSkin, $this->playerSkin, 0, 16, 0, 32, 56, 16); // RL2, BODY2, RA2
            imagecopy($this->playerSkin, $this->playerSkin, 16, 48, 0, 48, 16, 16); // LL2
            imagecopy($this->playerSkin, $this->playerSkin, 32, 48, 48, 48, 16, 16); // LA2
        }
        
        /* Function Calculates the angels
         *
         */
        private function calculateAngles() {
            global $cos_alpha, $sin_alpha, $cos_omega, $sin_omega;
            global $minX, $maxX, $minY, $maxY;
            
            // Rotation variables in radians (3D Rendering)
            $this->alpha = deg2rad($this->vR); // Vertical rotation on the X axis.
            $this->omega = deg2rad($this->hR); // Horizontal rotation on the Y axis.
            
            // Cosine and Sine values
            $cos_alpha = cos($this->alpha);
            $sin_alpha = sin($this->alpha);
            $cos_omega = cos($this->omega);
            $sin_omega = sin($this->omega);
            
            $this->members_angles['torso'] = array(
                                                'cos_alpha' => cos(0),
                                                'sin_alpha' => sin(0),
                                                'cos_omega' => cos(0),
                                                'sin_omega' => sin(0) 
                                                );
            
            $alpha_head = 0;
            $omega_head = deg2rad($this->hrh);
            $this->members_angles['head'] = $this->members_angles['helmet'] = array( // Head and helmet get the same calculations
                                                                                    'cos_alpha' => cos($alpha_head),
                                                                                    'sin_alpha' => sin($alpha_head),
                                                                                    'cos_omega' => cos($omega_head),
                                                                                    'sin_omega' => sin($omega_head) 
                                                                                    );
            
            $alpha_right_arm = deg2rad($this->vrra);
            $omega_right_arm = 0;
            $this->members_angles['rightArm'] = array(
                                                    'cos_alpha' => cos($alpha_right_arm),
                                                    'sin_alpha' => sin($alpha_right_arm),
                                                    'cos_omega' => cos($omega_right_arm),
                                                    'sin_omega' => sin($omega_right_arm) 
                                                    );  
            
            $alpha_left_arm = deg2rad($this->vrla);
            $omega_left_arm = 0;
            $this->members_angles['leftArm'] = array(
                                                    'cos_alpha' => cos($alpha_left_arm),
                                                    'sin_alpha' => sin($alpha_left_arm),
                                                    'cos_omega' => cos($omega_left_arm),
                                                    'sin_omega' => sin($omega_left_arm) 
                                                    );
            
            $alpha_right_leg = deg2rad($this->vrrl);
            $omega_right_leg = 0;
            $this->members_angles['rightLeg'] = array(
                                                    'cos_alpha' => cos($alpha_right_leg),
                                                    'sin_alpha' => sin($alpha_right_leg),
                                                    'cos_omega' => cos($omega_right_leg),
                                                    'sin_omega' => sin($omega_right_leg) 
                                                    );
                                                    
            $alpha_left_leg = deg2rad($this->vrll);
            $omega_left_leg = 0;
            $this->members_angles['leftLeg'] = array(
                                                    'cos_alpha' => cos($alpha_left_leg),
                                                    'sin_alpha' => sin($alpha_left_leg),
                                                    'cos_omega' => cos($omega_left_leg),
                                                    'sin_omega' => sin($omega_left_leg) 
                                                    );
            $minX = 0;
            $maxX = 0;
            $minY = 0;
            $maxY = 0;
        }
        
        /* Function determinates faces
         *
         */
        private function facesDetermination() {
            $this->visible_faces_format = array(
                                        'front' => array(),
                                        'back' => array ()
                                        );
                                    
            $this->visible_faces = array(
                                        'head' => $this->visible_faces_format,
                                        'torso' => $this->visible_faces_format,
                                        'rightArm' => $this->visible_faces_format,
                                        'leftArm' => $this->visible_faces_format,
                                        'rightLeg' => $this->visible_faces_format,
                                        'leftLeg' => $this->visible_faces_format 
                                        );
            
            $this->all_faces = array(
                                    'back',
                                    'right',
                                    'top',
                                    'front',
                                    'left',
                                    'bottom' 
                                    );
            
            // Loop each preProject and Project then calculate the visible faces for each - also display
            foreach ($this->visible_faces as $k => &$v) {
                unset($cube_max_depth_faces, $this->cube_points);
                
                $this->setCubePoints();
                
                foreach ($this->cube_points as $cube_point) {
                    $cube_point[0]->preProject(0, 0, 0,
                                                $this->members_angles[$k]['cos_alpha'],
                                                $this->members_angles[$k]['sin_alpha'],
                                                $this->members_angles[$k]['cos_omega'],
                                                $this->members_angles[$k]['sin_omega']);
                    $cube_point[0]->project();
                    
                    if (!isset($cube_max_depth_faces)) {
                        $cube_max_depth_faces = $cube_point;
                    } else if ($cube_max_depth_faces[0]->getDepth() > $cube_point[0]->getDepth()) {
                        $cube_max_depth_faces = $cube_point;
                    }
                }
                
                $v['back'] = $cube_max_depth_faces[1];
                $v['front'] = array_diff($this->all_faces, $v['back']);
            }
            
            $this->setCubePoints();
            
            unset($cube_max_depth_faces);
            foreach ($this->cube_points as $cube_point) {
                $cube_point[0]->project();
                
                if (!isset($cube_max_depth_faces)) {
                    $cube_max_depth_faces = $cube_point;
                } else if ($cube_max_depth_faces[0]->getDepth() > $cube_point[ 0 ]->getDepth()) {
                    $cube_max_depth_faces = $cube_point;
                }
                
                $this->back_faces = $cube_max_depth_faces[ 1 ];
                $this->front_faces = array_diff($this->all_faces, $this->back_faces );
            }
        }
        
        /* Function sets all cube points
         *
         */
        private function setCubePoints() {
            $this->cube_points = array();
            $this->cube_points[] = array(
                                        new Point(array(
                                                        'x' => 0,
                                                        'y' => 0,
                                                        'z' => 0 
                                                        )), array(
                                                                'back',
                                                                'right',
                                                                'top' 
                                                                )); // 0
            
            $this->cube_points[] = array(
                                        new Point(array(
                                                        'x' => 0,
                                                        'y' => 0,
                                                        'z' => 1 
                                                        )), array(
                                                                'front',
                                                                'right',
                                                                'top' 
                                                                )); // 1
            
            $this->cube_points[] = array(
                                        new Point(array(
                                                        'x' => 0,
                                                        'y' => 1,
                                                        'z' => 0 
                                                        )), array(
                                                                'back',
                                                                'right',
                                                                'bottom' 
                                                                )); // 2
            
            $this->cube_points[] = array(
                                        new Point(array(
                                                        'x' => 0,
                                                        'y' => 1,
                                                        'z' => 1 
                                                        )), array(
                                                                'front',
                                                                'right',
                                                                'bottom' 
                                                                )); // 3
                                                                
            $this->cube_points[] = array(
                                        new Point(array(
                                                        'x' => 1,
                                                        'y' => 0,
                                                        'z' => 0 
                                                        )), array(
                                                                'back',
                                                                'left',
                                                                'top' 
                                                                )); // 4

            $this->cube_points[] = array(
                                        new Point(array(
                                                        'x' => 1,
                                                        'y' => 0,
                                                        'z' => 1 
                                                        )), array(
                                                                'front',
                                                                'left',
                                                                'top' 
                                                                )); // 5
                                                                
            $this->cube_points[] = array(
                                        new Point(array(
                                                        'x' => 1,
                                                        'y' => 1,
                                                        'z' => 0 
                                                        )), array(
                                                                'back',
                                                                'left',
                                                                'bottom' 
                                                                )); // 6
                                                                
            $this->cube_points[] = array(
                                        new Point(array(
                                                        'x' => 1,
                                                        'y' => 1,
                                                        'z' => 1 
                                                        )), array(
                                                                'front',
                                                                'left',
                                                                'bottom' 
                                                                )); // 7
        }
        
        /* Function generates polygons
         *
         */
        private function generatePolygons() {
            $depths_of_face = array();
            $this->polygons = array();
            $cube_faces_array = array(  'front' => array(),
                                        'back' => array(),
                                        'top' => array(),
                                        'bottom' => array(),
                                        'right' => array(),
                                        'left' => array ()
                                        );
            
            $this->polygons = array('helmet' => $cube_faces_array,
                                    'head' => $cube_faces_array,
                                    'torso' => $cube_faces_array,
                                    'rightArm' => $cube_faces_array,
                                    'leftArm' => $cube_faces_array,
                                    'rightLeg' => $cube_faces_array,
                                    'leftLeg' => $cube_faces_array 
                                    );
            
            $hd_ratio = $this->hd_ratio;
            $img_png = $this->playerSkin;
            
            // HEAD         
            for ( $i = 0; $i < 9 * $hd_ratio; $i++ ) {
                for ( $j = 0; $j < 9 * $hd_ratio; $j++ ) {
                    if ( !isset( $volume_points[ $i ][ $j ][ -2 * $hd_ratio ] ) ) {
                        $volume_points[ $i ][ $j ][ -2 * $hd_ratio ] = new Point( array(
                            'x' => $i,
                            'y' => $j,
                            'z' => -2 * $hd_ratio 
                        ) );
                    }
                    if ( !isset( $volume_points[ $i ][ $j ][ 6 * $hd_ratio ] ) ) {
                        $volume_points[ $i ][ $j ][ 6 * $hd_ratio ] = new Point( array(
                            'x' => $i,
                            'y' => $j,
                            'z' => 6 * $hd_ratio 
                        ) );
                    }
                }
            }
            for ( $j = 0; $j < 9 * $hd_ratio; $j++ ) {
                for ( $k = -2 * $hd_ratio; $k < 7 * $hd_ratio; $k++ ) {
                    if ( !isset( $volume_points[ 0 ][ $j ][ $k ] ) ) {
                        $volume_points[ 0 ][ $j ][ $k ] = new Point( array(
                             'x' => 0,
                            'y' => $j,
                            'z' => $k 
                        ) );
                    }
                    if ( !isset( $volume_points[ 8 * $hd_ratio ][ $j ][ $k ] ) ) {
                        $volume_points[ 8 * $hd_ratio ][ $j ][ $k ] = new Point( array(
                             'x' => 8 * $hd_ratio,
                            'y' => $j,
                            'z' => $k 
                        ) );
                    }
                }
            }
            for ( $i = 0; $i < 9 * $hd_ratio; $i++ ) {
                for ( $k = -2 * $hd_ratio; $k < 7 * $hd_ratio; $k++ ) {
                    if ( !isset( $volume_points[ $i ][ 0 ][ $k ] ) ) {
                        $volume_points[ $i ][ 0 ][ $k ] = new Point( array(
                             'x' => $i,
                            'y' => 0,
                            'z' => $k 
                        ) );
                    }
                    if ( !isset( $volume_points[ $i ][ 8 * $hd_ratio ][ $k ] ) ) {
                        $volume_points[ $i ][ 8 * $hd_ratio ][ $k ] = new Point( array(
                             'x' => $i,
                            'y' => 8 * $hd_ratio,
                            'z' => $k 
                        ) );
                    }
                }
            }
            for ( $i = 0; $i < 8 * $hd_ratio; $i++ ) {
                for ( $j = 0; $j < 8 * $hd_ratio; $j++ ) {
                    $this->polygons[ 'head' ][ 'back' ][]  = new Polygon( array(
                         $volume_points[ $i ][ $j ][ -2 * $hd_ratio ],
                        $volume_points[ $i + 1 ][ $j ][ -2 * $hd_ratio ],
                        $volume_points[ $i + 1 ][ $j + 1 ][ -2 * $hd_ratio ],
                        $volume_points[ $i ][ $j + 1 ][ -2 * $hd_ratio ] 
                    ), imagecolorat( $img_png, ( 32 * $hd_ratio - 1 ) - $i, 8 * $hd_ratio + $j ) );
                    $this->polygons[ 'head' ][ 'front' ][] = new Polygon( array(
                         $volume_points[ $i ][ $j ][ 6 * $hd_ratio ],
                        $volume_points[ $i + 1 ][ $j ][ 6 * $hd_ratio ],
                        $volume_points[ $i + 1 ][ $j + 1 ][ 6 * $hd_ratio ],
                        $volume_points[ $i ][ $j + 1 ][ 6 * $hd_ratio ] 
                    ), imagecolorat( $img_png, 8 * $hd_ratio + $i, 8 * $hd_ratio + $j ) );
                }
            }
            for ( $j = 0; $j < 8 * $hd_ratio; $j++ ) {
                for ( $k = -2 * $hd_ratio; $k < 6 * $hd_ratio; $k++ ) {
                    $this->polygons[ 'head' ][ 'right' ][] = new Polygon( array(
                         $volume_points[ 0 ][ $j ][ $k ],
                        $volume_points[ 0 ][ $j ][ $k + 1 ],
                        $volume_points[ 0 ][ $j + 1 ][ $k + 1 ],
                        $volume_points[ 0 ][ $j + 1 ][ $k ] 
                    ), imagecolorat( $img_png, $k + 2 * $hd_ratio, 8 * $hd_ratio + $j ) );
                    $this->polygons[ 'head' ][ 'left' ][]  = new Polygon( array(
                         $volume_points[ 8 * $hd_ratio ][ $j ][ $k ],
                        $volume_points[ 8 * $hd_ratio ][ $j ][ $k + 1 ],
                        $volume_points[ 8 * $hd_ratio ][ $j + 1 ][ $k + 1 ],
                        $volume_points[ 8 * $hd_ratio ][ $j + 1 ][ $k ] 
                    ), imagecolorat( $img_png, ( 24 * $hd_ratio - 1 ) - $k - 2 * $hd_ratio, 8 * $hd_ratio + $j ) );
                }
            }
            for ( $i = 0; $i < 8 * $hd_ratio; $i++ ) {
                for ( $k = -2 * $hd_ratio; $k < 6 * $hd_ratio; $k++ ) {
                    $this->polygons[ 'head' ][ 'top' ][]    = new Polygon( array(
                         $volume_points[ $i ][ 0 ][ $k ],
                        $volume_points[ $i + 1 ][ 0 ][ $k ],
                        $volume_points[ $i + 1 ][ 0 ][ $k + 1 ],
                        $volume_points[ $i ][ 0 ][ $k + 1 ] 
                    ), imagecolorat( $img_png, 8 * $hd_ratio + $i, $k + 2 * $hd_ratio ) );
                    $this->polygons[ 'head' ][ 'bottom' ][] = new Polygon( array(
                         $volume_points[ $i ][ 8 * $hd_ratio ][ $k ],
                        $volume_points[ $i + 1 ][ 8 * $hd_ratio ][ $k ],
                        $volume_points[ $i + 1 ][ 8 * $hd_ratio ][ $k + 1 ],
                        $volume_points[ $i ][ 8 * $hd_ratio ][ $k + 1 ] 
                    ), imagecolorat( $img_png, 16 * $hd_ratio + $i, 2 * $hd_ratio + $k ) );
                }
            }
            if ($this->display_hair) {
                // HELMET/HAIR
                $volume_points = array();
                for ( $i = 0; $i < 9 * $hd_ratio; $i++ ) {
                    for ( $j = 0; $j < 9 * $hd_ratio; $j++ ) {
                        if ( !isset( $volume_points[ $i ][ $j ][ -2 * $hd_ratio ] ) ) {
                            $volume_points[ $i ][ $j ][ -2 * $hd_ratio ] = new Point( array(
                                 'x' => $i * 9 / 8 - 0.5 * $hd_ratio,
                                'y' => $j * 9 / 8 - 0.5 * $hd_ratio,
                                'z' => -2.5 * $hd_ratio 
                            ) );
                        }
                        if ( !isset( $volume_points[ $i ][ $j ][ 6 * $hd_ratio ] ) ) {
                            $volume_points[ $i ][ $j ][ 6 * $hd_ratio ] = new Point( array(
                                 'x' => $i * 9 / 8 - 0.5 * $hd_ratio,
                                'y' => $j * 9 / 8 - 0.5 * $hd_ratio,
                                'z' => 6.5 * $hd_ratio 
                            ) );
                        }
                    }
                }
                for ( $j = 0; $j < 9 * $hd_ratio; $j++ ) {
                    for ( $k = -2 * $hd_ratio; $k < 7 * $hd_ratio; $k++ ) {
                        if ( !isset( $volume_points[ 0 ][ $j ][ $k ] ) ) {
                            $volume_points[ 0 ][ $j ][ $k ] = new Point( array(
                                 'x' => -0.5 * $hd_ratio,
                                'y' => $j * 9 / 8 - 0.5 * $hd_ratio,
                                'z' => $k * 9 / 8 - 0.5 * $hd_ratio 
                            ) );
                        }
                        if ( !isset( $volume_points[ 8 * $hd_ratio ][ $j ][ $k ] ) ) {
                            $volume_points[ 8 * $hd_ratio ][ $j ][ $k ] = new Point( array(
                                 'x' => 8.5 * $hd_ratio,
                                'y' => $j * 9 / 8 - 0.5 * $hd_ratio,
                                'z' => $k * 9 / 8 - 0.5 * $hd_ratio 
                            ) );
                        }
                    }
                }
                for ( $i = 0; $i < 9 * $hd_ratio; $i++ ) {
                    for ( $k = -2 * $hd_ratio; $k < 7 * $hd_ratio; $k++ ) {
                        if ( !isset( $volume_points[ $i ][ 0 ][ $k ] ) ) {
                            $volume_points[ $i ][ 0 ][ $k ] = new Point( array(
                                 'x' => $i * 9 / 8 - 0.5 * $hd_ratio,
                                'y' => -0.5 * $hd_ratio,
                                'z' => $k * 9 / 8 - 0.5 * $hd_ratio 
                            ) );
                        }
                        if ( !isset( $volume_points[ $i ][ 8 * $hd_ratio ][ $k ] ) ) {
                            $volume_points[ $i ][ 8 * $hd_ratio ][ $k ] = new Point( array(
                                 'x' => $i * 9 / 8 - 0.5 * $hd_ratio,
                                'y' => 8.5 * $hd_ratio,
                                'z' => $k * 9 / 8 - 0.5 * $hd_ratio 
                            ) );
                        }
                    }
                }
                for ( $i = 0; $i < 8 * $hd_ratio; $i++ ) {
                    for ( $j = 0; $j < 8 * $hd_ratio; $j++ ) {
                        $this->polygons[ 'helmet' ][ 'back' ][]  = new Polygon( array(
                             $volume_points[ $i ][ $j ][ -2 * $hd_ratio ],
                            $volume_points[ $i + 1 ][ $j ][ -2 * $hd_ratio ],
                            $volume_points[ $i + 1 ][ $j + 1 ][ -2 * $hd_ratio ],
                            $volume_points[ $i ][ $j + 1 ][ -2 * $hd_ratio ] 
                        ), imagecolorat( $img_png, 32 * $hd_ratio + ( 32 * $hd_ratio - 1 ) - $i, 8 * $hd_ratio + $j ) );
                        $this->polygons[ 'helmet' ][ 'front' ][] = new Polygon( array(
                             $volume_points[ $i ][ $j ][ 6 * $hd_ratio ],
                            $volume_points[ $i + 1 ][ $j ][ 6 * $hd_ratio ],
                            $volume_points[ $i + 1 ][ $j + 1 ][ 6 * $hd_ratio ],
                            $volume_points[ $i ][ $j + 1 ][ 6 * $hd_ratio ] 
                        ), imagecolorat( $img_png, 32 * $hd_ratio + 8 * $hd_ratio + $i, 8 * $hd_ratio + $j ) );
                    }
                }
                for ( $j = 0; $j < 8 * $hd_ratio; $j++ ) {
                    for ( $k = -2 * $hd_ratio; $k < 6 * $hd_ratio; $k++ ) {
                        $this->polygons[ 'helmet' ][ 'right' ][] = new Polygon( array(
                             $volume_points[ 0 ][ $j ][ $k ],
                            $volume_points[ 0 ][ $j ][ $k + 1 ],
                            $volume_points[ 0 ][ $j + 1 ][ $k + 1 ],
                            $volume_points[ 0 ][ $j + 1 ][ $k ] 
                        ), imagecolorat( $img_png, 32 * $hd_ratio + $k + 2 * $hd_ratio, 8 * $hd_ratio + $j ) );
                        $this->polygons[ 'helmet' ][ 'left' ][]  = new Polygon( array(
                             $volume_points[ 8 * $hd_ratio ][ $j ][ $k ],
                            $volume_points[ 8 * $hd_ratio ][ $j ][ $k + 1 ],
                            $volume_points[ 8 * $hd_ratio ][ $j + 1 ][ $k + 1 ],
                            $volume_points[ 8 * $hd_ratio ][ $j + 1 ][ $k ] 
                        ), imagecolorat( $img_png, 32 * $hd_ratio + ( 24 * $hd_ratio - 1 ) - $k - 2 * $hd_ratio, 8 * $hd_ratio + $j ) );
                    }
                }
                for ( $i = 0; $i < 8 * $hd_ratio; $i++ ) {
                    for ( $k = -2 * $hd_ratio; $k < 6 * $hd_ratio; $k++ ) {
                        $this->polygons[ 'helmet' ][ 'top' ][]    = new Polygon( array(
                             $volume_points[ $i ][ 0 ][ $k ],
                            $volume_points[ $i + 1 ][ 0 ][ $k ],
                            $volume_points[ $i + 1 ][ 0 ][ $k + 1 ],
                            $volume_points[ $i ][ 0 ][ $k + 1 ] 
                        ), imagecolorat( $img_png, 32 * $hd_ratio + 8 * $hd_ratio + $i, $k + 2 * $hd_ratio ) );
                        $this->polygons[ 'helmet' ][ 'bottom' ][] = new Polygon( array(
                             $volume_points[ $i ][ 8 * $hd_ratio ][ $k ],
                            $volume_points[ $i + 1 ][ 8 * $hd_ratio ][ $k ],
                            $volume_points[ $i + 1 ][ 8 * $hd_ratio ][ $k + 1 ],
                            $volume_points[ $i ][ 8 * $hd_ratio ][ $k + 1 ] 
                        ), imagecolorat( $img_png, 32 * $hd_ratio + 16 * $hd_ratio + $i, 2 * $hd_ratio + $k ) );
                    }
                }
            }
            if ( !$this->head_only ) {
                // TORSO
                $volume_points = array();
                for ( $i = 0; $i < 9 * $hd_ratio; $i++ ) {
                    for ( $j = 0; $j < 13 * $hd_ratio; $j++ ) {
                        if ( !isset( $volume_points[ $i ][ $j ][ 0 ] ) ) {
                            $volume_points[ $i ][ $j ][ 0 ] = new Point( array(
                                 'x' => $i,
                                'y' => $j + 8 * $hd_ratio,
                                'z' => 0 
                            ) );
                        }
                        if ( !isset( $volume_points[ $i ][ $j ][ 4 * $hd_ratio ] ) ) {
                            $volume_points[ $i ][ $j ][ 4 * $hd_ratio ] = new Point( array(
                                 'x' => $i,
                                'y' => $j + 8 * $hd_ratio,
                                'z' => 4 * $hd_ratio 
                            ) );
                        }
                    }
                }
                for ( $j = 0; $j < 13 * $hd_ratio; $j++ ) {
                    for ( $k = 0; $k < 5 * $hd_ratio; $k++ ) {
                        if ( !isset( $volume_points[ 0 ][ $j ][ $k ] ) ) {
                            $volume_points[ 0 ][ $j ][ $k ] = new Point( array(
                                 'x' => 0,
                                'y' => $j + 8 * $hd_ratio,
                                'z' => $k 
                            ) );
                        }
                        if ( !isset( $volume_points[ 8 * $hd_ratio ][ $j ][ $k ] ) ) {
                            $volume_points[ 8 * $hd_ratio ][ $j ][ $k ] = new Point( array(
                                 'x' => 8 * $hd_ratio,
                                'y' => $j + 8 * $hd_ratio,
                                'z' => $k 
                            ) );
                        }
                    }
                }
                for ( $i = 0; $i < 9 * $hd_ratio; $i++ ) {
                    for ( $k = 0; $k < 5 * $hd_ratio; $k++ ) {
                        if ( !isset( $volume_points[ $i ][ 0 ][ $k ] ) ) {
                            $volume_points[ $i ][ 0 ][ $k ] = new Point( array(
                                 'x' => $i,
                                'y' => 0 + 8 * $hd_ratio,
                                'z' => $k 
                            ) );
                        }
                        if ( !isset( $volume_points[ $i ][ 12 * $hd_ratio ][ $k ] ) ) {
                            $volume_points[ $i ][ 12 * $hd_ratio ][ $k ] = new Point( array(
                                 'x' => $i,
                                'y' => 12 * $hd_ratio + 8 * $hd_ratio,
                                'z' => $k 
                            ) );
                        }
                    }
                }
                for ( $i = 0; $i < 8 * $hd_ratio; $i++ ) {
                    for ( $j = 0; $j < 12 * $hd_ratio; $j++ ) {
                        $this->polygons[ 'torso' ][ 'back' ][]  = new Polygon( array(
                             $volume_points[ $i ][ $j ][ 0 ],
                            $volume_points[ $i + 1 ][ $j ][ 0 ],
                            $volume_points[ $i + 1 ][ $j + 1 ][ 0 ],
                            $volume_points[ $i ][ $j + 1 ][ 0 ] 
                        ), imagecolorat( $img_png, ( 40 * $hd_ratio - 1 ) - $i, 20 * $hd_ratio + $j ) );
                        $this->polygons[ 'torso' ][ 'front' ][] = new Polygon( array(
                             $volume_points[ $i ][ $j ][ 4 * $hd_ratio ],
                            $volume_points[ $i + 1 ][ $j ][ 4 * $hd_ratio ],
                            $volume_points[ $i + 1 ][ $j + 1 ][ 4 * $hd_ratio ],
                            $volume_points[ $i ][ $j + 1 ][ 4 * $hd_ratio ] 
                        ), imagecolorat( $img_png, 20 * $hd_ratio + $i, 20 * $hd_ratio + $j ) );
                    }
                }
                for ( $j = 0; $j < 12 * $hd_ratio; $j++ ) {
                    for ( $k = 0; $k < 4 * $hd_ratio; $k++ ) {
                        $this->polygons[ 'torso' ][ 'right' ][] = new Polygon( array(
                             $volume_points[ 0 ][ $j ][ $k ],
                            $volume_points[ 0 ][ $j ][ $k + 1 ],
                            $volume_points[ 0 ][ $j + 1 ][ $k + 1 ],
                            $volume_points[ 0 ][ $j + 1 ][ $k ] 
                        ), imagecolorat( $img_png, 16 * $hd_ratio + $k, 20 * $hd_ratio + $j ) );
                        $this->polygons[ 'torso' ][ 'left' ][]  = new Polygon( array(
                             $volume_points[ 8 * $hd_ratio ][ $j ][ $k ],
                            $volume_points[ 8 * $hd_ratio ][ $j ][ $k + 1 ],
                            $volume_points[ 8 * $hd_ratio ][ $j + 1 ][ $k + 1 ],
                            $volume_points[ 8 * $hd_ratio ][ $j + 1 ][ $k ] 
                        ), imagecolorat( $img_png, ( 32 * $hd_ratio - 1 ) - $k, 20 * $hd_ratio + $j ) );
                    }
                }
                for ( $i = 0; $i < 8 * $hd_ratio; $i++ ) {
                    for ( $k = 0; $k < 4 * $hd_ratio; $k++ ) {
                        $this->polygons[ 'torso' ][ 'top' ][]    = new Polygon( array(
                             $volume_points[ $i ][ 0 ][ $k ],
                            $volume_points[ $i + 1 ][ 0 ][ $k ],
                            $volume_points[ $i + 1 ][ 0 ][ $k + 1 ],
                            $volume_points[ $i ][ 0 ][ $k + 1 ] 
                        ), imagecolorat( $img_png, 20 * $hd_ratio + $i, 16 * $hd_ratio + $k ) );
                        $this->polygons[ 'torso' ][ 'bottom' ][] = new Polygon( array(
                             $volume_points[ $i ][ 12 * $hd_ratio ][ $k ],
                            $volume_points[ $i + 1 ][ 12 * $hd_ratio ][ $k ],
                            $volume_points[ $i + 1 ][ 12 * $hd_ratio ][ $k + 1 ],
                            $volume_points[ $i ][ 12 * $hd_ratio ][ $k + 1 ] 
                        ), imagecolorat( $img_png, 28 * $hd_ratio + $i, ( 20 * $hd_ratio - 1 ) - $k ) );
                    }
                }
                // RIGHT ARM
                $volume_points = array();
                for ( $i = 0; $i < 9 * $hd_ratio; $i++ ) {
                    for ( $j = 0; $j < 13 * $hd_ratio; $j++ ) {
                        if ( !isset( $volume_points[ $i ][ $j ][ 0 ] ) ) {
                            $volume_points[ $i ][ $j ][ 0 ] = new Point( array(
                                 'x' => $i - 4 * $hd_ratio,
                                'y' => $j + 8 * $hd_ratio,
                                'z' => 0 
                            ) );
                        }
                        if ( !isset( $volume_points[ $i ][ $j ][ 4 * $hd_ratio ] ) ) {
                            $volume_points[ $i ][ $j ][ 4 * $hd_ratio ] = new Point( array(
                                 'x' => $i - 4 * $hd_ratio,
                                'y' => $j + 8 * $hd_ratio,
                                'z' => 4 * $hd_ratio 
                            ) );
                        }
                    }
                }
                for ( $j = 0; $j < 13 * $hd_ratio; $j++ ) {
                    for ( $k = 0; $k < 5 * $hd_ratio; $k++ ) {
                        if ( !isset( $volume_points[ 0 ][ $j ][ $k ] ) ) {
                            $volume_points[ 0 ][ $j ][ $k ] = new Point( array(
                                 'x' => 0 - 4 * $hd_ratio,
                                'y' => $j + 8 * $hd_ratio,
                                'z' => $k 
                            ) );
                        }
                        if ( !isset( $volume_points[ 8 * $hd_ratio ][ $j ][ $k ] ) ) {
                            $volume_points[ 4 * $hd_ratio ][ $j ][ $k ] = new Point( array(
                                 'x' => 4 * $hd_ratio - 4 * $hd_ratio,
                                'y' => $j + 8 * $hd_ratio,
                                'z' => $k 
                            ) );
                        }
                    }
                }
                for ( $i = 0; $i < 9 * $hd_ratio; $i++ ) {
                    for ( $k = 0; $k < 5 * $hd_ratio; $k++ ) {
                        if ( !isset( $volume_points[ $i ][ 0 ][ $k ] ) ) {
                            $volume_points[ $i ][ 0 ][ $k ] = new Point( array(
                                 'x' => $i - 4 * $hd_ratio,
                                'y' => 0 + 8 * $hd_ratio,
                                'z' => $k 
                            ) );
                        }
                        if ( !isset( $volume_points[ $i ][ 12 * $hd_ratio ][ $k ] ) ) {
                            $volume_points[ $i ][ 12 * $hd_ratio ][ $k ] = new Point( array(
                                 'x' => $i - 4 * $hd_ratio,
                                'y' => 12 * $hd_ratio + 8 * $hd_ratio,
                                'z' => $k 
                            ) );
                        }
                    }
                }
                for ( $i = 0; $i < 4 * $hd_ratio; $i++ ) {
                    for ( $j = 0; $j < 12 * $hd_ratio; $j++ ) {
                        $this->polygons[ 'rightArm' ][ 'back' ][]  = new Polygon( array(
                             $volume_points[ $i ][ $j ][ 0 ],
                            $volume_points[ $i + 1 ][ $j ][ 0 ],
                            $volume_points[ $i + 1 ][ $j + 1 ][ 0 ],
                            $volume_points[ $i ][ $j + 1 ][ 0 ] 
                        ), imagecolorat( $img_png, ( 56 * $hd_ratio - 1 ) - $i, 20 * $hd_ratio + $j ) );
                        $this->polygons[ 'rightArm' ][ 'front' ][] = new Polygon( array(
                             $volume_points[ $i ][ $j ][ 4 * $hd_ratio ],
                            $volume_points[ $i + 1 ][ $j ][ 4 * $hd_ratio ],
                            $volume_points[ $i + 1 ][ $j + 1 ][ 4 * $hd_ratio ],
                            $volume_points[ $i ][ $j + 1 ][ 4 * $hd_ratio ] 
                        ), imagecolorat( $img_png, 44 * $hd_ratio + $i, 20 * $hd_ratio + $j ) );
                    }
                }
                for ( $j = 0; $j < 12 * $hd_ratio; $j++ ) {
                    for ( $k = 0; $k < 4 * $hd_ratio; $k++ ) {
                        $this->polygons[ 'rightArm' ][ 'right' ][] = new Polygon( array(
                             $volume_points[ 0 ][ $j ][ $k ],
                            $volume_points[ 0 ][ $j ][ $k + 1 ],
                            $volume_points[ 0 ][ $j + 1 ][ $k + 1 ],
                            $volume_points[ 0 ][ $j + 1 ][ $k ] 
                        ), imagecolorat( $img_png, 40 * $hd_ratio + $k, 20 * $hd_ratio + $j ) );
                        $this->polygons[ 'rightArm' ][ 'left' ][]  = new Polygon( array(
                             $volume_points[ 4 * $hd_ratio ][ $j ][ $k ],
                            $volume_points[ 4 * $hd_ratio ][ $j ][ $k + 1 ],
                            $volume_points[ 4 * $hd_ratio ][ $j + 1 ][ $k + 1 ],
                            $volume_points[ 4 * $hd_ratio ][ $j + 1 ][ $k ] 
                        ), imagecolorat( $img_png, ( 52 * $hd_ratio - 1 ) - $k, 20 * $hd_ratio + $j ) );
                    }
                }
                for ( $i = 0; $i < 4 * $hd_ratio; $i++ ) {
                    for ( $k = 0; $k < 4 * $hd_ratio; $k++ ) {
                        $this->polygons[ 'rightArm' ][ 'top' ][]    = new Polygon( array(
                             $volume_points[ $i ][ 0 ][ $k ],
                            $volume_points[ $i + 1 ][ 0 ][ $k ],
                            $volume_points[ $i + 1 ][ 0 ][ $k + 1 ],
                            $volume_points[ $i ][ 0 ][ $k + 1 ] 
                        ), imagecolorat( $img_png, 44 * $hd_ratio + $i, 16 * $hd_ratio + $k ) );
                        $this->polygons[ 'rightArm' ][ 'bottom' ][] = new Polygon( array(
                             $volume_points[ $i ][ 12 * $hd_ratio ][ $k ],
                            $volume_points[ $i + 1 ][ 12 * $hd_ratio ][ $k ],
                            $volume_points[ $i + 1 ][ 12 * $hd_ratio ][ $k + 1 ],
                            $volume_points[ $i ][ 12 * $hd_ratio ][ $k + 1 ] 
                        ), imagecolorat( $img_png, 48 * $hd_ratio + $i, 16 * $hd_ratio + $k ) );
                    }
                }
                // LEFT ARM
                $volume_points = array();
                for ( $i = 0; $i < 9 * $hd_ratio; $i++ ) {
                    for ( $j = 0; $j < 13 * $hd_ratio; $j++ ) {
                        if ( !isset( $volume_points[ $i ][ $j ][ 0 ] ) ) {
                            $volume_points[ $i ][ $j ][ 0 ] = new Point( array(
                                 'x' => $i + 8 * $hd_ratio,
                                'y' => $j + 8 * $hd_ratio,
                                'z' => 0 
                            ) );
                        }
                        if ( !isset( $volume_points[ $i ][ $j ][ 4 * $hd_ratio ] ) ) {
                            $volume_points[ $i ][ $j ][ 4 * $hd_ratio ] = new Point( array(
                                 'x' => $i + 8 * $hd_ratio,
                                'y' => $j + 8 * $hd_ratio,
                                'z' => 4 * $hd_ratio 
                            ) );
                        }
                    }
                }
                for ( $j = 0; $j < 13 * $hd_ratio; $j++ ) {
                    for ( $k = 0; $k < 5 * $hd_ratio; $k++ ) {
                        if ( !isset( $volume_points[ 0 ][ $j ][ $k ] ) ) {
                            $volume_points[ 0 ][ $j ][ $k ] = new Point( array(
                                 'x' => 0 + 8 * $hd_ratio,
                                'y' => $j + 8 * $hd_ratio,
                                'z' => $k 
                            ) );
                        }
                        if ( !isset( $volume_points[ 8 * $hd_ratio ][ $j ][ $k ] ) ) {
                            $volume_points[ 4 * $hd_ratio ][ $j ][ $k ] = new Point( array(
                                 'x' => 4 * $hd_ratio + 8 * $hd_ratio,
                                'y' => $j + 8 * $hd_ratio,
                                'z' => $k 
                            ) );
                        }
                    }
                }
                for ( $i = 0; $i < 9 * $hd_ratio; $i++ ) {
                    for ( $k = 0; $k < 5 * $hd_ratio; $k++ ) {
                        if ( !isset( $volume_points[ $i ][ 0 ][ $k ] ) ) {
                            $volume_points[ $i ][ 0 ][ $k ] = new Point( array(
                                 'x' => $i + 8 * $hd_ratio,
                                'y' => 0 + 8 * $hd_ratio,
                                'z' => $k 
                            ) );
                        }
                        if ( !isset( $volume_points[ $i ][ 12 * $hd_ratio ][ $k ] ) ) {
                            $volume_points[ $i ][ 12 * $hd_ratio ][ $k ] = new Point( array(
                                 'x' => $i + 8 * $hd_ratio,
                                'y' => 12 * $hd_ratio + 8 * $hd_ratio,
                                'z' => $k 
                            ) );
                        }
                    }
                }
                for ( $i = 0; $i < 4 * $hd_ratio; $i++ ) {
                    for ( $j = 0; $j < 12 * $hd_ratio; $j++ ) {
                        if($this->isNewSkinType) {
                            $color1 = imagecolorat( $img_png, 47 * $hd_ratio - $i, 52 * $hd_ratio + $j ); // from right to left
                            $color2 = imagecolorat( $img_png, 36 * $hd_ratio + $i , 52 * $hd_ratio + $j ); // from left to right
                        } else {
                            $color1 = imagecolorat( $img_png, ( 56 * $hd_ratio - 1 ) - ( ( 4 * $hd_ratio - 1 ) - $i ), 20 * $hd_ratio + $j );
                            $color2 = imagecolorat( $img_png, 44 * $hd_ratio + ( ( 4 * $hd_ratio - 1 ) - $i ), 20 * $hd_ratio + $j );
                        }
                        
                        $this->polygons[ 'leftArm' ][ 'back' ][]  = new Polygon( array(
                             $volume_points[ $i ][ $j ][ 0 ],
                            $volume_points[ $i + 1 ][ $j ][ 0 ],
                            $volume_points[ $i + 1 ][ $j + 1 ][ 0 ],
                            $volume_points[ $i ][ $j + 1 ][ 0 ] 
                        ), $color1 );
                        $this->polygons[ 'leftArm' ][ 'front' ][] = new Polygon( array(
                             $volume_points[ $i ][ $j ][ 4 * $hd_ratio ],
                            $volume_points[ $i + 1 ][ $j ][ 4 * $hd_ratio ],
                            $volume_points[ $i + 1 ][ $j + 1 ][ 4 * $hd_ratio ],
                            $volume_points[ $i ][ $j + 1 ][ 4 * $hd_ratio ] 
                        ), $color2 );
                    }
                }
                for ( $j = 0; $j < 12 * $hd_ratio; $j++ ) {
                    for ( $k = 0; $k < 4 * $hd_ratio; $k++ ) {
                        if($this->isNewSkinType) {
                            $color1 = imagecolorat( $img_png, 32 * $hd_ratio + $k, 52 * $hd_ratio + $j ); // from left to right
                            $color2 = imagecolorat( $img_png, 43 * $hd_ratio - $k, 52 * $hd_ratio + $j ); // from right to left
                        } else {
                            $color1 = imagecolorat( $img_png, 40 * $hd_ratio + ( ( 4 * $hd_ratio - 1 ) - $k ), 20 * $hd_ratio + $j );
                            $color2 = imagecolorat( $img_png, ( 52 * $hd_ratio - 1 ) - ( ( 4 * $hd_ratio - 1 ) - $k ), 20 * $hd_ratio + $j );
                        }
                        
                        $this->polygons[ 'leftArm' ][ 'right' ][] = new Polygon( array(
                             $volume_points[ 0 ][ $j ][ $k ],
                            $volume_points[ 0 ][ $j ][ $k + 1 ],
                            $volume_points[ 0 ][ $j + 1 ][ $k + 1 ],
                            $volume_points[ 0 ][ $j + 1 ][ $k ] 
                        ), $color1 );
                        $this->polygons[ 'leftArm' ][ 'left' ][]  = new Polygon( array(
                             $volume_points[ 4 * $hd_ratio ][ $j ][ $k ],
                            $volume_points[ 4 * $hd_ratio ][ $j ][ $k + 1 ],
                            $volume_points[ 4 * $hd_ratio ][ $j + 1 ][ $k + 1 ],
                            $volume_points[ 4 * $hd_ratio ][ $j + 1 ][ $k ] 
                        ), $color2 );
                    }
                }
                for ( $i = 0; $i < 4 * $hd_ratio; $i++ ) {
                    for ( $k = 0; $k < 4 * $hd_ratio; $k++ ) {
                        if($this->isNewSkinType) {
                            $color1 = imagecolorat( $img_png, 36 * $hd_ratio + $i, 48 * $hd_ratio + $k ); // from left to right
                            $color2 = imagecolorat( $img_png, 40 * $hd_ratio + $i, 48 * $hd_ratio + $k ); // from left to right
                        } else {
                            $color1 = imagecolorat( $img_png, 44 * $hd_ratio + ( ( 4 * $hd_ratio - 1 ) - $i ), 16 * $hd_ratio + $k );
                            $color2 = imagecolorat( $img_png, 48 * $hd_ratio + ( ( 4 * $hd_ratio - 1 ) - $i ), ( 20 * $hd_ratio - 1 ) - $k );
                        }
                        
                        $this->polygons[ 'leftArm' ][ 'top' ][]    = new Polygon( array(
                             $volume_points[ $i ][ 0 ][ $k ],
                            $volume_points[ $i + 1 ][ 0 ][ $k ],
                            $volume_points[ $i + 1 ][ 0 ][ $k + 1 ],
                            $volume_points[ $i ][ 0 ][ $k + 1 ] 
                        ), $color1 );
                        $this->polygons[ 'leftArm' ][ 'bottom' ][] = new Polygon( array(
                             $volume_points[ $i ][ 12 * $hd_ratio ][ $k ],
                            $volume_points[ $i + 1 ][ 12 * $hd_ratio ][ $k ],
                            $volume_points[ $i + 1 ][ 12 * $hd_ratio ][ $k + 1 ],
                            $volume_points[ $i ][ 12 * $hd_ratio ][ $k + 1 ] 
                        ), $color2 );
                    }
                }
                // RIGHT LEG
                $volume_points = array();
                for ( $i = 0; $i < 9 * $hd_ratio; $i++ ) {
                    for ( $j = 0; $j < 13 * $hd_ratio; $j++ ) {
                        if ( !isset( $volume_points[ $i ][ $j ][ 0 ] ) ) {
                            $volume_points[ $i ][ $j ][ 0 ] = new Point( array(
                                 'x' => $i,
                                'y' => $j + 20 * $hd_ratio,
                                'z' => 0 
                            ) );
                        }
                        if ( !isset( $volume_points[ $i ][ $j ][ 4 * $hd_ratio ] ) ) {
                            $volume_points[ $i ][ $j ][ 4 * $hd_ratio ] = new Point( array(
                                 'x' => $i,
                                'y' => $j + 20 * $hd_ratio,
                                'z' => 4 * $hd_ratio 
                            ) );
                        }
                    }
                }
                for ( $j = 0; $j < 13 * $hd_ratio; $j++ ) {
                    for ( $k = 0; $k < 5 * $hd_ratio; $k++ ) {
                        if ( !isset( $volume_points[ 0 ][ $j ][ $k ] ) ) {
                            $volume_points[ 0 ][ $j ][ $k ] = new Point( array(
                                 'x' => 0,
                                'y' => $j + 20 * $hd_ratio,
                                'z' => $k 
                            ) );
                        }
                        if ( !isset( $volume_points[ 8 * $hd_ratio ][ $j ][ $k ] ) ) {
                            $volume_points[ 4 * $hd_ratio ][ $j ][ $k ] = new Point( array(
                                 'x' => 4 * $hd_ratio,
                                'y' => $j + 20 * $hd_ratio,
                                'z' => $k 
                            ) );
                        }
                    }
                }
                for ( $i = 0; $i < 9 * $hd_ratio; $i++ ) {
                    for ( $k = 0; $k < 5 * $hd_ratio; $k++ ) {
                        if ( !isset( $volume_points[ $i ][ 0 ][ $k ] ) ) {
                            $volume_points[ $i ][ 0 ][ $k ] = new Point( array(
                                 'x' => $i,
                                'y' => 0 + 20 * $hd_ratio,
                                'z' => $k 
                            ) );
                        }
                        if ( !isset( $volume_points[ $i ][ 12 * $hd_ratio ][ $k ] ) ) {
                            $volume_points[ $i ][ 12 * $hd_ratio ][ $k ] = new Point( array(
                                 'x' => $i,
                                'y' => 12 * $hd_ratio + 20 * $hd_ratio,
                                'z' => $k 
                            ) );
                        }
                    }
                }
                for ( $i = 0; $i < 4 * $hd_ratio; $i++ ) {
                    for ( $j = 0; $j < 12 * $hd_ratio; $j++ ) {
                        $this->polygons[ 'rightLeg' ][ 'back' ][]  = new Polygon( array(
                             $volume_points[ $i ][ $j ][ 0 ],
                            $volume_points[ $i + 1 ][ $j ][ 0 ],
                            $volume_points[ $i + 1 ][ $j + 1 ][ 0 ],
                            $volume_points[ $i ][ $j + 1 ][ 0 ] 
                        ), imagecolorat( $img_png, ( 16 * $hd_ratio - 1 ) - $i, 20 * $hd_ratio + $j ) );
                        $this->polygons[ 'rightLeg' ][ 'front' ][] = new Polygon( array(
                             $volume_points[ $i ][ $j ][ 4 * $hd_ratio ],
                            $volume_points[ $i + 1 ][ $j ][ 4 * $hd_ratio ],
                            $volume_points[ $i + 1 ][ $j + 1 ][ 4 * $hd_ratio ],
                            $volume_points[ $i ][ $j + 1 ][ 4 * $hd_ratio ] 
                        ), imagecolorat( $img_png, 4 * $hd_ratio + $i, 20 * $hd_ratio + $j ) );
                    }
                }
                for ( $j = 0; $j < 12 * $hd_ratio; $j++ ) {
                    for ( $k = 0; $k < 4 * $hd_ratio; $k++ ) {
                        $this->polygons[ 'rightLeg' ][ 'right' ][] = new Polygon( array(
                             $volume_points[ 0 ][ $j ][ $k ],
                            $volume_points[ 0 ][ $j ][ $k + 1 ],
                            $volume_points[ 0 ][ $j + 1 ][ $k + 1 ],
                            $volume_points[ 0 ][ $j + 1 ][ $k ] 
                        ), imagecolorat( $img_png, 0 + $k, 20 * $hd_ratio + $j ) );
                        $this->polygons[ 'rightLeg' ][ 'left' ][]  = new Polygon( array(
                             $volume_points[ 4 * $hd_ratio ][ $j ][ $k ],
                            $volume_points[ 4 * $hd_ratio ][ $j ][ $k + 1 ],
                            $volume_points[ 4 * $hd_ratio ][ $j + 1 ][ $k + 1 ],
                            $volume_points[ 4 * $hd_ratio ][ $j + 1 ][ $k ] 
                        ), imagecolorat( $img_png, ( 12 * $hd_ratio - 1 ) - $k, 20 * $hd_ratio + $j ) );
                    }
                }
                for ( $i = 0; $i < 4 * $hd_ratio; $i++ ) {
                    for ( $k = 0; $k < 4 * $hd_ratio; $k++ ) {
                        $this->polygons[ 'rightLeg' ][ 'top' ][]    = new Polygon( array(
                             $volume_points[ $i ][ 0 ][ $k ],
                            $volume_points[ $i + 1 ][ 0 ][ $k ],
                            $volume_points[ $i + 1 ][ 0 ][ $k + 1 ],
                            $volume_points[ $i ][ 0 ][ $k + 1 ] 
                        ), imagecolorat( $img_png, 4 * $hd_ratio + $i, 16 * $hd_ratio + $k ) );
                        $this->polygons[ 'rightLeg' ][ 'bottom' ][] = new Polygon( array(
                             $volume_points[ $i ][ 12 * $hd_ratio ][ $k ],
                            $volume_points[ $i + 1 ][ 12 * $hd_ratio ][ $k ],
                            $volume_points[ $i + 1 ][ 12 * $hd_ratio ][ $k + 1 ],
                            $volume_points[ $i ][ 12 * $hd_ratio ][ $k + 1 ] 
                        ), imagecolorat( $img_png, 8 * $hd_ratio + $i, 16 * $hd_ratio + $k ) );
                    }
                }
                // LEFT LEG
                $volume_points = array();
                for ( $i = 0; $i < 9 * $hd_ratio; $i++ ) {
                    for ( $j = 0; $j < 13 * $hd_ratio; $j++ ) {
                        if ( !isset( $volume_points[ $i ][ $j ][ 0 ] ) ) {
                            $volume_points[ $i ][ $j ][ 0 ] = new Point( array(
                                 'x' => $i + 4 * $hd_ratio,
                                'y' => $j + 20 * $hd_ratio,
                                'z' => 0 
                            ) );
                        }
                        if ( !isset( $volume_points[ $i ][ $j ][ 4 * $hd_ratio ] ) ) {
                            $volume_points[ $i ][ $j ][ 4 * $hd_ratio ] = new Point( array(
                                 'x' => $i + 4 * $hd_ratio,
                                'y' => $j + 20 * $hd_ratio,
                                'z' => 4 * $hd_ratio 
                            ) );
                        }
                    }
                }
                for ( $j = 0; $j < 13 * $hd_ratio; $j++ ) {
                    for ( $k = 0; $k < 5 * $hd_ratio; $k++ ) {
                        if ( !isset( $volume_points[ 0 ][ $j ][ $k ] ) ) {
                            $volume_points[ 0 ][ $j ][ $k ] = new Point( array(
                                 'x' => 0 + 4 * $hd_ratio,
                                'y' => $j + 20 * $hd_ratio,
                                'z' => $k 
                            ) );
                        }
                        if ( !isset( $volume_points[ 8 * $hd_ratio ][ $j ][ $k ] ) ) {
                            $volume_points[ 4 * $hd_ratio ][ $j ][ $k ] = new Point( array(
                                 'x' => 4 * $hd_ratio + 4 * $hd_ratio,
                                'y' => $j + 20 * $hd_ratio,
                                'z' => $k 
                            ) );
                        }
                    }
                }
                for ( $i = 0; $i < 9 * $hd_ratio; $i++ ) {
                    for ( $k = 0; $k < 5 * $hd_ratio; $k++ ) {
                        if ( !isset( $volume_points[ $i ][ 0 ][ $k ] ) ) {
                            $volume_points[ $i ][ 0 ][ $k ] = new Point( array(
                                 'x' => $i + 4 * $hd_ratio,
                                'y' => 0 + 20 * $hd_ratio,
                                'z' => $k 
                            ) );
                        }
                        if ( !isset( $volume_points[ $i ][ 12 * $hd_ratio ][ $k ] ) ) {
                            $volume_points[ $i ][ 12 * $hd_ratio ][ $k ] = new Point( array(
                                 'x' => $i + 4 * $hd_ratio,
                                'y' => 12 * $hd_ratio + 20 * $hd_ratio,
                                'z' => $k 
                            ) );
                        }
                    }
                }
                for ( $i = 0; $i < 4 * $hd_ratio; $i++ ) {
                    for ( $j = 0; $j < 12 * $hd_ratio; $j++ ) {
                        if($this->isNewSkinType) {
                            $color1 = imagecolorat( $img_png, 31 * $hd_ratio - $i, 52 * $hd_ratio + $j ); // from right to left
                            $color2 = imagecolorat( $img_png, 20 * $hd_ratio + $i , 52 * $hd_ratio + $j ); // from left to right
                        } else {
                            $color1 = imagecolorat( $img_png, ( 16 * $hd_ratio - 1 ) - ( ( 4 * $hd_ratio - 1 ) - $i ), 20 * $hd_ratio + $j );
                            $color2 = imagecolorat( $img_png, 4 * $hd_ratio + ( ( 4 * $hd_ratio - 1 ) - $i ), 20 * $hd_ratio + $j );
                        }
                        
                        $this->polygons[ 'leftLeg' ][ 'back' ][]  = new Polygon( array(
                             $volume_points[ $i ][ $j ][ 0 ],
                            $volume_points[ $i + 1 ][ $j ][ 0 ],
                            $volume_points[ $i + 1 ][ $j + 1 ][ 0 ],
                            $volume_points[ $i ][ $j + 1 ][ 0 ] 
                        ), $color1 );
                        $this->polygons[ 'leftLeg' ][ 'front' ][] = new Polygon( array(
                             $volume_points[ $i ][ $j ][ 4 * $hd_ratio ],
                            $volume_points[ $i + 1 ][ $j ][ 4 * $hd_ratio ],
                            $volume_points[ $i + 1 ][ $j + 1 ][ 4 * $hd_ratio ],
                            $volume_points[ $i ][ $j + 1 ][ 4 * $hd_ratio ] 
                        ), $color2 );
                    }
                }
                for ( $j = 0; $j < 12 * $hd_ratio; $j++ ) {
                    for ( $k = 0; $k < 4 * $hd_ratio; $k++ ) {
                        if($this->isNewSkinType) {
                            $color1 = imagecolorat( $img_png, 16 * $hd_ratio + $k , 52 * $hd_ratio + $j ); // from left to right
                            $color2 = imagecolorat( $img_png, 27 * $hd_ratio - $k , 52 * $hd_ratio + $j ); // from right to left
                        } else {
                            $color1 = imagecolorat( $img_png, 0 + ( ( 4 * $hd_ratio - 1 ) - $k ), 20 * $hd_ratio + $j );
                            $color2 = imagecolorat( $img_png, ( 12 * $hd_ratio - 1 ) - ( ( 4 * $hd_ratio - 1 ) - $k ), 20 * $hd_ratio + $j );
                        }
                        
                        $this->polygons[ 'leftLeg' ][ 'right' ][] = new Polygon( array(
                             $volume_points[ 0 ][ $j ][ $k ],
                            $volume_points[ 0 ][ $j ][ $k + 1 ],
                            $volume_points[ 0 ][ $j + 1 ][ $k + 1 ],
                            $volume_points[ 0 ][ $j + 1 ][ $k ] 
                        ), $color1 );
                        $this->polygons[ 'leftLeg' ][ 'left' ][]  = new Polygon( array(
                             $volume_points[ 4 * $hd_ratio ][ $j ][ $k ],
                            $volume_points[ 4 * $hd_ratio ][ $j ][ $k + 1 ],
                            $volume_points[ 4 * $hd_ratio ][ $j + 1 ][ $k + 1 ],
                            $volume_points[ 4 * $hd_ratio ][ $j + 1 ][ $k ] 
                        ), $color2 );
                    }
                }
                for ( $i = 0; $i < 4 * $hd_ratio; $i++ ) {
                    for ( $k = 0; $k < 4 * $hd_ratio; $k++ ) {
                        if($this->isNewSkinType) {
                            $color1 = imagecolorat( $img_png, 20 * $hd_ratio + $i , 48 * $hd_ratio + $k ); // from left to right
                            $color2 = imagecolorat( $img_png, 24 * $hd_ratio + $i , 48 * $hd_ratio + $k ); // from left to right
                        } else {
                            $color1 = imagecolorat( $img_png, 4 * $hd_ratio + ( ( 4 * $hd_ratio - 1 ) - $i ), 16 * $hd_ratio + $k );
                            $color2 = imagecolorat( $img_png, 8 * $hd_ratio + ( ( 4 * $hd_ratio - 1 ) - $i ), ( 20 * $hd_ratio - 1 ) - $k );
                        }
                        
                        $this->polygons[ 'leftLeg' ][ 'top' ][]    = new Polygon( array(
                             $volume_points[ $i ][ 0 ][ $k ],
                            $volume_points[ $i + 1 ][ 0 ][ $k ],
                            $volume_points[ $i + 1 ][ 0 ][ $k + 1 ],
                            $volume_points[ $i ][ 0 ][ $k + 1 ] 
                        ), $color1 );
                        $this->polygons[ 'leftLeg' ][ 'bottom' ][] = new Polygon( array(
                             $volume_points[ $i ][ 12 * $hd_ratio ][ $k ],
                            $volume_points[ $i + 1 ][ 12 * $hd_ratio ][ $k ],
                            $volume_points[ $i + 1 ][ 12 * $hd_ratio ][ $k + 1 ],
                            $volume_points[ $i ][ 12 * $hd_ratio ][ $k + 1 ] 
                        ), $color2 );
                    }
                }
            }           
        }
        
        /* Function rotates members
         *
         */
        private function memberRotation() {
            foreach ($this->polygons['head'] as $face ) {
                foreach ( $face as $poly ) {
                    $poly->preProject( 4, 8, 2, $this->members_angles[ 'head' ][ 'cos_alpha' ], $this->members_angles[ 'head' ][ 'sin_alpha' ], $this->members_angles[ 'head' ][ 'cos_omega' ], $this->members_angles[ 'head' ][ 'sin_omega' ] );
                }
            }
            
            if ($this->display_hair) {
                foreach ( $this->polygons[ 'helmet' ] as $face ) {
                    foreach ( $face as $poly ) {
                        $poly->preProject( 4, 8, 2, $this->members_angles[ 'head' ][ 'cos_alpha' ], $this->members_angles[ 'head' ][ 'sin_alpha' ], $this->members_angles[ 'head' ][ 'cos_omega' ], $this->members_angles[ 'head' ][ 'sin_omega' ] );
                    }
                }
            }
            
            if (!$this->head_only) {
                foreach ( $this->polygons[ 'rightArm' ] as $face ) {
                    foreach ( $face as $poly ) {
                        $poly->preProject( -2, 8, 2, $this->members_angles[ 'rightArm' ][ 'cos_alpha' ], $this->members_angles[ 'rightArm' ][ 'sin_alpha' ], $this->members_angles[ 'rightArm' ][ 'cos_omega' ], $this->members_angles[ 'rightArm' ][ 'sin_omega' ] );
                    }
                }
                foreach ( $this->polygons[ 'leftArm' ] as $face ) {
                    foreach ( $face as $poly ) {
                        $poly->preProject( 10, 8, 2, $this->members_angles[ 'leftArm' ][ 'cos_alpha' ], $this->members_angles[ 'leftArm' ][ 'sin_alpha' ], $this->members_angles[ 'leftArm' ][ 'cos_omega' ], $this->members_angles[ 'leftArm' ][ 'sin_omega' ] );
                    }
                }
                foreach ( $this->polygons[ 'rightLeg' ] as $face ) {
                    foreach ( $face as $poly ) {
                        $poly->preProject( 2, 20, ( $this->members_angles[ 'rightLeg' ][ 'sin_alpha' ] < 0 ? 0 : 4 ), $this->members_angles[ 'rightLeg' ][ 'cos_alpha' ], $this->members_angles[ 'rightLeg' ][ 'sin_alpha' ], $this->members_angles[ 'rightLeg' ][ 'cos_omega' ], $this->members_angles[ 'rightLeg' ][ 'sin_omega' ] );
                    }
                }
                foreach ( $this->polygons[ 'leftLeg' ] as $face ) {
                    foreach ( $face as $poly ) {
                        $poly->preProject( 6, 20, ( $this->members_angles[ 'leftLeg' ][ 'sin_alpha' ] < 0 ? 0 : 4 ), $this->members_angles[ 'leftLeg' ][ 'cos_alpha' ], $this->members_angles[ 'leftLeg' ][ 'sin_alpha' ], $this->members_angles[ 'leftLeg' ][ 'cos_omega' ], $this->members_angles[ 'leftLeg' ][ 'sin_omega' ] );
                    }
                }
            }
        }
        
        /* Create projection plan
         *
         */
        private function createProjectionPlan() {
            foreach ($this->polygons as $piece) {
                foreach ($piece as $face) {
                    foreach ($face as $poly) {
                        if (!$poly->isProjected()) {
                            $poly->project();
                        }
                    }
                }
            }
        }
        
        /* Function displays the image
         *
         */
        private function displayImage($output) {
            global $minX, $maxX, $minY, $maxY;
            global $seconds_to_cache;
            
            $width = $maxX - $minX;
            $height = $maxY - $minY;
            $ratio = $this->ratio;
            if ( $ratio < 2 ) {
                $ratio = 2;
            }
            
            if($this->aa === true) {
                // double the ration for downscaling later (sort of AA)
                $ratio = $ratio * 2;
            }
            
            if ($seconds_to_cache > 0) {
                $ts = gmdate( "D, d M Y H:i:s", time() + $seconds_to_cache ) . ' GMT';
                if($output != 'return') {
                    header( 'Expires: ' . $ts );
                    header( 'Pragma: cache' );
                    header( 'Cache-Control: max-age=' . $seconds_to_cache );
                }
            }
            
            if ($this->format != 'svg') {
                $srcWidth = $ratio * $width + 1;
                $srcHeight = $ratio * $height + 1;
                $realWidth = $srcWidth / 2;
                $realHeight = $srcHeight / 2;
                
                $image = img::createEmptyCanvas($srcWidth, $srcHeight);
            }
            
            $display_order = $this->getDisplayOrder();
            
            $imgOutput = '';
            if($this->format == 'svg') {
                $imgOutput .= '<svg width="100%" height="100%" version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="' . $minX . ' ' . $minY . ' ' . $width . ' ' . $height . '">';
            }
            
            foreach ($display_order as $pieces) {
                foreach ($pieces as $piece => $faces) {
                    foreach ($faces as $face) {
                        foreach ($this->polygons[$piece][$face] as $poly) {
                            if ($this->format == 'svg') {
                                $imgOutput .= $poly->getSvgPolygon(1);
                            } else {
                                $poly->addPngPolygon($image, $minX, $minY, $ratio);
                            }
                        }
                    }
                }
            }
            
            if($this->format == 'svg') {
                $imgOutput .= '</svg>';
            }
            
            if ($this->format !== 'svg') {
                if($this->aa === true) {
                    // image normal size (sort of AA).
                    // resize the image down to it's normal size so it will be smoother
                    $destImage = img::createEmptyCanvas($realWidth, $realHeight);
                    
                    imagecopyresampled($destImage, $image, 0, 0, 0, 0, $realWidth, $realHeight, $srcWidth, $srcHeight);
                    $image = $destImage;
                }
                
                $imgData = null;
                if($this->format == 'base64') {
                    // output png;base64
                    ob_start();
                    imagepng($image);
                    $imgData = ob_get_contents();
                    ob_end_clean();
                } else {
                    $imgOutput = $image;
                }
                
                if($imgData !== null) {
                    $imgOutput = base64_encode($imgData);
                    imagedestroy($image);
                }
            }
            
            return $imgOutput;
        }
        
        /* Function retuns display order
         *
         */
        private function getDisplayOrder() {
            $display_order = array();
            if ( in_array( 'top', $this->front_faces ) ) {
                if ( in_array( 'right', $this->front_faces ) ) {
                    $display_order[] = array('leftLeg' => $this->back_faces);
                    $display_order[] = array('leftLeg' => $this->visible_faces['leftLeg']['front']);
                    $display_order[] = array('rightLeg' => $this->back_faces);
                    $display_order[] = array('rightLeg' => $this->visible_faces['rightLeg']['front']);
                    $display_order[] = array('leftArm' => $this->back_faces);
                    $display_order[] = array('leftArm' => $this->visible_faces['leftArm']['front']);
                    $display_order[] = array('torso' => $this->back_faces);
                    $display_order[] = array('torso' => $this->visible_faces['torso']['front']);
                    $display_order[] = array('rightArm' => $this->back_faces);
                    $display_order[] = array('rightArm' => $this->visible_faces['rightArm']['front']);
                } else {
                    $display_order[] = array('rightLeg' => $this->back_faces);
                    $display_order[] = array('rightLeg' => $this->visible_faces['rightLeg' ]['front']);
                    $display_order[] = array('leftLeg' => $this->back_faces);
                    $display_order[] = array('leftLeg' => $this->visible_faces['leftLeg']['front']);
                    $display_order[] = array('rightArm' => $this->back_faces);
                    $display_order[] = array('rightArm' => $this->visible_faces['rightArm']['front']);
                    $display_order[] = array('torso' => $this->back_faces);
                    $display_order[] = array('torso' => $this->visible_faces['torso']['front']);
                    $display_order[] = array('leftArm' => $this->back_faces);
                    $display_order[] = array('leftArm' => $this->visible_faces['leftArm']['front']);
                }
                
                $display_order[] = array('helmet' => $this->back_faces);
                $display_order[] = array('head' => $this->back_faces);
                $display_order[] = array('head' => $this->visible_faces['head']['front']);
                $display_order[] = array('helmet' => $this->visible_faces['head']['front']);
            } else {
                $display_order[] = array('helmet' => $this->back_faces);
                $display_order[] = array('head' => $this->back_faces);
                $display_order[] = array('head' => $this->visible_faces['head']['front']);
                $display_order[] = array('helmet' => $this->visible_faces['head']['front']);
                
                if ( in_array( 'right', $this->front_faces ) ) {
                    $display_order[] = array('leftArm' => $this->back_faces);
                    $display_order[] = array('leftArm' => $this->visible_faces['leftArm']['front']);
                    $display_order[] = array('torso' => $this->back_faces);
                    $display_order[] = array('torso' => $this->visible_faces['torso']['front']);
                    $display_order[] = array('rightArm' => $this->back_faces);
                    $display_order[] = array('rightArm' => $this->visible_faces['rightArm']['front']);
                    $display_order[] = array('leftLeg' => $this->back_faces);
                    $display_order[] = array('leftLeg' => $this->visible_faces['leftLeg' ]['front']);
                    $display_order[] = array('rightLeg' => $this->back_faces);
                    $display_order[] = array('rightLeg' => $this->visible_faces['rightLeg']['front']);
                } else {
                    $display_order[] = array('rightArm' => $this->back_faces);
                    $display_order[] = array('rightArm' => $this->visible_faces['rightArm']['front']);
                    $display_order[] = array('torso' => $this->back_faces);
                    $display_order[] = array('torso' => $this->visible_faces['torso']['front']);
                    $display_order[] = array('leftArm' => $this->back_faces);
                    $display_order[] = array('leftArm' => $this->visible_faces['leftArm']['front']);
                    $display_order[] = array('rightLeg' => $this->back_faces);
                    $display_order[] = array('rightLeg' => $this->visible_faces['rightLeg']['front']);
                    $display_order[] = array('leftLeg' => $this->back_faces);
                    $display_order[] = array('leftLeg' => $this->visible_faces['leftLeg']['front']);
                }
            }
            
            return $display_order;
        }
    }
    
    /* Img class
     *
     * Handels image related things
     */
    class img {
        private function __construct() {
        }
        
        /* Function creates a blank canvas
         * with transparancy with the size of the
         * given image.
         * 
         * Espects canvas with and canvast height.
         * Returns a empty canvas.
         */
        public static function createEmptyCanvas($w, $h) {
            $dst = imagecreatetruecolor($w, $h);
            imagesavealpha($dst, true);
            $trans_colour = imagecolorallocatealpha($dst, 255, 255, 255, 127);
            imagefill($dst, 0, 0, $trans_colour);
            
            return $dst;
        }
        
        /* Function converts a non true color image to
         * true color. This fixes the dark blue skins.
         * 
         * Espects an image.
         * Returns a true color image.
         */
        public static function convertToTrueColor($img) {
            if(imageistruecolor($img)) {
                return $img;
            }

            $dst = img::createEmptyCanvas(imagesx($img), imagesy($img));
        
            imagecopy($dst, $img, 0, 0, 0, 0, imagesx($img), imagesy($img));
            imagedestroy($img);

            return $dst;
        }
    }
        
    /* Point Class
     *
     */
    class Point {
        private $_originCoord;
        private $_destCoord = array();
        private $_isProjected = false;
        private $_isPreProjected = false;
        
        public function __construct( $originCoord ) {
            if ( is_array( $originCoord ) && count( $originCoord ) == 3 ) {
                $this->_originCoord = array(
                    'x' => ( isset( $originCoord[ 'x' ] ) ? $originCoord[ 'x' ] : 0 ),
                    'y' => ( isset( $originCoord[ 'y' ] ) ? $originCoord[ 'y' ] : 0 ),
                    'z' => ( isset( $originCoord[ 'z' ] ) ? $originCoord[ 'z' ] : 0 ) 
                );
            } else {
                $this->_originCoord = array(
                    'x' => 0,
                    'y' => 0,
                    'z' => 0 
                );
            }
        }
        
        public function project() {
            global $cos_alpha, $sin_alpha, $cos_omega, $sin_omega;
            global $minX, $maxX, $minY, $maxY;

            // 1, 0, 1, 0
            $x = $this->_originCoord['x'];
            $y = $this->_originCoord['y'];
            $z = $this->_originCoord['z'];
            $this->_destCoord['x'] = $x * $cos_omega + $z * $sin_omega;
            $this->_destCoord['y'] = $x * $sin_alpha * $sin_omega + $y * $cos_alpha - $z * $sin_alpha * $cos_omega;
            $this->_destCoord['z'] = -$x * $cos_alpha * $sin_omega + $y * $sin_alpha + $z * $cos_alpha * $cos_omega;
            $this->_isProjected = true;
            $minX = min($minX, $this->_destCoord['x']);
            $maxX = max($maxX, $this->_destCoord['x']);
            $minY = min($minY, $this->_destCoord['y']);
            $maxY = max($maxY, $this->_destCoord['y']);
        }
        
        public function preProject( $dx, $dy, $dz, $cos_alpha, $sin_alpha, $cos_omega, $sin_omega ) {
            if ( !$this->_isPreProjected ) {
                $x                         = $this->_originCoord[ 'x' ] - $dx;
                $y                         = $this->_originCoord[ 'y' ] - $dy;
                $z                         = $this->_originCoord[ 'z' ] - $dz;
                $this->_originCoord[ 'x' ] = $x * $cos_omega + $z * $sin_omega + $dx;
                $this->_originCoord[ 'y' ] = $x * $sin_alpha * $sin_omega + $y * $cos_alpha - $z * $sin_alpha * $cos_omega + $dy;
                $this->_originCoord[ 'z' ] = -$x * $cos_alpha * $sin_omega + $y * $sin_alpha + $z * $cos_alpha * $cos_omega + $dz;
                $this->_isPreProjected     = true;
            }
        }
        
        public function getOriginCoord() {
            return $this->_originCoord;
        }
        
        public function getDestCoord() {
            return $this->_destCoord;
        }
        
        public function getDepth() {
            if ( !$this->_isProjected ) {
                $this->project();
            }
            return $this->_destCoord[ 'z' ];
        }
        
        public function isProjected() {
            return $this->_isProjected;
        }
    }
    
    /* Polygon Class
     *
     */
    class Polygon {
        private $_dots;
        private $_colour;
        private $_isProjected = false;
        private $_face = 'w';
        private $_faceDepth = 0;
        
        public function __construct( $dots, $colour ) {
            $this->_dots   = $dots;
            $this->_colour = $colour;
            $coord_0       = $dots[ 0 ]->getOriginCoord();
            $coord_1       = $dots[ 1 ]->getOriginCoord();
            $coord_2       = $dots[ 2 ]->getOriginCoord();
            if ( $coord_0[ 'x' ] == $coord_1[ 'x' ] && $coord_1[ 'x' ] == $coord_2[ 'x' ] ) {
                $this->_face      = 'x';
                $this->_faceDepth = $coord_0[ 'x' ];
            } else if ( $coord_0[ 'y' ] == $coord_1[ 'y' ] && $coord_1[ 'y' ] == $coord_2[ 'y' ] ) {
                $this->_face      = 'y';
                $this->_faceDepth = $coord_0[ 'y' ];
            } else if ( $coord_0[ 'z' ] == $coord_1[ 'z' ] && $coord_1[ 'z' ] == $coord_2[ 'z' ] ) {
                $this->_face      = 'z';
                $this->_faceDepth = $coord_0[ 'z' ];
            }
        }
        
        // never used
        private function getFace() {
            return $this->_face;
        }
        
        // never used
        private function getFaceDepth() {
            if ( !$this->_isProjected ) {
                $this->project();
            }
            return $this->_faceDepth;
        }
        
        public function getSvgPolygon( $ratio ) {
            $points_2d = '';
            $r         = ( $this->_colour >> 16 ) & 0xFF;
            $g         = ( $this->_colour >> 8 ) & 0xFF;
            $b         = $this->_colour & 0xFF;
            $vR        = ( 127 - ( ( $this->_colour & 0x7F000000 ) >> 24 ) ) / 127;
            if ( $vR == 0 )
                return '';
            foreach ( $this->_dots as $dot ) {
                $coord = $dot->getDestCoord();
                $points_2d .= $coord[ 'x' ] * $ratio . ',' . $coord[ 'y' ] * $ratio . ' ';
            }
            $comment = '';
            return $comment . '<polygon points="' . $points_2d . '" style="fill:rgba(' . $r . ',' . $g . ',' . $b . ',' . $vR . ')" />' . "\n";
        }
        
        public function addPngPolygon( &$image, $minX, $minY, $ratio ) {
            $points_2d = array();
            $nb_points = 0;
            $r         = ( $this->_colour >> 16 ) & 0xFF;
            $g         = ( $this->_colour >> 8 ) & 0xFF;
            $b         = $this->_colour & 0xFF;
            $vR        = ( 127 - ( ( $this->_colour & 0x7F000000 ) >> 24 ) ) / 127;
            if ( $vR == 0 )
                return;
            $same_plan_x = true;
            $same_plan_y = true;
            foreach ( $this->_dots as $dot ) {
                $coord = $dot->getDestCoord();
                if ( !isset( $coord_x ) )
                    $coord_x = $coord[ 'x' ];
                if ( !isset( $coord_y ) )
                    $coord_y = $coord[ 'y' ];
                if ( $coord_x != $coord[ 'x' ] )
                    $same_plan_x = false;
                if ( $coord_y != $coord[ 'y' ] )
                    $same_plan_y = false;
                $points_2d[] = ( $coord[ 'x' ] - $minX ) * $ratio;
                $points_2d[] = ( $coord[ 'y' ] - $minY ) * $ratio;
                $nb_points++;
            }
            if ( !( $same_plan_x || $same_plan_y ) ) {
                $colour = imagecolorallocate( $image, $r, $g, $b );
                imagefilledpolygon( $image, $points_2d, $nb_points, $colour );
            }
        }
        
        public function isProjected() {
            return $this->_isProjected;
        }
        
        public function project() { 
            foreach ( $this->_dots as &$dot ) {
                if ( !$dot->isProjected() ) {
                    $dot->project();
                }
            }
            $this->_isProjected = true;
        }
        
        public function preProject( $dx, $dy, $dz, $cos_alpha, $sin_alpha, $cos_omega, $sin_omega ) {
            foreach ( $this->_dots as &$dot ) {
                $dot->preProject( $dx, $dy, $dz, $cos_alpha, $sin_alpha, $cos_omega, $sin_omega );
            }
        }
    }
?>
