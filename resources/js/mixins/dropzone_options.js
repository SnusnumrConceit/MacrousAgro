export default {
    data() {
        return {
            dropzone_options: {
                url: 'https://httpbin.org/post',
                thumbnailWidth: 150,
                maxFilesize: 0.5,
                headers: { "X-CSRF-TOKEN": $('meta').attr('content') },

                "dictDefaultMessage": this.$t('dropzone.dictDefaultMessage.news'),

                "dictFallbackMessage":          this.$t('dropzone.dictFallbackMessage'),
                "dictResponseError":            this.$t('dropzone.dictResponseError'),
                "dictInvalidFileType":          this.$t('dropzone.dictInvalidFileType'),
                "dictFileTooBig":               this.$t('dropzone.dictFileTooBig'),
                "dictCancelUpload":             this.$t('dropzone.dictCancelUpload'),
                "dictUploadCanceled":           this.$t('dropzone.dictUploadCanceled'),
                "dictCancelUploadConfirmation": this.$t('dropzone.dictCancelUploadConfirmation'),
                "dictRemoveFile":               this.$t('dropzone.dictRemoveFile'),
                "dictRemoveFileConfirmation":   this.$t('dropzone.dictRemoveFileConfirmation'),
            }
        }
    }
};
