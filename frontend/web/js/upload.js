var Upload = function (file,select,pre_select,hidden_select) {
  this.file = file;
  this.selector = select;
  this.pre_select = pre_select;
  this.hidden_select = hidden_select;
};
Upload.prototype.getType = function() {
  return this.file.type;
};
Upload.prototype.getSize = function() {
  return this.file.size;
};
Upload.prototype.getName = function() {
  return this.file.name;
};
Upload.prototype.doUpload = function () {
  var that = this;
  var formData = new FormData();

  formData.append("UpLoadImageForm[imageFile]", this.file, this.getName());

  $.ajax({
    type: "POST",
    url: "/file/upload-image",
    xhr: function () {
      var myXhr = $.ajaxSettings.xhr();
      if (myXhr.upload) {
        myXhr.upload.addEventListener('progress', function (event) {
          var percent = 0;
          var position = event.loaded || event.position;
          var total = event.total;
          if (event.lengthComputable) {
            percent = Math.ceil(position / total * 100);
          }
          // $(progress_bar_id + " .progress-bar").css("width", +percent + "%");
          $(that.selector).next().html("上传成功");
        }, false);
      }
      return myXhr;
    },
    success: function (data) {
      $(that.pre_select).attr('src',data.url);
      $(that.hidden_select).val(data.path);
    },
    error: function (error) {
    },
    async: true,
    data: formData,
    cache: false,
    contentType: false,
    processData: false,
    timeout: 60000
  });
};