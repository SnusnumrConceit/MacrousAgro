<template>
    <v-btn color="success" outlined @click="exportData" class="float-right">
        {{ $t('buttons.export') }}
    </v-btn>
</template>

<script>
  export default {
    name: "UserExport",

    methods: {
      /**
       * Экспорт пользователей
       */
      exportData() {
        axios({
          url: `/users/export`,
          method: 'POST',
          responseType: 'blob',
        }).then((response) => {
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'users.xlsx');
          document.body.appendChild(link);
          link.click();
        }).catch(e => {
          this.showNotification({ type: 'error', message: e.response.data.message});
        });
      },
    }
  }
</script>