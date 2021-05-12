<template src="./FileUpload.htm"></template>
<script>
  import axios from 'axios'

  export default {
    name: "file-upload",
    data() {
      return {
        file: '',
        image: '',
        progress: '',
        uploadComplete: false,
        uploadFailed: false,
        uploadInProgress: false,
        validationErrors: [],
      }
    },
    props: {
      imagePreview: {
        type: Boolean,
        default: false
      },
      accept: {
        type: String,
        default: ''
      },
      label: {
        type: String,
        default: ''
      },
      helpText: {
        type: String,
        default: ''
      },
      examplePhoto: {
        type: String,
        default: ''
      },
      maxSize: {
        type: Number,
        default: parseInt(window.config.maxFileSize.replace("M", "")) * 1024 * 1024
      },
      resolution: {
        type: String,
        default: 'not specified'
      }
    },
    methods: {
      getFileSizeReadable(bytes) {
        if (bytes >= 1073741824) {
          bytes = (bytes / 1073741824).toFixed(1) + " GB";
        }
        else if (bytes >= 1048576) {
          bytes = (bytes / 1048576).toFixed(1) + " MB";
        }
        else if (bytes >= 1024) {
          bytes = (bytes / 1024).toFixed(1) + " KB";
        }
        else if (bytes > 1) {
          bytes = bytes + " bytes";
        }
        else if (bytes === 1) {
          bytes = bytes + " byte";
        }
        else {
          bytes = "0 byte";
        }
        return bytes;
      },

      triggerSelect(e) {
        $(this.$refs.input).click();
      },

      onFileChange(e) {
        let files = e.target.files || e.dataTransfer.files;
        if (!files.length)
          return;
        this.file = files[0];

        //Check file size and clear if exceeds
        if (this.file.size > this.maxSize) {
          let args = {
            file_size: this.getFileSizeReadable(this.file.size),
            max_size: this.getFileSizeReadable(this.maxSize)
          };
          this.toastError(this.$t('file_upload_max_size_warning', args));

          this.clear();
          return;
        }

        $(this.$refs.filename).val(this.file.name);
        if (this.imagePreview) {
          this.createImage(files[0]);
        }
        this.upload();
      },

      createImage(file) {
        let reader = new FileReader();
        let self = this;
        reader.onload = (e) => {
          self.image = e.target.result;
        };
        reader.readAsDataURL(file);
      },

      clear() {
        this.image = '';
        this.file = '';
        $(this.$refs.filename).val('');
        var $el = $(this.$refs.input);
        $el.wrap('<form>').closest('form').get(0).reset();
        $el.unwrap();
        this.$emit('clearFile');
        this.validationErrors = [];
        this.uploadComplete = false;
      },


      upload() {
        if (!this.file) {
          return;
        }

        let self = this;

        var config = {
          onUploadProgress: function (progressEvent) {
            self.progress = Math.round((progressEvent.loaded * 100) / progressEvent.total);
          }
        };

        var data = new FormData();
        data.append('file', this.file);

        this.uploadInProgress = true;
        this.uploadComplete = false;
        this.uploadFailed = false;
        this.validationErrors = [];
        let url = 'file/upload';
        if (this.accept === "image/*") {
          url += '/image'
        }
        axios.post(url, data, config).then(response => {
          this.uploadComplete = true;
          this.uploadInProgress = false;
          this.$emit('uploadComplete', response.data)
        }).catch((e) => {
          this.uploadFailed = true;
          this.uploadInProgress = false;
          if (e.response.data.errors && e.response.data.errors.file) {
            this.validationErrors = e.response.data.errors.file;
          }
        });
      }
    }
  }
</script>