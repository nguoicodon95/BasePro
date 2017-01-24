<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CRUD Generator | Core systems builder</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.3.2/css/AdminLTE.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/skins/all.css">
</head>
<style>
    .chk-align {
        padding-right: 10px;
    }

    .chk-label-margin {
        margin-left: 5px;
    }

    .required {
        color: red;
        padding-left: 5px;
    }

    .btn-green {
        background-color: #00A65A !important;
    }

    .btn-blue {
        background-color: #2489C5 !important;
    }

    .panel, .panel-heading {
        border-radius: 0;
    }
</style>
<body class="skin-blue" style="background-color: #ecf0f5">
<div class="col-md-10 col-md-offset-1">
    <section class="content">
        <div id="info" style="display: none"></div>
        <div class="box box-primary col-lg-12">
            <div class="box-header" style="margin-top: 10px">
                <h1 class="box-title" style="font-size: 30px">Xây dựng CRUD tổng quan</h1>
            </div>
            <div class="box-body">
                <form id="form">
                    <input type="hidden" name="_token" id="token" value="{!! csrf_token() !!}"/>

                    <div class="form-group col-md-4">
                        <label for="txtCustomTblName">Tên CRUD<span class="required">*</span></label>
                        <input type="text" class="form-control" required id="txtCustomTblName" placeholder="Enter crud name">
                    </div>

                    <div class="form-group col-md-4">
                        <label for="txtModelName">Model Namespace</label>
                        <input type="text" class="form-control" id="txtModelName" placeholder="Enter name">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="txtControllerName">Controller Namespace</label>
                        <input type="text" class="form-control" id="txtControllerName" placeholder="Enter name">
                    </div>

                    <div class="form-group col-md-4">
                        <label for="txtViewPath">Đường dẫn View</label>
                        <input type="text" class="form-control" id="txtViewPath" placeholder="Enter prefix">
                    </div>
                    
                    <div class="form-group col-md-4">
                        <label for="txtPrefix">Route group</label>
                        <input type="text" class="form-control" id="txtPrefix" placeholder="Enter prefix">
                    </div>
                    

                    <div class="form-group col-md-12" style="margin-top: 7px">
                        <div class="form-control" style="border-color: transparent;padding-left: 0px">
                            <label style="font-size: 18px">Fields</label>
                        </div>

                        <div class="panel panel-danger">
                            <div class="panel-heading">
                                <h3 class="panel-title">Lưu ý thêm các trường</h3>
                            </div>
                            <div class="panel-body">
                                <ul>
                                    <li>Primary: Trường khóa chính mặc định sẽ được thêm là ID và type là Auto Increment.
                                        <ul>
                                            <li>Nên không cần phải làm bất cứ điều gì cho khóa chính.</li>
                                        </ul>
                                    </li>
                                    <li>
                                        Created_at & Updated_at: Trường thời gian tạo và cập nhật sẽ được tự động thêm, vì vậy bạn không cần phải làm điều gì về nó.
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive col-md-12">
                        <table class="table table-striped table-bordered" id="table">
                            <thead class="no-border">
                            <tr>
                                <th>Field Name</th>
                                <th style="width: 150px;">DB Type</th>
                                <th>Validations</th>
                                <th style="width: 150px;">Html Type</th>
                                <th style="width: 180px;">Foreign</th>
                            </tr>
                            </thead>
                            <tbody id="container" class="no-border-x no-border-y ui-sortable">

                            </tbody>
                        </table>
                    </div>

                    <div class="form-inline col-md-12" style="padding-top: 10px">
                        <div class="form-group chk-align" style="border-color: transparent;">
                            <button type="button" class="btn btn-success btn-flat btn-green" id="btnAdd"> Thêm trường mới
                            </button>
                        </div>
                    </div>

                    <div class="form-inline col-md-12" style="padding:15px 15px;text-align: right">
                        <div class="form-group" style="border-color: transparent;padding-left: 10px">
                            <button type="submit" class="btn btn-flat btn-primary btn-blue" id="btnGenerate">Ok, Làm
                            </button>
                        </div>
                        <div class="form-group" style="border-color: transparent;padding-left: 10px">
                            <button type="button" class="btn btn-default btn-flat" id="btnReset" data-toggle="modal"
                                    data-target="#confirm-delete"> Thôi, bỏ đi
                            </button>
                        </div>
                    </div>


                    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog"
                         aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal"
                                            aria-hidden="true">&times;</button>
                                    <h4 class="modal-title" id="myModalLabel">Xác nhận Đặt lại</h4>
                                </div>

                                <div class="modal-body">
                                    <p style="font-size: 16px">Điều này sẽ đặt lại tất cả các lĩnh vực của bạn. Bạn có muốn tiếp tục?</p>

                                    <p class="debug-url"></p>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Oh, Không
                                    </button>
                                    <a id="btnModelReset" class="btn btn-flat btn-danger btn-ok" data-dismiss="modal">Ok, Làm đi</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </section>
</div>

