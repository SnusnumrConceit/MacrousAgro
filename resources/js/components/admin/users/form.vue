<template>

</template>

<script>
  export default {
    name: "form",

    data() {
      return {
        user: {
          email: '',
          last_name: '',
          first_name: '',
          birthday: ''
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
        const response = await axios.get(`/admin/users/${this.$route.params.id}`);

        if (response.data.status === 'error' || response.status !== 200) {
          this.$swal(this.$t('swal.title.error', response.data.msg, 'error'));
          return false;
        }

        this.user = response.data.user;
      },

      async save() {
        if (this.id !== undefined) {
          const response = await axios.put(`/admin/users/update/${this.id}`, {
            ...this.user
          });

          switch (response.data.status) {
            case 'error':
              this.$swal(this.$t('swal.title.error', response.data.msg, 'error'));
              return false;

            case 'success':
              this.$swal(this.$t('swal.title.success', response.data.msg, 'success'));
              this.$router.push({name: 'Users'});
              break;
          }
        } else {
          const response = await axios.post(`/admin/users/create`, {
            ...this.user
          });

          switch (response.data.status) {
            case 'error':
              this.$swal(this.$t('swal.title.error', response.data.msg, 'error'));
              return false;

            case 'success':
              this.$swal(this.$t('swal.title.success', response.data.msg, 'success'));
              this.$router.push({name: 'Users'});
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
