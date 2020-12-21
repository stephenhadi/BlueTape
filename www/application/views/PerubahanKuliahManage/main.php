<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!doctype html>
<html class="no-js" lang="en">
    <?php $this->load->view('templates/head_loggedin'); ?>
    <body>
        <?php $this->load->view('templates/topbar_loggedin'); ?>
        <?php $this->load->view('templates/flashmessage'); ?>
        <br>
        <div class="container">
        <div class="card">
                <div class="card-header">   
                    <div class="row">
                        <div class = "col">                 
                            Statistik Perubahan Kuliah
                        </div>
                        <div class= "col">
                            <a class ="float-right" data-toggle="collapse" data-target="#statistikPerubahanKuliah">
                                <i class="fas fa-angle-double-down" style="color:black;"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="collapse" id = "statistikPerubahanKuliah">
                    <div class="card-body">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#" id="byYear">Statistik Berdasarkan Tahun</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#" id="byDay">ho</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#">he</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane show active text-center">                            
                                <canvas id="chartStatistic" style="width:100%"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="card">
                <div class="card-header">
                    Permohonan Perubahan Kuliah
                </div>
                <br>
                <div class="card-body table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Status</th>
                            <th scope="col">Tanggal Permohonan</th>
                            <th scope="col">Kode MK</th>
                            <th scope="col">Perubahan</th>
                            <th scope="col">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($requests as $request): ?>
                            <tr>
                                <th>#<?= $request->id ?></th>
                                <td><span class="badge badge-<?= $request->labelClass ?>"><?= $request->status ?></span></td>
                                <td><time datetime="<?= $request->requestDateTime ?>"><?= $request->requestDateString ?></time></td>
                                <td><?= $request->mataKuliahCode ?></td>
                                <td><?= PerubahanKuliah_model::CHANGETYPE_TYPES[$request->changeType] ?></td>
                                <td>
                                    <a data-toggle="modal" data-target="#detail<?= $request->id ?>" id="detailIkon<?= $request->id ?>"><i class="fas fa-eye blueiconcolor"></i></a>
                                    <a target="_blank" href="/PerubahanKuliahManage/printview/<?= $request->id ?>"><i class="fas fa-print"></i></a>
                                    <a data-toggle="modal" data-target="#konfirmasi<?= $request->id ?>"><i class="fas fa-thumbs-up"></i></a>
                                    <a data-toggle="modal" data-target="#tolak<?= $request->id ?>"><i class="fas fa-thumbs-down"></i></a>
                                    <a data-toggle="modal" data-target="#hapus<?= $request->id ?>"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php if ($numOfPages > 1): ?>
                    <ul class="pagination justify-content-center" role="navigation" aria-label="Pagination">
                        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                            <?php if ($i === $page): ?>
                                <li class="current page-item active"><span class="page-link"><?= $i ?></span></li>
                            <?php else: ?>
                                <li class = "page-item"><a href="?page=<?= $i ?>"  aria-label="Halaman <?= $i ?>" class="page-link"><?= $i ?></a></li>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </ul>
                <?php endif; ?>
                <?php foreach ($requests as $request): ?>
                    <div class="modal fade" id="detail<?= $request->id ?>" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Detail Permohonan #<?= $request->id ?></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-striped">
                                        <tbody>
                                        <tr>
                                            <th>E-mail Pemohon</th>
                                            <td><?= $request->requestByEmail ?></td>
                                        </tr>
                                        <tr>
                                            <th>Nama Pemohon</th>
                                            <td><?= $request->requestByName ?></td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Permohonan</th>
                                            <td><?= $request->requestDateTime ?></td>
                                        </tr>
                                        <tr>
                                            <th>Kode Mata Kuliah</th>
                                            <td><?= $request->mataKuliahCode ?></td>
                                        </tr>
                                        <tr>
                                            <th>Nama Mata Kuliah</th>
                                            <td><?= $request->mataKuliahName ?></td>
                                        </tr>
                                        <tr>
                                            <th>Kelas</th>
                                            <td><?= $request->class ?></td>
                                        </tr>
                                        <tr>
                                            <th>Jenis Perubahan</th>
                                            <td><?= PerubahanKuliah_model::CHANGETYPE_TYPES[$request->changeType] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Dari Hari/Jam</th>
                                            <td><time datetime="<?= $request->fromDateTime ?>"><?= $request->fromDateTime ?></time></td>
                                        </tr>
                                        <tr>
                                            <th>Dari Ruang</th>
                                            <td><?= $request->fromRoom ?></td>
                                        </tr>
                                        <?php foreach (json_decode($request->to) as $to ): ?>
                                            <tr>
                                                <th>Menjadi Hari/Jam</th>
                                                <td><time datetime="<?= $to->dateTime ?>"><?= $to->dateTime ?></time>
                                                <?= empty($to->toTimeFinish)? '': '- <time datetime="'.$to->toTimeFinish.'">'.$to->toTimeFinish.'</time>'?></td>
                                            </tr>
                                            <tr>
                                                <th>Menjadi Ruang</th>
                                                <td><?= $to->room ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <tr>
                                            <th>Keterangan</th>
                                            <td><?= $request->remarks ?></td>
                                        </tr>
                                        <tr>
                                            <th>E-mail Penjawab</th>
                                            <td><?= $request->answeredByEmail ?></td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Dijawab</th>
                                            <td><?= $request->answeredDateTime ?></td>
                                        </tr>
                                        <tr>
                                            <th>Keterangan Penjawab</th>
                                            <td><?= $request->answeredMessage ?></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="konfirmasi<?= $request->id ?>" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Konfirmasi Permohonan #<?= $request->id ?></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="/PerubahanKuliahManage/answer">
                                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
                                        <input type="hidden" name="id" value="<?= $request->id ?>"/>
                                        <input type="hidden" name="answer" value="confirmed"/>
                                        <div class="form-group">
                                            <label>Email penjawab:</label>
                                            <input class="form-control" type="text" value="<?= $answeredByEmail ?>" readonly="true"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Keterangan:</label>
                                            <input class="form-control" name="answeredMessage" class="input-group-field" type="text"/>
                                        </div>
                                        <p>&nbsp;</p>
                                        <div class="form-group">
                                            <input type="submit" class="btn btn-success" value="Konfirmasi"/>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="tolak<?= $request->id ?>" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Tolak Permohonan #<?= $request->id ?></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="/PerubahanKuliahManage/answer">
                                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
                                        <input type="hidden" name="id" value="<?= $request->id ?>"/>
                                        <input type="hidden" name="answer" value="rejected"/>
                                        <div class="form-group">
                                            <label>Email penjawab:</label>
                                            <input class="form-control" type="text" value="<?= $answeredByEmail ?>" readonly="true"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Alasan penolakan:</label>
                                            <input class="form-control" name="answeredMessage" class="input-group-field" type="text" required/>
                                        </div>
                                        <p>&nbsp;</p>
                                        <div class="form-group">
                                            <input type="submit" class="btn btn-danger" value="Tolak"/>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="hapus<?= $request->id ?>" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Hapus Permohonan #<?= $request->id ?></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="/PerubahanKuliahManage/remove">
                                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
                                        <input type="hidden" name="id" value="<?= $request->id ?>"/>
                                        <input type="hidden" name="answer" value="remove"/>
                                        <p><strong>Yakin ingin menghapus?</strong></p>
                                        <p>Data akan hilang selamanya dari catatan. Biasanya menghapus tidak diperlukan, cukup menolak atau mencetak.</p>
                                        <input type="submit" class="btn btn-danger" value="Hapus"/>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php $this->load->view('templates/script_foundation'); ?>
            </div>
        </div>
        <script>
            $(document).ready(function () {
                var perubahanKuliahChart;                
                var canvascontainer = $('#chartStatistic');
                var context = canvascontainer[0].getContext('2d');   

                $('#statistikPerubahanKuliah a').on('click',function(e){
                    e.preventDefault()
                    if($(this).attr('id')==='byDay'){
                        <?php 
                        $dayLabel='';
                        $diganti='';
                        $tambahan='';
                        $ditiadakan='';   
                        foreach($statistic->requestByDay as $key => $row){
                            
                            $dayLabel .= '"'.$row->month_day.'",';         
                            
                            $perubahan = strtolower(PerubahanKuliah_model::CHANGETYPE_TYPES[$row->changeType]);
                            $$perubahan .= '"'.$row->count.'",';  
                        }                        
                        ?>         
                        
                        perubahanKuliahChart.data.labels=[<?=substr($dayLabel,0,strlen($dayLabel)-1);?>];
                        perubahanKuliahChart.data.datasets[0].data=[<?=substr($diganti,0,strlen($diganti)-1);?>];
                        perubahanKuliahChart.data.datasets[1].data=[<?=substr($ditiadakan,0,strlen($ditiadakan)-1);?>];
                        perubahanKuliahChart.data.datasets[2].data=[<?=substr($tambahan,0,strlen($tambahan)-1);?>];
                        perubahanKuliahChart.update();
                    }
                    else if($(this).attr('id')==='byYear'){
                        <?php                     
                        $yearLabel='"'.$statistic->requestByYear[0]->year.'",';
                        $diganti='';
                        $tambahan='';
                        $ditiadakan='';                   

                        foreach($statistic->requestByYear as $key => $row){
                            if($key>0 && $row->year != $statistic->requestByYear[$key - 1]->year){
                                $yearLabel .= '"'.$row->year.'",';         
                            }
                            $perubahan = strtolower(PerubahanKuliah_model::CHANGETYPE_TYPES[$row->changeType]);
                            $$perubahan .= '"'.$row->count.'",';                    
                        }?>
                        perubahanKuliahChart.data.labels=[<?=substr($yearLabel,0,strlen($yearLabel)-1);?>];
                        perubahanKuliahChart.data.datasets[0].data=[<?=substr($diganti,0,strlen($diganti)-1);?>];
                        perubahanKuliahChart.data.datasets[1].data=[<?=substr($ditiadakan,0,strlen($ditiadakan)-1);?>];
                        perubahanKuliahChart.data.datasets[2].data=[<?=substr($tambahan,0,strlen($tambahan)-1);?>];
                        perubahanKuliahChart.update();
                    }
                });  
                           
                $('#statistikPerubahanKuliah').on('shown.bs.collapse',function(){
                    
                    <?php                     
                    $yearLabel='"'.$statistic->requestByYear[0]->year.'",';
                    $diganti='';
                    $tambahan='';
                    $ditiadakan='';                   

                    foreach($statistic->requestByYear as $key => $row){
                        if($key>0 && $row->year != $statistic->requestByYear[$key - 1]->year){
                            $yearLabel .= '"'.$row->year.'",';         
                        }
                        $perubahan = strtolower(PerubahanKuliah_model::CHANGETYPE_TYPES[$row->changeType]);
                        $$perubahan .= '"'.$row->count.'",';                    
                    }?>

                    perubahanKuliahChart = new Chart(context, {
                        type: 'bar',
                        data: {
                            labels: [<?= substr($yearLabel,0,strlen($yearLabel)-1);?>],                                                    
                            datasets: [{
                                label: 'Diganti',
                                data: [<?=substr($diganti,0,strlen($diganti)-1);?>],
                                backgroundColor:'rgba(68, 114, 196, 0.5)',
                                borderWidth: 1                      
                            },
                            {
                                label: 'Ditiadakan',
                                data: [<?=substr($ditiadakan,0,strlen($ditiadakan)-1);?>],
                                backgroundColor:'rgba(237, 125, 49, 0.5)',
                                borderWidth: 1
                            },
                            {
                                label: 'Tambahan',
                                data: [<?=substr($tambahan,0,strlen($tambahan)-1);?>],
                                backgroundColor:'rgba(165, 165, 165, 0.3)',
                                borderWidth: 1
                            }]
                        },
                        options:{
                            title:{
                                display:true,
                                fontSize:24,
                                fontColor:"black",
                                text: 'Statistik Diganti, Ditiadakan, Tambahan Dibagi Berdasarkan Tahun'
                            },
                            tooltips:{
                                mode:'label',
                                position:'nearest'
                            },
                            legend:{
                                display:true,
                                fontSize:14
                            },
                            scales:{
                                xAxes:[{
                                    stacked:true
                                }],
                                yAxes:[{
                                    stacked:true
                                }]
                            }
                        },
                        hidden:true                                 
                    });
                    $('#statistikPerubahanKuliah').on('hidden.bs.collapse',function(){
                        perubahanKuliahChart.destroy();
                    });
                });                
            });
        
        </script>
    </body>
</html>