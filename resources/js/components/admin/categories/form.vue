<template>
    <div>
        <v-card>
            <v-form v-model="form.valid">
                <v-card-text>
                    <v-text-field v-model="category.name"
                                  label="Название*"
                                  required
                                  clearable
                                  counter
                                  :rules="form.name.rules"
                                  maxlength="25"></v-text-field>
                    </v-card-text>
                <v-card-actions>
                    <v-btn color="success" outlined @click="save" :disabled="! form.valid">
                        {{ $t('categories.btn.edit')}}
                    </v-btn>
                    <v-btn @click="goBack" color="default" outlined>
                        {{ $t('categories.btn.back')}}
                    </v-btn>
                </v-card-actions>
            </v-form>
        </v-card>
    </div>
</template>

<script>
  export default {
    name: "form",

    data() {
      return {
        category: {
          name: ''
        },

        form: {
          valid: false,

          name: {
            rules: [
              v => v.length <= 25 || 'Длина не может превышать 25 символов',
              v => v.length > 0 || 'Поле обязательное к заполнению'
            ]
          }
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
        const response = await axios.get(`/admin/categories/${this.id}/edit`);

        if (response.data.status === 'error') {
          this.$swal(this.$t('swal.title.error'), response.data.msg, 'error');
          return false;
        }

        this.category = response.data.category;
      },

      async save() {
        if (this.id !== undefined) {
          const response = await axios.put(`/admin/categories/${this.id}`, { ...this.category});

          switch (response.status) {
            case 200:
              this.$swal(this.$t('swal.title.success', response.data.msg, 'success'));
              this.goBack();
              break;

            case 500:
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
      },

      goBack() {
        this.$router.go(-1);
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
