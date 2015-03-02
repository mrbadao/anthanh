
/* +++++++++++++++++++++++++UploadImage's functions++++++++++++++++++++++++++*/

$(window).ready(function () {
    $(".fuTagsUI_video").click(function () {
        var tagName = $(this).html();
        if($('#UI_video_tag').val().indexOf(tagName) == -1){
            $('#UI_video_tag').addTag(tagName);
        }
    });

    $("#send_to_editor").click(function(){
        var is_checked = ($(this).prop("checked"))? true : false;
        if(is_checked){
            $("#positionHolder").show();
        }else{
            $("#positionHolder").hide();
        }

    });
    $('#UI_video_tag').tagsInput({width:'94%', height:'auto' });

    $("#UI_upload_video").change(function(){
        readVideoURL(this);
    });
});


function UploadVideo(s) {
    // send Editor name to popup for calling back
    var currentEditor = CKEDITOR.currentInstance.name;

    // initialize a new popup
    $("#popUpUploadVideo").dialog({
        autoOpen:false,
        modal:true,
        width:600,
        draggable:true,
        resizable:true,
        show:"fade",
        hide:"fade",
        buttons: {
            Cancel: function() {
                $(this).dialog("close");
            },
            Save: function() {
                checkThisVideoForm();
            },
            "New Upload":function(){
                newVideoUpload();
            }
        }
    });
    $("#popUpUploadVideo").dialog("open");
    newVideoUpload();

    // hide new upload when first load
    $('.ui-dialog-buttonpane button:contains("New Upload")').button().hide();
    $("#currentEditor_UI").val(currentEditor);
    return false;
}



function readVideoURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        $('#UI_video').attr("controls");
        reader.onload = function (e) {
            $('#UI_video').attr('src', e.target.result);
        };
        console.log(input.files[0]);
        reader.readAsDataURL(input.files[0]);
        $("#UI_video").show();
        $("#infoVideoHolder").show();
        $("#UI_videoName").html(input.files[0].name);
        $("#UI_videoType").html(input.files[0].type);
    }
}
function checkThisVideoForm(){
    var hasError  = false;
    if($("#UI_upload_video").val() == ""){
        $("#UI_video_err").show();
        hasError = true;
    }else{
        $("#UI_video_err").hide();
    }
    if($("#UI_video_title").val() == ""){
        $("#UI_video_title_err").show();
        hasError = true;
    }else{
        $("#UI_video_title_err").hide();
    }
    if($("#UI_video_caption").val() == ""){
        $("#UI_video_caption_err").show();
        hasError = true;
    }else{
        $("#UI_video_caption_err").hide();
    }
    if(hasError){
        return false;
    }

    var xhr = new XMLHttpRequest();
    var fileToUpload = document.getElementById("UI_upload_video").files[0];
    var uploadStatus = xhr.upload;

    var myProgess = 0;
    var myProgessTex = 0;
    uploadStatus.addEventListener("progress", function (ev) {
        $("#processVideoBar").empty();
        $("#processVideoBar").append('<div id="videouploading" style="text-align: center; padding: 5px; color: #000000;"></div><div id="videoUploadPercentage" style="background: #00ff00; opacity: 0.5; height: 20px; text-align: center; color: #000000;"></div>');

        if (ev.lengthComputable) {
            myProgess = (ev.loaded / ev.total) * 50;
            if (myProgess.toFixed){
                myProgessTex = myProgess.toFixed(0);
            }else{
                myProgessTex = myProgess;
            }
            $("#videoUploadPercentage").css('width',((ev.loaded / ev.total) * 50 + "%"));
            $('#videoUploadPercentage').html((myProgessTex + "%"));

        }
    }, false);
    uploadStatus.addEventListener("error", function (ev) {$("#errorVideoMsg").html(ev)}, false);
    uploadStatus.addEventListener("load", function (ev) {doAfterUploadingVideo(xhr)}, false);
    xhr.open(
        "POST",
        "/contents/uploadobject",
        true
    );

    xhr.setRequestHeader("Cache-Control", "no-cache");
    xhr.setRequestHeader("Content-Type", "multipart/form-data");
    xhr.setRequestHeader("X-File-Name", fileToUpload.name);
    xhr.setRequestHeader("X-File-Size", fileToUpload.size);
    xhr.setRequestHeader("X-File-Type", fileToUpload.type);
    xhr.setRequestHeader("X-Object-Type", "video");
    xhr.setRequestHeader("Content-Type", "application/octet-stream");
    xhr.send(fileToUpload);


}
function doAfterUploadingVideo(xhr)
{
    xhr.onreadystatechange = function() {
        if(xhr.readyState == 4 && xhr.status == 200)  {
            var data = $("#popUpUploadVideo :input").serialize();
            $.ajax({
                type: "post",
                dataType: "json",
                url: '/contents/uploadobject',
                data: data,
                error:function(){

                },
                submit:function(){
                    $("#videoUploadPercentage").css('width',("75%"));
                    $('#videoUploadPercentage').html(("75%"));
                },
                success:function(data){

                    $("#videoUploadPercentage").css('width',("100%"));
                    $('#videoUploadPercentage').html(("100%"));

                    //show New upload button and hide Save button when done uploading
                    $('.ui-dialog-buttonpane button:contains("New Upload")').button().show();
                    $('.ui-dialog-buttonpane button:contains("Save")').button().hide();

                    setTimeout(function(){
                        if($("#send_to_editor").prop("checked") == 1){
                            var position =  $("input:radio[name=position]:checked").val();
                            var style = "";
                            if(position == 3){ // center
                                var videoTag = "<div style='text-align: center;height: 100%; width:100%'>" +
                                    "<iframe allowfullscreen='' frameborder='0' height='315' src='//www.youtube.com/embed/video_id' width='560'></iframe></div>";
                            }else if(position == 4){ //right
                                var videoTag = "<div style='text-align: right;height: 100%; width:100%'>" +
                                    "<iframe allowfullscreen='' frameborder='0' height='315' src='//www.youtube.com/embed/video_id' width='560' style='float: right'></iframe></div>";
                            }else{ // left & no set
                                var videoTag = "<div style='text-align: left;height: 100%; width:100%'>" +
                                    "<iframe allowfullscreen='' frameborder='0' height='315' src='//www.youtube.com/embed/video_id' width='560' style='float: left'></iframe></div>";
                            }

                            var currentEditor_UI = $("#currentEditor_UI").val();
                            var currentEditor = CKEDITOR.instances[currentEditor_UI];
                            videoTag = videoTag.replace('video_id',data.video_id);
                            currentEditor.insertHtml( videoTag );
                            newVideoUpload();
                            closeDialog($("#popUpUploadVideo"));

                        }
                    },1000);
                }
            });
        }
    };
}
function closeDialog(selector){
    $(selector).dialog('close');
}

function newVideoUpload(){

    $(":input", $("#popUpUploadVideo")).not(":button, :submit, :reset, :hidden").each(function () {
        this.value = this.defaultValue;
    });

    $("#UI_tag").importTags("");

    $("#UI_video").hide();
    $("#infoHolder").hide();
    $("#processBar").html("");
    $("#errorMsg").html("");
    $(".UI_error").each(function(){
        $(this).hide();
    });
    $('.ui-dialog-buttonpane button:contains("New Upload")').button().hide();
    $('.ui-dialog-buttonpane button:contains("Save")').button().show();
}