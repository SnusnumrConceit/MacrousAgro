<template>

</template>

<script>
  export default {
    name: "form",

    data() {
      return {
        photo: {
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
        const response = await axios.get(`/admin/photo/${this.id}`);

        if (response.data.status === 'error' || response.status !== 200) {
          this.$swal(this.$t('swal.title.error', response.data.msg, 'error'));
          return false;
        }

        this.photo = response.data.photo;
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
