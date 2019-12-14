<template>

</template>

<script>
  export default {
    name: "form",

    data() {
      return {
        video: {
          title: '',
          path: ''
        }
      }
    },

    computed: {
      id() {
        return this.$route.params.id;
      }
    },

    methods: {
      async loadData() {
        const response = await axios.get(`/admin/video/${this.id}`);

        if (response.data.status === 'error') {
          this.$swal(this.$t('swal.title.error', response.data.msg, 'error'));
          return false;
        }

        this.video = response.data.video;
      },

      async save() {
        if (this.id !== undefined) {
          const response = await axios.post(`/admin/video/update/${this.id}`, {
            ...this.video
          });

          switch (response.data.status) {
            case 'error':
              this.$swal(this.$t('swal.title.error', response.data.msg, 'error'));
              return false;

            case 'success':
              this.$swal(this.$t('swal.title.success', response.data.msg, 'success'));
              this.$router.push({name: 'Videos'});
          }
        } else {
          const response = await axios.post(`/admin/video/create`, {
            ...this.video
          });

          switch (response.data.status) {
            case 'error':
              this.$swal(this.$t('swal.title.error', response.data.msg, 'error'));
              return false;

            case 'success':
              this.$swal(this.$t('swal.title.succss', response.data.msg, 'success'));
              this.$router.push({name: 'Videos'});
          }
        }
      }
    },

    created() {
      if (this.id !== undefined) {
        this.loadData();
      }
    }
  }
</script>

<style scoped>

</style>
