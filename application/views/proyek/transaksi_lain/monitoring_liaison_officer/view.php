<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>

<div style="float:right">
    <h2>
        <button class="btn btn-warning" onClick="window.history.back()" disabled>
            <i class="fa fa-arrow-left"></i>
            Back
        </button>
        <button class="btn btn-success" onClick="window.location.href='<?=site_url()?>/transaksi_lain/p_monitoring_liaison_officer'">
            <i class="fa fa-repeat"></i>
            Refresh
        </button>
    </h2>
</div>
<div class="clearfix"></div>
</div>
    <div class="x_content">
      <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">
            <table class="table table-striped jambo_table bulk_action tableDT">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Unit</th>
                            <th>Customer</th>
                            <th>Paket LOI</th>
                            <th>Nomor Registrasi</th>
                            <!-- <th>Deposit</th> -->
                            <!-- <th>Status Bayar</th> -->
                            <th>Status Dokumen</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                <tbody>
                <?php
                    $i = 0;
                    foreach($data as $v){
                      ++$i;
                      echo "<tr>";
                      echo "<td>$i</td>";
                      echo "<td>$v[unit]</td>";
                      echo "<td>$v[customer_name]</td>";
                      echo "<td>$v[nama_paket]</td>";
                      echo "<td>$v[nomor_registrasi]</td>";
                      // echo "<td>".number_format($v['deposit_masuk'])."</td>";
                      // if($v['status_bayar']=='1'){
                      //   echo "<td><span class='label label-success'>Sudah Bayar</span></td>";
                      // }else{
                      //   echo "<td><span class='label label-danger'>Belum Bayar</span></td>";
                      // }
                      if($v['status_dokumen']=='1'){
                        echo "<td><span class='label label-danger'>Belum Survei</span></td>";
                      }elseif($v['status_dokumen']=='2' || $v['status_dokumen'] == '3' || $v['status_dokumen'] == '6'){
                        echo "<td><span class='label label-danger'>Belum Pelaksanaan</span></td>";
                      }elseif($v['status_dokumen']=='4' || $v['status_dokumen'] == '7' || $v['status_dokumen'] == '9' || $v['status_dokumen'] == '8'){
                        echo "<td><span class='label label-danger'>Belum Serah Terima</span></td>";
                      }elseif($v['status_dokumen']=='5'){
                        echo "<td><span class='label label-success'>Complete</span></td>";
                      }
                      echo "<td>";
                      if($v['status_dokumen']== '2'){
                      echo "<a title='Edit' href='".site_url()."/transaksi_lain/P_monitoring_liaison_officer/tambahBiaya?id=$v[id]' class='btn btn-xs btn-primary'>
                              <i class='fa fa-edit'></i>
                            </a>";
                      }elseif($v['status_dokumen'] == '9'){
                        echo "<a title='Edit2' href='".site_url()."/transaksi_lain/P_monitoring_liaison_officer/tambahBiaya2?id=$v[id]' class='btn btn-xs btn-primary'>
                              <i class='fa fa-edit'></i>
                            </a>";
                      }
                      // $total = $v['deposit_masuk'] - $v['deposit_pemakaian'];
                      echo "<a title='Detail' class='btn btn-xs btn-primary' data-toggle='modal' data-target='#modal$v[id]'>
                              <i class='fa fa-search'></i>
                            </a>";
                      echo "</td>";
                      echo "</tr>";  
                ?>
                <div id="modal<?=$v['id']?>" class="modal fade" data-backdrop="static" data-keyboard="false" style="width:100vw" role="dialog" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Detail Monitoring</h4>
                      </div>
                      <div class="modal-body">
                          <ul class="list-unstyled timeline">
                            <li>
                              <div class="block">
                                <div class="tags">
                                  <a href="" class="tag" style="background-color:#004d1a;">
                                    <font color="white">Registrasi</font>
                                  </a>
                                </div>
                                <div class="block_content">
                                  <h2 class="title">
                                    <a>Nomor Registrasi : <?= $v['nomor_registrasi']?></a>
                                  </h2>
                                  <h5>
                                    <a>Tanggal Registrasi : <?= $v['tanggal_document']?></a><br><br>
                                      <a>Pembayaran Deposit : <?php 
                                       echo ($v['status_tagihan1']==0)? " <span class='label label-warning'>Belum Bayar</span>" : " <span class='label label-success'>Lunas</span> ";
                                       
                                       ?></a>
                                  </h5>
                                </div>
                              </div>
                            </li>
                            <li>
                              <div class="block">
                                <div class="tags">
                                  <?php if($v['status_dokumen'] == 1){?>
                                    <a href="" class="tag" style="background-color:#cc0000;">
                                      <font color="white">Survei</font>
                                    </a>
                                  <?php }elseif($v['status_dokumen'] >= 2){?>
                                    <a href="" class="tag" style="background-color:#004d1a;">
                                      <font color="white">Survei</font>
                                    </a>
                                  <?php }?>
                                </div>
                                <div class="block_content">
                                  <?php if($v['status_dokumen'] == 1){?>
                                    <h2 class="title">
                                      <a>Nomor Registrasi : <?= $v['nomor_registrasi']?></a>
                                    </h2>
                                    <h5>
                                      <a>Tanggal Rencana Survei : <?= $v['tanggal_rencana_survei']?></a>
                                    </h5>
                                  <?php }elseif($v['status_dokumen'] >= 2){?>
                                    <h2 class="title">
                                      <a>Nomor Registrasi : <?= $v['nomor_registrasi']?></a>
                                      
                                    </h2>
                                    <h5>
                                      <a>Tanggal Rencana Survei : <?= $v['tanggal_rencana_survei']?></a>
                                    </h5>
                                    <h5>
                                      <a>Tanggal Survei : <?= $v['tanggal_survei']?></a>
                                    </h5>
                                    <h5>
                                      <a>Kekurangan Bayar Survei 1 : <?php $sisa_deposit = $v['deposit_masuk']-$v['deposit_pemakaian'];
                                       echo ($sisa_deposit<0)?($sisa_deposit*-1):0;
                                       echo ($v['status_tagihan2']==0&&$sisa_deposit<0)? " <span class='label label-warning'>Belum Bayar</span>" : " <span class='label label-success'>Lunas</span> ";
                                       
                                       ?></a><br><br>
                                       <a>Kekurangan Bayar Survei 2 : <?php $sisa_deposit = ($v['deposit_masuk']-$v['deposit_pemakaian'])-$v['deposit_pemakaian2'];
                                       echo ($sisa_deposit<0)?$v['deposit_pemakaian2']:0;
                                       echo ($v['status_tagihan3']==0&&$sisa_deposit<0)? " <span class='label label-warning'>Belum Bayar</span>" : " <span class='label label-success'>Lunas</span> ";
                                       
                                       ?></a>
                                    </h5>
                                  <?php }?>
                                </div>
                              </div>
                            </li>
                            <li>
                              <div class="block">
                                <div class="tags">
                                  <?php if($v['status_dokumen'] <= 3 || $v['status_dokumen'] == 6){?>
                                    <a href="" class="tag" style="background-color:#cc0000;">
                                      <font color="white">Pelaksanaan</font>
                                    </a>
                                  <?php }elseif($v['status_dokumen'] >= 3){?>
                                    <a href="" class="tag" style="background-color:#004d1a;">
                                      <font color="white">Pelaksanaan</font>
                                    </a>
                                  <?php }?>
                                </div>
                                <div class="block_content">
                                  <?php if($v['status_dokumen'] <= 3 || $v['status_dokumen'] == 6){?>
                                    <h2 class="title">
                                      <a>Nomor Registrasi : <?= $v['nomor_registrasi']?></a>
                                    </h2>
                                    <h5>
                                      <a>Tanggal Rencana Pelaksanaan : <?= $v['tanggal_rencana_pemasangan']?></a>
                                    </h5>
                                  <?php }elseif($v['status_dokumen'] >= 3){?>
                                    <h2 class="title">
                                      <a>Nomor Registrasi : <?= $v['nomor_registrasi']?></a>
                                    </h2>
                                    <h5>
                                      <a>Tanggal Rencana Pelaksanaan : <?= $v['tanggal_rencana_pemasangan']?></a>
                                    </h5>
                                    <h5>
                                      <a>Tanggal Pelaksanaan : <?= $v['tanggal_instalasi']?></a>
                                    </h5>
                                  <?php }?>
                                </div>
                              </div>
                            </li>
                            <li>
                              <div class="block">
                                <div class="tags">
                                  <?php if($v['status_dokumen'] <= 4 || $v['status_dokumen'] == 6){?>
                                    <a href="" class="tag" style="background-color:#cc0000;">
                                      <font color="white">ST</font>
                                    </a>
                                  <?php }elseif($v['status_dokumen'] == 5){?>
                                    <a href="" class="tag" style="background-color:#004d1a;">
                                      <font color="white">ST</font>
                                    </a>
                                  <?php }?>
                                </div>
                                <div class="block_content">
                                  <?php if($v['status_dokumen'] <= 4 || $v['status_dokumen'] == 6){?>
                                    <h2 class="title">
                                      <a>Nomor Registrasi : <?= $v['nomor_registrasi']?></a>
                                    </h2>
                                    <h5>
                                      <a>Tanggal Rencana Serah Terima : <?= $v['tanggal_rencana_aktifasi']?></a>
                                    </h5>
                                  <?php }elseif($v['status_dokumen'] == 5){?>
                                    <h2 class="title">
                                      <a>Nomor Registrasi : <?= $v['nomor_registrasi']?></a>
                                    </h2>
                                    <h5>
                                      <a>Tanggal Rencana Serah Terima : <?= $v['tanggal_rencana_aktifasi']?></a>
                                    </h5>
                                    <h5>
                                      <a>Tanggal Serah Terima : <?= $v['tanggal_aktifasi']?></a>
                                    </h5>
                                  <?php }?>
                                </div>
                              </div>
                            </li>
                          </ul>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>
                <?php }?>

                </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
    function notif(title, text, type) {
        new PNotify({
            title: title,
            text: text,
            type: type,
            styling: 'bootstrap3'
        });
    }
    function confirm_modal(id) {
        jQuery('#modal_delete_m_n').modal('show', {
            backdrop: 'static',
            keyboard: false
        });
        document.getElementById('delete_link_m_n').setAttribute("href", "<?= site_url('/transaksi_lain/P_registrasi_tvi/delete?id="+id+"'); ?>");
        document.getElementById('delete_link_m_n').focus();
    }

    function upload_modal(id) {
        jQuery('#modal_upload').modal('show', {
            backdrop: 'static',
            keyboard: false,
            contentType:false
        });
        document.getElementById('button-modal-upload').setAttribute("href", "<?= site_url('/transaksi_lain/P_registrasi_tvi/upload?id="+id+"'); ?>");
        document.getElementById('button-modal-upload').focus();
    }

</script>
