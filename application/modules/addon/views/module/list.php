<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<link href="<?php echo base_url('application/modules/addon/assets/css/style.css'); ?>" rel="stylesheet"
    type="text/css" />
<!-- Add new customer start -->



<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="card panel-bd">
            <div class="card-header">
                <div class="panel-title box-header">
                    <h4><?php echo (!empty($title)?$title:"Module List") ?></h4>
                    <div>
                        <a href="<?php echo base_url('addon/module/download_module')?>"
                            class="btn btn-success m-b-5 m-r-2"><i class="ti-download"> </i>
                            <?php echo display('download')?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">

                <?php echo form_open_multipart("addon/module/upload/") ?>
                <div class="row">
                    <div class="col-sm-3">
                        <label> <!-- <?php echo display('purchase_key') ?> -->Access code <span class="glyphicon glyphicon-question-sign"
                                data-toggle="tooltip" data-placement="bottom"
                                title="Enter access code shared by CodeAir"></span></label>
                        <input type="text" name="purchase_key" placeholder="Type the code here"
                            class="form-control" required />
                    </div>

                    <div class="col-sm-3">
                        <label class="form-label" for="module"><?php echo display('module') ?> (.zip | .rar |
                            .gz)</label>
                        <input type="file" name="module" class="form-control" required>
                    </div>
                    <div class="col-sm-6 themeupload">
                        <div class="pull-left overwrite">
                                <button type="submit" class="btn btn-success ml-2"><?php echo display('add_module') ?></button>
                        </div>
                    </div>
                </div>
                <?php echo form_close() ?>
                <hr />

                <div class="row">
                    <?php if(!empty($live_modules)){
                                    foreach ($live_modules as $livemod) {
                                        if(!in_array($livemod->identity, $downloaded)){
                                 ?>

                    <?php } } } ?>
                    <!-- display list of downloaded module without Default Modules -->
                    <?php
                                $path = 'application/modules/';
                                $map  = directory_map($path);
                                $def_mods = ['dashboard','accounts','customer','hrm','payment_setting','purchase','reports','room_facilities','room_reservation','room_setting','tax_management','units','addon','template'];
                                if (is_array($map))
                                //extract each directory 
                                foreach ($map as $key => $value) {
                                    $key = str_replace("\\", '/', $key);
                                    $mod = str_replace("/", '', $key);
                                    
                                    //chek directory is not default modules
                                    if ($value != "index.html" && !in_array($mod, $def_mods)) {
                                        // set the default config path
                                        $file = $path.$key.'config/config.php';
                                        $image = $path.$key.'assets/images/thumbnail.jpg';
                                        $css  = $path.$key.'assets/css/style.css';
                                        $js   = $path.$key.'assets/js/script.js';
                                        $db   = $path.$key.'assets/data/database.sql';
                                        $delMod = ((!is_array($value) && $value!='index.html')?$value:(is_array($value)?$mod:null)); 
                                        //check database.sql and config.php 
                                        if (file_exists($file) && file_exists($db) && file_exists($image)) {
                                            @include($file);
                                        //check the setting of config.php
                                        if (isset($HmvcConfig[$mod])
                                            && is_array($HmvcConfig[$mod])
                                            && array_key_exists('_title', $HmvcConfig[$mod])
                                            && $HmvcConfig[$mod]['_title'] != ''
                                            && array_key_exists('_database', $HmvcConfig[$mod])
                                            && array_key_exists('_description', $HmvcConfig[$mod]) 
                                            && $HmvcConfig[$mod]['_description'] != ''
                                            ) {
                                            //form to module 
                                        ?>
                    <!-- display module information -->
                    <div class="col-md-4 addonsbox">
                        <?php
                        echo form_open('addon/module/install');
                        echo form_hidden('name',$HmvcConfig[$mod]['_title']);
                        echo form_hidden('image',$image);
                        echo form_hidden('directory',$mod);
                        echo form_hidden('description',$HmvcConfig[$mod]['_description']);
                        ?>
                        <div class="thumbnail">
                            <div class="addon_img">
                                <img src="<?php echo base_url('application/modules/'.$mod.'/assets/images/thumbnail.jpg') ?>"
                                    alt="" class="mod_thumb_img">
                            </div>
                            <div class="caption">
                                <h3><?php echo html_escape(($HmvcConfig[$mod]['_title']!=null)?$HmvcConfig[$mod]['_title']:null) ?>
                                </h3>
                                <p class="caption_desc">
                                    <?php echo html_escape(($HmvcConfig[$mod]['_description']!=null)?$HmvcConfig[$mod]['_description']:null) ?>
                                </p>
                                <p>
                                    <?php 
													$mkey = array_search($mod, array_column($live_modules, 'identity'));
														   if(($live_modules[$mkey]->identity==$mod) && ($live_modules[$mkey]->version>$HmvcConfig[$mod]['_version'])){
											 			?>
                                    <a onclick="return confirm('<?php echo display("are_you_sure") ?>')"
                                        href="<?php echo base_url("addon/module/updatemodule/$delMod/") ?>"
                                        class="btn btn-success"><?php echo display("update") ?></a>
                                    <?php }
                                    // desktop
                          
                                    if ($mod == 'hrmscheduler') {
                                        if(array_key_exists('_zip_download', $HmvcConfig[$mod]) &&  $HmvcConfig[$mod]['_zip_download'] == TRUE){
                                        ?>
                                            <a onclick="return confirm('<?php echo display('are_you_sure') ?>')"  
                                        class="btn btn-success" href="<?php echo base_url($mod.'/hrmscheduler/zip_download?module='.$mod.'&is_download=yes&downloadas=zip&downloadid='.md5('BDT'.$mod)) ?>" >
                                        <?php echo display("download") ?></a>
                                        <?php } }
                                        // desktop
                                                    $rows = null;
                                                    $rows = $this->db->select("*")
                                                        ->from('module')
                                                        ->where('directory', $mod)
                                                        ->get(); 
                                                    if ($rows->num_rows() > 0) { 
                                                    ?>
                                    <a onclick="return confirm('<?php echo display("are_you_sure").", You will lost your transactional records of this module" ?>')"
                                        href="<?php echo base_url("addon/module/uninstall/$delMod") ?>"
                                        class="btn btn-danger"><?php echo display("uninstall") ?></a>
                                    <?php } else { 
													if(array_key_exists('_zip_download', $HmvcConfig[$mod]) != true){
													?>
                                    <button onclick="return confirm('<?php echo display("are_you_sure") ?>')"
                                        type="submit" class="btn btn-success"
                                        role="button"><?php echo display("install") ?></button>
                                    <?php } } ?>
                                    <a href="<?php echo base_url("addon/module/uninstall/$delMod/delete") ?>"
                                        type="submit"
                                        class="btn btn-danger delete_item"><?php echo display("delete") ?></a>
                                    <?php if ($mod == 'house_keeping') { ?>
                                        <a id="app_redirect"
                                            class="btn btn-primary float-right"><?php echo display("install")." ".display("application_form") ?></a>
                                    <?php } ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php 
                                            echo form_close();
                                        } else {
                                        ?>
                    <!-- if module config.php configuration missing -->
                    <div class="col-md-4  addonsbox">
                        <div class="thumbnail">
                            <h3><?php echo display("invalid_module") ?> "<?php echo $mod ?>" </h3>
                            <div class="caption text-danger">
                                <h4>Missing config.php</h4>
                                <ul class="pl_10">
                                    <?php 
                                                    if (isset($HmvcConfig[$mod])) {
                                                        if (!array_key_exists('_title', $HmvcConfig[$mod]) || $HmvcConfig[$mod]['_title'] == null) {
                                                            echo '<li>$HmvcConfig["'.$mod.'"]["_title"]</li>';
                                                        }      
                                                        if (!array_key_exists('_description', $HmvcConfig[$mod]) || $HmvcConfig[$mod]['_description'] == null) {
                                                            echo '<li>$HmvcConfig["'.$mod.'"]["_description"]</li>';
                                                        }   
                                                    } else {
                                                        echo '<li>$HmvcConfig["'.$mod.'"] is not define</li>';
                                                    }
                                                    ?>

                                    <li></li>
                                </ul>
                            </div>
                            <p><a href="<?php echo base_url("addon/module/uninstall/$delMod/delete") ?>" type="submit"
                                    class="btn btn-danger delete_item"><?php echo display("delete") ?></a></p>
                        </div>
                    </div>
                    <?php

                                            }
                                            // ends of check the setting of config.php

                                        } else { 
                                        ?>
                    <!-- if module config.php or database.sql is not found -->
                    <div class="col-md-4  addonsbox">
                        <div class="thumbnail">
                            <h3><?php echo display("invalid_module") ?> "<?php echo $delMod ?>"</h3>
                            <div class="caption text-danger">
                                <h4>Missing</h4>
                                <ul class="pl_10">
                                    <?php 
                                                        if (!file_exists($file)) {
                                                            echo "<li>config/config.php</li>";
                                                        } 
                                                        if (!file_exists($db)) {
                                                            echo "<li>assets/data/database.sql</li>";
                                                        }  
                                                        if (!file_exists($image)) {
                                                            echo "<li>assets/images/thumbnail.jpg</li>";
                                                        } 
                                                        if (!file_exists($css)) {
                                                            echo "<li>assets/css/style.css</li>";
                                                        } 
                                                        if (!file_exists($js)) {
                                                            echo "<li>assets/js/script.js</li>";
                                                        }    
                                                        ?>
                                </ul>
                            </div>
                            <p><a href="<?php echo base_url("addon/module/uninstall/$delMod/delete") ?>" type="submit"
                                    class="btn btn-danger delete_item"><?php echo display("delete") ?></a></p>
                        </div>
                    </div>
                    <?php 
                                        }
                                    }
                                }   
                                ?>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url().'application/modules/addon/assets/ajaxs/addons/module.js' ?>"></script>