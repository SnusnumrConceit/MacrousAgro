<template>

</template>

<script>
  export default {
    name: "form",

    data() {
      return {
        article: {
          title: '',
          description: '',
          image: '',
          publication_date: '',
          is_published: true,
        }
      }
    },

    computed: {
      id() {
        return this.$route.params.id;
      }
    },

    methods: {
      async save() {
        if (this.id !== undefined) {
          const response = await axios.post(`/admin/articles/update/${this.id}`, {...this.article});

          switch (response.data.status) {
            case 'error':
              this.$swal(this.$t('swal.title.error', response.data.msg, 'error'));
              return false;

            case 'success':
              this.$swal(this.$t('swal.title.success', response.data.msg, 'success'));
              this.$router.push({name: 'News'});
              break;
          }
        } else {
          const response = await axios.post(`/admin/articles/create`, {...this.article});

          switch (response.data.status) {
            case 'error':
              this.$swal(this.$t('swal.title.error', response.data.msg, 'error'));
              return false;

            case 'success':
              this.$swal(this.$t('swal.title.success', response.data.msg, 'success'));
              this.$router.push({name: 'News'});
              break;
          }
        }
      }
    },

    created() {
      if (this.id) {
        this.loadData();
      }
    }
  }
</script>

<style scoped>

</style>
