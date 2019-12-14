<template>

</template>

<script>
  export default {
    name: "form",

    data() {
      return {
        category: {
          title: ''
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
        const response = await axios.get(`/admin/categories/${this.id}`);

        if (response.data.status === 'error') {
          this.$swal(this.$t('swal.title.error'), response.data.msg, 'error');
          return false;
        }

        this.category = response.data.category;
      },

      async save() {
        if (this.id !== undefined) {
          const response = await axios.post(`/admin/categories/update/${this.id}`, {
            category: {...this.category}
          });

          switch (response.data.status) {
            case 'success':
              this.$swal(this.$t('swal.title.success', response.data.msg, 'success'));
              this.$router.push({name: 'Categories'});
              break;

            default:
              this.$swal(this.$t('swal.title.error', response.data.msg, 'error'));
              return;
          }
        } else {
          const response = await axios.post('/admin/categories/create', {
            category: {...this.category}
          });

          switch (response.data.status) {
            case 'success':
              this.$swal(this.$t('swal.title.success', response.data.msg, 'success'));
              this.$router.push({name: 'Categories'});
              break;

            default:
              this.$swal(this.$t('swal.title.error', response.data.msg, 'error'));
              return;
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
