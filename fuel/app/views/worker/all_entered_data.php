<div class="container">
    <div class="row">
        <div class="col-md-5">
            <h1>Iesniegtie lietotāju dati</h1>
            <hr/>
            <a href="/" alt="atpakaļ">Doties atpakaļ</a>
        </div>
    </div>

        <div class="row main-block">
    
                <?php if(Session::get_flash('success')) { ?>
                    <div class="col-md-8">
                        <div class="alert alert-success">
                            <p class="text-success"><?php echo Session::get_flash('success'); ?></p>
                        </div>
                    </div>
                    <div class="clearfix"></div>

                <?php } elseif(Session::get_flash('error')) { ?>
                    <div class="col-md-8">
                        <div class="alert alert-danger">
                            <p class="text-danger"><?php echo Session::get_flash('error'); ?></p>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                <?php } ?>
    
                <div class="panel-group" id="accordion">
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                          Iesniegtie skaitītāju rādījumi
                        </a>
                      </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in">
                      <div class="panel-body">
                          <?php if(!empty($readings)) { ?>
                            <table class="table text-center">
                                <thead>
                                    <th>Klienta numurs</th>
                                    <th>Skaitāja numurs</th>
                                    <th>Iepriekšējais rādījums</th>
                                    <th>Iesniegtais rādījums</th>
                                    <th>Datums</th>
                                    <th>Statuss</th>
                                    <th>Atgriezt rādījumu</th>
                                    <th>Apstiprināt rādījumu</th>
                                </thead>   
                                <?php foreach($readings as $reading) { ?>
                                <tr <?php if(in_array($reading->status, array('Apstiprināts','Atgriezts'))) echo 'class="success"'; ?>>
                                    <td><?php echo $reading -> client_number; ?></td>
                                    <td><?php echo $reading -> meter_number; ?></td>
                                    <td><?php echo $reading -> last_lead; ?></td>
                                    <td><?php echo $reading -> lead; ?></td>
                                    <td><?php echo date_format(date_create($reading -> date_taken),'d.m.Y'); ?></td>
                                    <td><?php echo $reading -> status; ?></td>
                                    <td><a class="return_trigger <?php if(in_array($reading->status, array('Apstiprināts','Atgriezts'))) echo 'hidden'; ?>" data-cln="<?php echo $reading -> client_id; ?>" data-rdn="<?php echo $reading -> rdn_id; ?>" href="#" data-toggle="modal" data-target="#atgriezt_radijumu"><span class="glyphicon glyphicon-remove"></span></a></td>
                                    <td><a class="<?php if(in_array($reading->status, array('Apstiprināts','Atgriezts'))) echo 'hidden'; ?>" href='/darbinieks/skaititaji/radijumi/apstiprinat/<?php echo $reading->rdn_id; ?>/<?php echo $reading->client_id; ?>'><span class="glyphicon glyphicon-ok"></span></a></td>
                                </tr>
                                <?php } ?>
                            </table>
                          <?php } else { ?>
                            <p>Nav iesniegts neviens skaitītāja rādījums</p>
                          <?php } ?>
                      </div>
                    </div>
                  </div>
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                          Iesniegtie pakalpojumu pieprasījumi
                        </a>
                      </h4>
                    </div>
                    <div id="collapseTwo" class="panel-collapse collapse">
                      <div class="panel-body">
                          <?php if(!empty($services)) { ?>
                          <?php } else { ?>
                          <p>Pašlaik nav iesniegts neviens pakalpojuma pieprasījums</p>
                          <?php } ?>
                      </div>
                    </div>
                  </div>
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                          Iesniegtās avārijas
                        </a>
                      </h4>
                    </div>
                    <div id="collapseThree" class="panel-collapse collapse">
                      <div class="panel-body">
                          <?php if(!empty($emergencies)) { ?>
                          <?php } else { ?>
                          <p>Pašlaik nav iesniegta neviena avārija</p>
                          <?php } ?>
                      </div>
                    </div>
                  </div>
                </div>
        </div>
</div><!--/.container -->

<!-- atgriezt rādījumu -->
<div class="modal fade" id="atgriezt_radijumu" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Atgriešanas pamatojums</h4>
      </div>

    <form id="return_reading" action="/darbinieks/skaititaji/radijumi/atgriezt" method="POST" role="form">
       <input type="hidden" name="<?php echo \Config::get('security.csrf_token_key');?>" value="<?php echo \Security::fetch_token();?>" />
       <input id="rdn_id" type="hidden" name="reading_id" />
       <input id="cln_id" type="hidden" name="client_id" />
       
       <div class="modal-body">
        <div class="form-group">
            <label for="number">Paskaidrojums abonentam:</label>       
            <textarea name="notes" class="form-control" placeholder="Īss un skaidrs paskaidrojums abonentam, kādēļ rādījums tiek atgriezts"></textarea>
        </div>
          
        <div class="modal-footer">
          <button id="reset" type="button" class="btn btn-default" data-dismiss="modal">Notīrīt un aizvērt</button>
          <button type="submit" class="btn btn-primary">Atgriezt</button>
        </div>
      </div>
    </form>
  </div>
</div>
</div>
<!-- atgriezt rādījumu -->

<script>
    $(document).ready(function() {
        $('.return_trigger').click(function() {
           $('#rdn_id').attr('value',$(this).attr('data-rdn')); 
           $('#cln_id').attr('value',$(this).attr('data-cln')); 
        });
    });
</script>