</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/icheck.min.js"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script>
    $("select").select2({width: '100%'});
    var fieldIdArr = [];
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });

        $("#relationship_tbl").on("change", function () {
            alert(1)
        });

        $(document).ready(function () {
            var htmlStr = '<tr class="item" style="display: table-row;"></tr>';
            var commonComponent = $(htmlStr).filter("tr").load('{!! url('') !!}/dev/crud/field-template');

            $("#btnAdd").on("click", function () {
                var item = $(commonComponent).clone();
                initializeCheckbox(item);
                $("#container").append(item);
            });

            $("#btnModelReset").on("click", function () {
                $("#container").html("");
                $('input:text').val("");
                $('input:checkbox').iCheck('uncheck');

            });

            $("#form").on("submit", function () {
                $('#btnGenerate').attr('disabled', 'true');
                var fieldArr = [];
                $('.item').each(function () {

                    var htmlType = $(this).find('.drdHtmlType');
                    var htmlValue = "";
                    if ($(htmlType).val() == "select" || $(htmlType).val() == "radio") {
                        htmlValue = $(this).find('.drdHtmlType').val() + ':' + $(this).find('.txtHtmlValue').val();
                    }
                    else {
                        htmlValue = $(this).find('.drdHtmlType').val();
                    }

                    fieldArr.push({
                        fieldInput: $(this).find('.txtFieldName').val() + ':' + $(this).find('.txtdbType').val(),
                        htmlType: htmlValue,
                        validations: $(this).find('.txtValidation').val(),
                        foreign_keys: $(this).find('.relationship_tbl').val() + ':' + $(this).find('.column_tbl').val()
                    });
                });

                var data = {
                    modelName: $('#txtModelName').val(),
                    routeGroup: $('#txtPrefix').val(),
                    crudName: $('#txtCustomTblName').val(),
                    controllerName: $('#txtControllerName').val(),
                    viewPath: $('#txtViewPath').val(),
                    fields: fieldArr
                };

                data['_token'] = $('#token').val();

                $.ajax({
                    url: '{!! url('') !!}/dev/crud/generator-builder/generate',
                   // type: "POST",
                    method: "POST",
                    dataType: 'json',
                    contentType: 'application/json',
                    data: JSON.stringify(data),
                    success: function (result) {
                        $("#info").html("");
                        $("#info").append('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>' + result + '</strong></div>');
                        $("#info").show();
                        setTimeout(function () {
                            $('#info').fadeOut('fast');
                            window.location.reload(true)
                        }, 3000);
                    },
                    error: function (result) {
                        $("#info").html("");
                        $("#info").append('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Fail!</strong></div>');
                        $("#info").show();
                        setTimeout(function () {
                            $('#info').fadeOut('fast');
                        }, 3000);
                    }
                });

                return false;
            });

            function renderPrimaryData(el) {

                $('.chkPrimary').iCheck(getiCheckSelection(false));

                $(el).find('.txtFieldName').val("id");
                $(el).find('.txtdbType').val("increments");
                $(el).find('.chkSearchable').attr('checked', false);
                $(el).find('.chkFillable').attr('checked', false);
                $(el).find('.chkPrimary').attr('checked', true);
                $(el).find('.chkInForm').attr('checked', false);
                $(el).find('.chkInIndex').attr('checked', false);
            }

            function renderTimeStampData(el) {
                $(el).find('.txtdbType').val("timestamp");
                $(el).find('.chkSearchable').attr('checked', false);
                $(el).find('.chkFillable').attr('checked', false);
                $(el).find('.chkPrimary').attr('checked', false);
                $(el).find('.chkInForm').attr('checked', false);
                $(el).find('.chkInIndex').attr('checked', false);
                $(el).find('.drdHtmlType').val('date').trigger('change');
            }

        });

        function initializeCheckbox(el) {
            $(el).find('input:checkbox').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue'
            });
            $(el).find("select").select2({width: '100%'});

            $(el).find(".chkPrimary").on("ifClicked", function () {
                $('.chkPrimary').each(function () {
                    $(this).iCheck('uncheck');
                });
            });

            $(el).find(".chkPrimary").on("ifChanged", function () {
                if ($(this).prop('checked') == true) {
                    $(el).find(".chkSearchable").iCheck('uncheck');
                    $(el).find(".chkFillable").iCheck('uncheck');
                    $(el).find(".chkInForm").iCheck('uncheck');
                }
            });

            var htmlType = $(el).find('.drdHtmlType');

            $(htmlType).select2().on('change', function () {
                if ($(htmlType).val() == "select" || $(htmlType).val() == "radio")
                    $(el).find('.htmlValue').show();
                else
                    $(el).find('.htmlValue').hide();
            });

            var tbl = $(el).find('.relationship_tbl');

            $(tbl).select2().on('change', function () {
                $.ajax({
                    url: '{!! url('') !!}/dev/crud/generator-builder/column-table',
                    method: "POST",
                    data: {table: tbl.val()},
                    success: function (result) {
                       $(el).find('#rs_column').html(result)
                    }
                });

            });

        }

    });

    function getiCheckSelection(value) {
        if (value == true)
            return 'checked';
        else
            return 'uncheck';
    }

    function removeItem(e) {
        e.parentNode.parentNode.parentNode.removeChild(e.parentNode.parentNode);
    }

</script>
</html>
