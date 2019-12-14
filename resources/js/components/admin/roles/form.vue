<template>

</template>

<script>
  export default {
    name: "form",

    data() {
      return {
        role: {
          name: '',
          description: '',
          slug: ''
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
        const response = await axios.get(`/admin/roles/${this.id}`);

        if (response.data.status === 'error' || response.status !== 200) {
          this.$swal(this.$t('swal.title.error', response.data.msg, 'error'));
          return false;
        }

        this.role = response.data.role;
      },

      async save() {
        if (this.id) {
          const response = await axios.post(`/admin/roles/update/${this.id}`, {
            ...this.role
          });

          switch (response.data.status) {
            case 'error':
              this.$swal(this.$t('swal.title.error', response.data.msg, 'error'));
              return false;

            case 'success':
              this.$swal(this.$t('swal.title.success', response.data.msg, 'success'));
              this.$router.push({ name: 'Roles' });
          }
        } else {
          const response = await axios.post(`/admin/roles/create`, {
            ...this.role
          });

          switch (response.data.status) {
            case 'error':
              this.$swal(this.$t('swal.title.error', response.data.msg, 'error'));
              return false;

            case 'success':
              this.$swal(this.$t('swal.title.success', response.data.msg, 'success'));
              this.$router.push({ name: 'Roles' });
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
