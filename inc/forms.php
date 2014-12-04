    <div id="plugins_container">
        <h2>Mass plugin installer</h2>
        <div class="plugins_entry">
            <form method="POST" id="install_plugins">
            
            <?php
                $file_address = plugins_url('/js/plugins.json',__FILE__);
                $json_file      = file_get_contents($file_address);
                $plugins_array  = json_decode($json_file, true);
                $plugins_array  = $plugins_array['plugins'];
                $pnumber = count($plugins_array);
                for($i = 0; $i < $pnumber; $i++):
                    foreach($plugins_array[$i] as $key => $value):
                        if($key == 'name'):
            ?>
                <label>
                    <input type="checkbox" name="<?php echo $value; ?>"/>
                    <span class="forcheckbox"><?php echo $value; ?></span>
                </label>
            <?php
                        endif;
                    endforeach;
                endfor;
            ?>

                <button>Install</button>

            </form>
        </div>
    </div>

<script>
        jQuery('#install_plugins').on('submit',function() {
        var that    =   jQuery(this);
            contents    =   that.serialize();
            // Split $_POST to an array
            masoud = contents.split('&');

            if( masoud.length >= 1 && masoud['0'] != "" ) {
                jQuery('form').hide();
                jQuery('#plugins_container').append('<li>Installing the plugins, Please wait! </li>');

                // Send data's to Getfile.php
                for( i = 0; i < masoud.length; i++) {
                    data = masoud[i];
                    data = {
                                'data': data,
                                'action': 'wp_plugins_ajax'
                           };
                    if( data != "") {
                        jQuery.ajax({
                            url: '<?php echo admin_url(); ?>admin-ajax.php',
                            dataType: 'json',
                            type: 'post',
                            data: data,
                            
                            beforeSend: function() {
                                console.log(data);
                            },
                            success: function(data) {
                                jQuery('#plugins_container').append('<li>' + data + '</li>');
                                console.log(data);
                            }
                        }); // End if Ajax Method
                    }
                    
                } // end of For loop
                return false;
            }
    });
</script>
