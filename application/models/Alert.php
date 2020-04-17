<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class alert extends CI_Model {

    public function css(){
        echo("<link href='".base_url()."vendors/pnotify/dist/pnotify.css' rel='stylesheet'>\n");
        echo("<link href='".base_url()."vendors/pnotify/dist/pnotify.buttons.css' rel='stylesheet'>\n");
        echo("<link href='".base_url()."vendors/pnotify/dist/pnotify.nonblock.css' rel='stylesheet'>\n");
    }
    public function coa($status){
        $success = 'Data Berhasil di Tambah';
        $failed = 'Data Gagal di Tambah Karena Data';
        if($status == 'success'){
            echo(
                "<script>
                    new PNotify({
                        title: 'Berhasil',
                        text: '$success',
                        type: 'success',
                        styling: 'bootstrap3'
                    });
                </script>"                
            );
        }elseif($status == 'double'){

        }elseif($status == 'pt'){
            
        }elseif($status == 'coa'){
            
        }
    }
}