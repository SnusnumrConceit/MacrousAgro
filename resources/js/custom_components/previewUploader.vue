<template>
    <div class="container">
        <div class="avatar-upload">
            <div class="avatar-edit">
                <input type='file' id="imageUpload" :accept="this.extensions" @change="readUrl"/>
                <label for="imageUpload">
                    <v-icon>mdi-pencil</v-icon>
                </label>
            </div>
            <div class="avatar-preview">
                <div id="imagePreview" :style="{'background-image': `url(${this.dataSrc})`}" v-show="isImage"></div>
                <div v-show="isVideo">
                    <video id="videoPreview" :src="this.dataSrc" controls width="320" height="180" v-show="this.dataSrc.length"></video>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    name: "previewUploader",

    props: {
      src: {
        default: '',
        type: String
      },
      extensions: {
        default: '.png, .jpg, .jpeg',
        type: String
      },
      type: {
        default: 'image',
        type: String
      },
      reset: {
        default: false,
        type: Boolean
      }
    },

    data() {
      return {
        previewElemId: '#imagePreview',
        dataSrc: this.src
      }
    },

    computed: {
      isImage() {
        return this.type.includes('image');
      },

      isVideo() {
        return this.type.includes('video');
      }
    },

    methods: {
      readUrl($event) {
        let files = $event.target.files;
        if (files && files[0]) {
          let reader = new FileReader();

          reader.onload = (e) => {
            console.log(e.target.result);
            this.dataSrc = e.target.result;
            console.log(e.target.result);
            switch (files[0].type) {
              case 'video/mp4':
                this.previewElemId = '#videoPreview';
                break;
              default:
                this.previewElemId = '#imagePreview';
                break;
            }

            $(this.previewElemId).hide();
            $(this.previewElemId).fadeIn(650);

          };

          reader.readAsDataURL(files[0]);

          this.$emit('uploaded', files[0]);
        }
      },
    },

    watch: {
      'reset': function (after, before) {
        console.log(after);
        if (this.reset) {
          this.dataSrc = '';
          $('#imageUpload').val('');
        }
      }
    },
  }
</script>

<style scoped lang="scss">
    .avatar-upload {
        position: relative;
        max-width: 275px; // 205 original
        margin: 50px auto;
        .avatar-edit {
            position: absolute;
            right: 12px;
            z-index: 1;
            top: 10px;
            input {
                display: none;
                + label {
                    display: inline-block;
                    width: 34px;
                    height: 34px;
                    margin-bottom: 0;
                    border-radius: 100%;
                    background: #FFFFFF;
                    border: 1px solid transparent;
                    box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
                    cursor: pointer;
                    font-weight: normal;
                    transition: all .2s ease-in-out;
                    &:hover {
                        background: #f1f1f1;
                        border-color: #d6d6d6;
                    }
                    &:before {
                        color: #757575;
                        position: absolute;
                        top: 12px;
                        left: 0;
                        right: 0;
                        text-align: center;
                        margin: auto;
                    }
                }
            }
        }
        .avatar-preview {
            width: 250px;  // 192 originals of WxH
            height: 250px;
            position: relative;
            /*border-radius: 100%;*/
            border: 6px solid #F8F8F8;
            box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
            > div {
                width: 100%;
                height: 100%;
                /*border-radius: 100%;*/
                background-size: cover;
                background-repeat: no-repeat;
                background-position: center;
            }
        }
    }
</style>