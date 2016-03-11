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
                <label for="txtFrom" class="col-sm-2 control-label">Von Datum</label>
                <div class="col-sm-10">
                    <div id="txtFrom" />
                </div>
            </div>
            <div class="form-group">
                <label for="txtFromTime" class="col-sm-2 control-label">Von Zeit</label>
                <div class="col-sm-10">
                    <input type="time" id="txtFromTime" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label for="txtTo" class="col-sm-2 control-label">Bis</label>
                <div class="col-sm-10">
                    <div id="txtTo" />
                </div>
            </div>
            <div class="form-group">
                <label for="txtToTime" class="col-sm-2 control-label">Bis Zeit</label>
                <div class="col-sm-10">
                    <input type="time" id="txtToTime" class="form-control" />
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
<!--
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.1/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.1/locale/de.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker-standalone.min.css" rel="stylesheet">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker-standalone.min.css"></script>
-->
<script type="text/javascript">
    var $ = jQuery.noConflict();
    $(document).ready(function () {
        $('#txtDescription').summernote();
        $("#txtFrom").datepicker($.datepicker.regional[ "de"]);
        $( "#txtFrom" ).datepicker( "option", "dateFormat", 'yy-mm-dd');
        $("#txtTo").datepicker($.datepicker.regional[ "de"]);
        $( "#txtTo" ).datepicker( "option", "dateFormat", 'yy-mm-dd');
        $('#btnSave').on('click', function(){
            var data = {
                from: $("#txtFrom").val()
            };
            alert(JSON.stringify(data));
        });
    });

</script>

