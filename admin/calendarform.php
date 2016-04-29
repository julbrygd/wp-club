<div class="bootstrap-wrapper">
    <div class="container">
        <h1>Club Termine</h1>
        <form class="form-horizontal">
            <div class="form-group">
                <label for="txtTitle" class="col-sm-2 control-label">Title</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="txtTitle" placeholder="Titel">
                </div>
            </div>
            <div class="form-group">
                <label for="txtDescription" class="col-sm-2 control-label">Beschreibung</label>
                <div class="col-sm-10">
                    <div id="txtDescription"></div>
                </div>
            </div>
            <div class="form-group">
                <label for="txtFrom" class="col-sm-2 control-label">Datum</label>
                <div class="col-sm-10">
                    <div class="row">
                        <div class="col-sm-6"><label for="dateFrom">Von</label></div>
                        <div class="col-sm-6"><label for="dateTo">Bis</label></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div id="dateFrom"></div>
                        </div>
                        <div class="col-sm-6">
                            <div id="dateTo"></div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="form-group">
                <label for="txtPlace" class="col-sm-2 control-label">Ort</label>
                <div class="col-sm-10">
                    <input type="text" id="txtPlace" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    
            <br />  
                    <button type="button" id="btnSave" class="btn btn-default">Speichern</button>
                </div>
            </div>
    </div>


</div>

<link href="http://netdna.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet">

<!-- include summernote css/js-->
<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.7.3/summernote.css" rel="stylesheet">
<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.7.3/summernote.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/i18n/jquery-ui-i18n.min.js"></script>
<script src="<?php echo Club\Club::getInstance()->plugin_url ?>/js/jquery.datetimepicker.full.min.js"></script>
<link href="<?php echo Club\Club::getInstance()->plugin_url ?>/css/jquery.datetimepicker.min.css" rel="stylesheet" />
<script type="text/javascript">
    var $ = jQuery.noConflict();
    $(document).ready(function () {
        $('#txtDescription').summernote();
        $('#btnSave').on('click', function () {
            var data = {
                title: $("#txtTitle").val(),
                desc: $("txtDescription").val(),
                from: $('#dateFrom').val(),
                to: $('#dateTo').val()
            };
            alert(JSON.stringify(data));
        });
        $('#dateFrom').datetimepicker({
            format: 'd.m.Y H:i',
            inline: true,
            lang: 'de',
            onSelectDate: function (ct) {
                $('#dateTo').datetimepicker('setOptions',{
                    minDate: $('#dateFrom').val() ? $('#dateFrom').val() : false
                });
                
            }
        });
        $('#dateTo').datetimepicker({
            format: 'd.m.Y H:i',
            inline: true,
            lang: 'de',
            minDate: $('#dateFrom').val(),
            onSelectDate: function (ct) {
                $('#dateFrom').datetimepicker('setOptions',{
                    maxDate: $('#dateTo').val() ? $('#dateTo').val() : false
                });
                
            }
        });
    });

</script>

